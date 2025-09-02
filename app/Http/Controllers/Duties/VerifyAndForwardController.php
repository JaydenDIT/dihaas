<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\Proforma;
use App\Models\Task;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VerifyAndForwardController extends Controller
{
    //
    public function index(Request $request, $tasks_id)
    {

        $task = Task::findOrFail($tasks_id);
        $departments = CmisApiService::apiFieldDepartments();
        $view = $request->input('view', '');
        return view('duties.verifyAndForwardList', compact('departments', 'task', 'view'));
    }



    public function ajaxlist(Request $request, $tasks_id)
    {
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');
        $overallSeniorityIndex = Proforma::getOverallSeniorityList();
        switch ($application_status) {
            //proforma_status tells the current state of the application
            case 'un-verified': // currently pending on me
                $data = WorkflowHandler::proformaTaskCurrentData($task)
                    ->where('mini_sequence', '!=', 'verified');   // collection whereNot
                break;
            case 'verified': // currently pending on me
                $data = WorkflowHandler::proformaTaskCurrentData($task)
                    ->where('mini_sequence', 'verified');        // collection where
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
            ->editColumn('applicant_dob', function ($row) {
                return date('d M, Y', strtotime($row->applicant_dob));
            })
            ->addColumn('remarks', function ($row) {
                return $row->proformaLogs()
                    ->whereIn('action_name', ['verified', 'forwarded', 'rejected', 'reverted', 'completed'])
                    ->latest()
                    ->value('action_remark') ?? 'N/A';
            })
            ->addColumn('action', function ($row) use ($application_status) {
                // $data = urlencode(json_encode($row));
                $resp = "<div class='d-flex gap-2'>";
                if ($application_status === 'un-verified' || $application_status === 'verified') {
                    $resp .= "<a href='" . route('duties.verify.form.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                } else {
                    $resp .= "<a href='" . route('duties.proforma.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                }
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



    public function viewVerifyAndForward($id)
    {
        $proforma = Proforma::findOrFail($id);
        $total_step = 4;
        $tasks = getPrevNextTasks($id);
        $this->authorize('canPerformOnProforma',  [$proforma, 'verify_and_forward']);
        return view('duties.verifyAndForward', compact('proforma', 'total_step', 'tasks'));
    }


    public function verify(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'remarks' => 'nullable|string|max:600',
            ]);
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'verify_and_forward']);
            if ($proforma->mini_sequence == "verified") {
                return response()->json(['message' => 'Proforma already verified.'], 422);
            }

            $proforma->mini_sequence = "verified";
            $proforma->save();
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'verified',
                'action_remark' => $request->remarks,
                'process_sequence' => $proforma->process_sequence
            ]);
            DB::commit();
            return response()->json(['message' => 'Proforma verified successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error verifying proforma: ' . $e->getMessage()], 422);
        }
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
}
