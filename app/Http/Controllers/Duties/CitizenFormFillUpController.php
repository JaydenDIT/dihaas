<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Models\FamilyDetail;
use App\Models\Proforma;
use App\Models\Task;
use App\Services\CmisApiService;
use App\Services\DocumentRequirementService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CitizenFormFillUpController extends Controller
{

    public function index($tasks_id)
    {

        $task = Task::findOrFail($tasks_id);
        $departments = CmisApiService::apiFieldDepartments();
        return view('duties.listFormFillUp', compact('departments', 'task'));
    }



    public function ajaxlist(Request $request, $tasks_id)
    {
        $task = Task::findOrFail($tasks_id);

        $application_status = $request->input('application_status');

        switch ($application_status) {
            //proforma_status tells the current state of the application
            case 'pending': //currently pending on me
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
            ->editColumn('applicant_dob', function ($row) {
                return date('d M, Y', strtotime($row->applicant_dob));
            })
            ->addColumn('action', function ($row) use ($application_status) {
                // $data = urlencode(json_encode($row));
                $resp = "<div class='d-flex gap-2'>";

                if ($application_status === 'pending') {
                    $resp .= "<a href='" . route('duties.proforma.edit', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>edit</a>";
                } else {
                    $resp .= "<a href='" . route('duties.proforma.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
                }

                $resp .= "</div>";
                return $resp;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }





    //Step 2 - Complete Family Detail
    public function completeFamilyDetail($id)
    {
        try {
            DB::beginTransaction();
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
            $familyMembers = FamilyDetail::where('proforma_id', $proforma->proforma_id)->count();
            if ($familyMembers == 0) {
                return response()->json(['message' => 'Add at least one family member'], 422);
            }
            $data['form_fillup_step'] = 'step2-completed'; //default status
            $proforma->update($data);
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'Family Detail save draft-step2',
                'action_remark' => 'Step 2 completed',
            ]);
            DB::commit();
            return response()->json(['message' => 'Successfully save', 'proforma_id' => $proforma->proforma_id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Saving failed', 'error' => $e->getMessage()], 422);
        }
    }


    //step 3 completed

    public function completeUploadDocument($id)
    {
        try {
            DB::beginTransaction();
            $proforma = Proforma::findOrFail($id);
            $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
            $documents = DocumentRequirementService::getDocumentsWithRequirement($proforma);
            $requiredDocumentsLeft = $documents
                ->where('required', true)
                ->filter(function ($doc) {
                    return empty($doc->uploaded_file);
                })
                ->count();

            if ($requiredDocumentsLeft > 0) {
                return response()->json(['message' => 'Upload all required documents'], 422);
            }
            $data['form_fillup_step'] = 'step3-completed'; //default status
            $proforma->update($data);

            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'Proforma document save draft-step3',
                'action_remark' => 'Step 3 completed',
            ]);
            DB::commit();
            return response()->json(['message' => 'Successfully save', 'proforma_id' => $proforma->proforma_id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Saving failed', 'error' => $e->getMessage()], 422);
        }
    }

    //final form submission
    public function proformaFormSubmit($id)
    {
        try {
            DB::beginTransaction();
            // Find the proforma
            $proforma = Proforma::where('proforma_status', 'step3-completed')->where('proforma_id', $id)->first();
            $this->authorize('canPerformOnProforma',  [$proforma, 'client_form_submission']);
            $proforma->form_fillup_step = 'submitted';
            $proforma->save();

            WorkflowHandler::forwardApplication($proforma); //set the sequence to next 

            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => 1,
                'action_name' => 'forwarded',
                'action_remark' => 'Form Submit by Applicant ' . $proforma->applicant_name,
            ]);
            DB::commit();
            return response()->json(['message' => 'Proforma updated successfully', 'proforma_id' => $proforma->proforma_id], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Proforma update failed', 'error' => $e->getMessage()], 422);
        }
    }
}
