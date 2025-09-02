<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use App\Http\Requests\UoFormFillupRequest;
use App\Models\Proforma;
use App\Models\Task;
use App\Models\UoGeneration;
use App\Models\User;
use App\Services\CmisApiService;
use App\Services\LogService;
use App\Services\WorkflowHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class UoFormFillUpController extends Controller
{
    public function index($tasks_id)
    {
        $task = Task::findOrFail($tasks_id);
        $departments = CmisApiService::apiFieldDepartments();
        return view('duties.uoFormFillupList', compact('departments', 'task'));
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
                    $resp .= "<a href='" . route('duties.uo.formfillup.view', $row->proforma_id) . "' class='btn btn-sm btn-primary view-btn'>View</a>";
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
        $this->authorize('canPerformOnProforma',  [$proforma, 'uo_formfillup']);

        $total_step = 4;
        $tasks = getPrevNextTasks($id);

        $adminDepartments = CmisApiService::apiAdminDepartments();

        $adminDepts = [];
        foreach ($adminDepartments as $dept) {
            $adminDepts[$dept['adm_dept_cd']] = $dept['adm_dept_desc'];
        }

        $departments = CmisApiService::apiFieldDepartments();

        //Retrieving Nodal Officers for Department of personels for signing authority
        $dpNodals = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('role_name', '=', 'DP Nodal');
            })->get();
        return view('duties.uoFormFillup', compact('proforma', 'total_step', 'tasks', 'departments', 'dpNodals', 'adminDepts'));
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

    public function forward(UoFormFillupRequest  $request, $id)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            $proforma = Proforma::findOrFail($id);

            // Prepare data
            $data = [
                'proforma_id'                       => $id,
                'post_option'                       => $validated['post_option'],

                // Applicant preferred option
                'applicant_prefered_post_id'        => $validated['applicant_prefered_post_id'] ?? null,
                'applicant_prefered_post_desc'      => $validated['applicant_prefered_post_desc'] ?? null,
                'applicant_prefered_group_code'     => $validated['applicant_prefered_group_code'] ?? null,
                'applicant_prefered_dept_cd'        => $validated['applicant_prefered_dept_cd'] ?? null,
                'applicant_prefered_dept_desc'      => $validated['applicant_prefered_dept_desc'] ?? null,
                'applicant_prefered_adm_dept_cd'    => $validated['applicant_prefered_adm_dept_cd'] ?? null,
                'applicant_prefered_adm_dept_desc'  => $validated['applicant_prefered_adm_dept_desc'] ?? null,

                // Dept preferred options
                'department_prefered_post_id'       => $validated['department_prefered_post_id'] ?? null,
                'department_prefered_post_desc'     => $validated['department_prefered_post_desc'] ?? null,
                'department_prefered_group_code'    => $validated['department_prefered_group_code'] ?? null,
                'department_prefered_dept_cd'       => $validated['department_prefered_dept_cd'] ?? null,
                'department_prefered_dept_desc'     => $validated['department_prefered_dept_desc'] ?? null,
                'department_prefered_adm_dept_desc' => $validated['department_prefered_adm_dept_desc'] ?? null,
                'department_prefered_adm_dept_cd'   => $validated['department_prefered_adm_dept_cd'] ?? null,

                // Always required
                'signing_authority'                => $validated['signing_authority'],
                'generated_by'                     => auth()->id(),
            ];

            //$preference = ($validated['post_option'] == "applicant-prefered") ? 'applicant_prefered' : 'department_prefered';
            $preference = str_replace('-', '_', $validated['post_option']);

            $params = [];
            $params['proforma_id'] = $data['proforma_id'];
            $params['alloted_adm_dept_cd'] = $data[$preference . '_adm_dept_cd'];
            $params['alloted_adm_dept_desc'] = $data[$preference . '_adm_dept_desc'];
            $params['alloted_field_dept_cd'] = $data[$preference . '_dept_cd'];
            $params['alloted_field_dept_desc'] = $data[$preference . '_dept_desc'];
            $params['alloted_dsg_srno'] = $data[$preference . '_post_id'];
            $params['alloted_dsg_desc'] = $data[$preference . '_post_desc'];
            $params['alloted_group_code'] = $data[$preference . '_group_code'];

            $params['signing_authority'] = $data['signing_authority'];
            $params['generated_by'] = auth()->id();
            $params['generated_on'] = Carbon::now();
            $params['is_applicant_choice_post'] = ($validated['post_option'] == "applicant-prefered");

            UoGeneration::updateOrCreate([
                'proforma_id' => $data['proforma_id']
            ], $params);

            //WorkflowHandler comes after LogService
            LogService::addProformaLog([
                'proforma_id' => $proforma->proforma_id,
                'action_by' => Auth::user()->user_id,
                'action_name' => 'forwarded',
                'action_remark' => $request->remarks ?? '',
                'process_sequence' => $proforma->process_sequence
            ]);
            WorkflowHandler::forwardApplication($proforma);
            DB::commit();
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Proforma forwarded successfully.'], 200);
            }
            return redirect()->back()->with('success', 'Proforma forwarded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Error forwarding proforma: ' . $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', 'Error forwarding proforma: ' . $e->getMessage());
        }
    }
}
