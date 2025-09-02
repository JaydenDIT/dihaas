<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\Proforma;
use App\Models\Task;
use App\Models\UoFileSubmission;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UoFileSubmissionController extends Controller
{
    public function index(Request $request, $tasks_id)
    {

        $task = Task::findOrFail($tasks_id);
        $departments = CmisApiService::apiFieldDepartments();
        $view = $request->input('view', '');
        return view('duties.uoFileSubmissionList', compact('departments', 'task', 'view'));
    }

    public function ajaxlist(Request $request, $tasks_id)
    {
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');
        $overallSeniorityIndex = Proforma::getOverallSeniorityList();

        switch ($application_status) {
            //proforma_status tells the current state of the application

            case 'pending': // currently pending on me
                $data = WorkflowHandler::proformaTaskCurrentData($task);        // collection where
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
                    ->whereIn('action_name', ['forwarded', 'rejected', 'reverted', 'completed'])
                    ->latest()
                    ->value('action_remark') ?? 'N/A';
            })
            ->addColumn('action', function ($row) use ($application_status) {
                // $data = urlencode(json_encode($row));
                $resp = "<div class='d-flex gap-2'>";
                if ($application_status === 'pending') {
                    $resp .= "<a href='" . route('duties.uo.filesubmission.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
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


    public function view($id)
    {
        $proforma = Proforma::findOrFail($id);
        $total_step = 4;
        $tasks = getPrevNextTasks($id);
        $this->authorize('canPerformOnProforma',  [$proforma, 'uo_file_submission']);
        return view('duties.uoFileSubmission', compact('proforma', 'total_step', 'tasks'));
    }


    public function submit(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'uo_file_submission']);
            $file   = $request->file('document_file');

            $doc = UoFileSubmission::where('proforma_id', $id)
                ->first();

            if ($doc && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }

            if (!$doc) {
                $doc = new UoFileSubmission();
                $doc->proforma_id = $id;
            }

            $path = $file->store('uploads/documents', 'public');
            $doc->file_name = $file->getClientOriginalName();
            $doc->file_path = $path;
            $doc->uploaded_by = Auth::user()->user_id;
            $doc->save();

            $proforma->mini_sequence = "file_uploaded";
            $proforma->save();
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'file_uploaded',
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
            $this->authorize('canForward',  [$proforma, 'uo_file_submission']);
            if ($proforma->mini_sequence != "file_uploaded") {
                return response()->json(['message' => 'Upload file before forwarding.'], 422);
            }
            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'forwarded', //compulsory always forwarded for forwarded
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
