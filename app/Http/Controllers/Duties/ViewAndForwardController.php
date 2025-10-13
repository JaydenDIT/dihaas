<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\Proforma;
use App\Models\Remark;
use App\Models\Task;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ViewAndForwardController extends Controller
{
    //
    public function index(Request $request, $tasks_id)
    {

        $task = Task::findOrFail($tasks_id);
        $departments = CmisApiService::apiFieldDepartments();
        $remarks = Remark::where('is_active', true)->orderBy('id')->get();
        $view = $request->input('view', '');
        return view('duties.viewAndForwardList', compact('departments', 'task', 'view', 'remarks'));
    }



    public function ajaxlist(Request $request, $tasks_id)
    {
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');
        $overallSeniorityIndex = Proforma::getOverallSeniorityList();
        switch ($application_status) {
            //proforma_status tells the current state of the application
            case 'pending':  // currently pending on the authenticated user
                $data = WorkflowHandler::proformaTaskCurrentData($task);
                break;
            case 'forwarded': //forwarded from me but entire process not completed
                $data = WorkflowHandler::proformaTaskForwardedData($task);
                break;
            case 'completed': //forwarded from me but entire process not completed
                $data = WorkflowHandler::proformaTaskCompletedData($task);
                break;
            case 'rejected': //forwarded from me or rejected during me but entire process is rejected later
                $data = WorkflowHandler::proformaTaskRejectedData($task);
                break;
            default:
                return DataTables::of([])->make(true); // No data for other statuses
                break;
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('deceased_doe', function ($row) {
                return date('d M, Y', strtotime($row->deceased_doe));
            })
            ->editColumn('created_at', function ($row) {
                return date('d M, Y', strtotime($row->created_at));
            })
            ->editColumn('proforma_submission_date', function ($row) {
                return date('d M, Y', strtotime($row->proforma_submission_date));
            })
            ->editColumn('applicant_dob', function ($row) {
                return date('d M, Y', strtotime($row->applicant_dob));
            })
            ->addColumn('remarks', function ($row) {
                $log = $row->proformaLogs()
                    ->whereIn('action_name', ['verified', 'forwarded', 'rejected', 'reverted', 'completed'])
                    ->latest()
                    ->first();
                if ($log) {
                    return (object)[
                        'remark' => $log->action_remark,
                        'date' => $log->created_at->format('d M, Y'),
                        'time' => $log->created_at->format('h:i A'),
                        'by' => $log->actionBy->fullname ?? 'N/A'
                    ];
                } else {
                    return null;
                }
            })
            ->addColumn('action', function ($row) {
                $resp = "<div class='d-flex gap-2'>";
                $resp .= "<a href='" . route('duties.proforma.view', $row->proforma_id) . "' target='_blank' class='btn btn-sm btn-primary view-btn'>View</a>";
                $resp .= "</div>";
                return $resp;
            })
            ->addColumn('overall_seniority_idx', function ($row) use ($overallSeniorityIndex) {
                return $overallSeniorityIndex[$row->proforma_id] ?? 'N/A';
            })
            ->addColumn('dept_seniority_idx', function ($row) {
                return Proforma::getDepartmentalSeniorityIndex($row->proforma_id);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function viewAndForward($id)
    {
        $proforma = Proforma::findOrFail($id);
        $total_step = 4;
        $tasks = getPrevNextTasks($id);
        $remarks = Remark::where('is_active', true)->orderBy('id')->get();
        $this->authorize('canPerformOnProforma',  [$proforma, 'view_and_forward']);
        return view('duties.viewAndForward', compact('proforma', 'total_step', 'tasks', 'remarks'));
    }

    public function forward(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'remarks' => 'nullable|string|max:600',
            ]);
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canForward',  [$proforma, 'verify_and_forward']);
            if ($proforma->mini_sequence != "verified") {
                return response()->json(['message' => 'Verify first before forwarding.'], 422);
            }
            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'forwarded',
                'action_remark' => $request->remarks,
                'process_sequence' => $proforma->process_sequence
            ]);
            WorkflowHandler::forwardApplication($proforma);
            DB::commit();
            return response()->json(['message' => 'Proforma forwarded successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error forwarding proforma: ' . $e->getMessage()], 422);
        }
    }

    //Forwarding for multiple proformas in bulk
    public function bulkForward(Request $request)
    {
        $request->validate([
            'selected_proforma' => 'required|array|min:1',
            'selected_proforma.*' => 'exists:proforma,proforma_id',
            'remarks' => 'nullable|string|max:600',
        ]);

        DB::beginTransaction();

        try {
            $proformas = Proforma::whereIn('proforma_id', $request->selected_proforma)->get();
            foreach ($proformas as $proforma) {
                $this->authorize('canForward',  [$proforma, 'verify_and_forward']);

                //WorkflowHandler comes after LogService
                LogService::addProformaLog([
                    'proforma_id' => $proforma->proforma_id,
                    'action_by' => Auth::user()->user_id,
                    'action_name' => 'forwarded',
                    'action_remark' => $request->remarks,
                    'process_sequence' => $proforma->process_sequence
                ]);
                WorkflowHandler::forwardApplication($proforma);
            }
            DB::commit();
            return response()->json(['message' => 'Selected Proformas forwarded successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error forwarding proforma: ' . $e->getMessage()], 422);
        }
    }
}
