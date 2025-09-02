<?php

namespace App\Http\Controllers\Duties;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Proforma;
use App\Models\Task;
use App\Models\UoFileSubmission;
use App\Models\User;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UoFileApprovalController extends Controller
{
    public function index(Request $request, $tasks_id)
    {
        $this->authorize('canPerform', [Proforma::class, 'uo_file_approval']);
        $task        = Task::findOrFail($tasks_id);
        $departments = CmisApiService::apiFieldDepartments();
        $view        = $request->input('view', '');

        return view('duties.uoFileApprovalList', compact('departments', 'task', 'view'));
    }

    public function ajaxlist(Request $request, $tasks_id)
    {
        $this->authorize('canPerform', [Proforma::class, 'uo_file_approval']);
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');
        $overallSeniorityIndex = Proforma::getOverallSeniorityList();

        switch ($application_status) {
            case 'pending':  // currently pending on me
                $data = WorkflowHandler::proformaTaskCurrentData($task);
                break;

            case 'forwarded': // forwarded but process not yet completed
                $data = WorkflowHandler::proformaTaskForwardedData($task);
                break;

            case 'completed': // fully completed applications
                $data = WorkflowHandler::proformaTaskCompletedData($task);
                break;

            case 'rejected': // rejected at or after my stage
                $data = WorkflowHandler::proformaTaskRejectedData($task);
                break;

            default:
                return DataTables::of(collect())->make(true); // empty collection
        }

        // Filter only applications verified by current user (via relationship)
        /*
        $data = $data->filter(function ($item) {
            return $item->uoFileSubmission &&
                $item->uoFileSubmission->verified_by == Auth::user()->user_id;
        });*/

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('deceased_doe', fn($row) => $row->deceased_doe ? date('d M, Y', strtotime($row->deceased_doe)) : 'N/A')
            ->editColumn('created_at', fn($row) => $row->created_at ? date('d M, Y', strtotime($row->created_at)) : 'N/A')
            ->editColumn('applicant_dob', fn($row) => $row->applicant_dob ? date('d M, Y', strtotime($row->applicant_dob)) : 'N/A')
            ->addColumn('remarks', function ($row) {
                return $row->proformaLogs()
                    ->whereIn('action_name', ['forwarded', 'rejected', 'reverted', 'completed'])
                    ->latest()
                    ->value('action_remark') ?? 'N/A';
            })
            ->addColumn('action', function ($row) use ($application_status) {
                $resp = "<div class='d-flex gap-2'>";
                if ($application_status === 'pending') {
                    $resp .= "<a href='" . route('duties.uo.file-approval.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                } else {
                    $resp .= "<a href='" . route('duties.proforma.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                }
                $resp .= "</div>";
                return $resp;
            })
            ->addColumn('overall_seniority_idx', function ($row) use ($overallSeniorityIndex) {
                return $overallSeniorityIndex[$row->proforma_id] ?? 0;
            })
            ->addColumn('dept_seniority_idx', function ($row) {
                return Proforma::getDepartmentalSeniorityIndex($row->proforma_id);
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function view($id)
    {
        $proforma   = Proforma::findOrFail($id);
        $total_step = 4;
        $tasks      = getPrevNextTasks($id);
        $this->authorize('canPerformOnProforma', [$proforma, 'uo_file_approval']);

        //retrieving DP Nodal officers                
        $dpNodalUsers = User::whereHas('role', function ($query) {
            $query->where('role_name', 'DP Nodal');
        })->get();
        return view('duties.uoFileApproval', compact('proforma', 'total_step', 'tasks', 'dpNodalUsers'));
    }

    public function submit(Request $request, $id)
    {
        //

    }

    public function forward(Request $request, $id)
    {
        try {
            $request->validate([
                'remarks' => 'nullable|string|max:600',
                'dp_nodal_user_id' => 'required|exists:users,user_id',
            ]);

            //Getting the proforma and uo file submission detail
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canForward', [$proforma, 'uo_file_approval']);
            $uo_file_submission = UoFileSubmission::where('proforma_id', $id)->first();

            DB::beginTransaction();
            $uo_file_submission->verified_by = $request->dp_nodal_user_id;
            $uo_file_submission->save();

            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id'      => $proforma->proforma_id,
                'action_by'        => Auth::user()->user_id,
                'action_name'      => 'forwarded', //compulsory always forwarded for forwarded
                'action_remark'    => $request->remarks,
                'process_sequence' => $proforma->process_sequence,
            ]);
            WorkflowHandler::forwardApplication($proforma);
            DB::commit();
            return response()->json(['message' => 'Proforma forwarded successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error forwarding proforma: ' . $e->getMessage()], 422);
        }
    }
}
