<?php

namespace App\Services;

use App\Models\DepartmentModel;
use App\Traits\WorkflowHandler;
use Yajra\DataTables\Facades\DataTables;

class VerifyNewApplication
{
    use WorkflowHandler;

    public static function index($task)
    {
        $departments = DepartmentModel::orderBy('dept_id')->get()->unique('dept_id');
        return view('duties.verify_new_application', compact('departments', 'task'));
    }

    public static function ajaxlist($task)
    {
        $status_label = config('status_label');
        $data = self::proformaTaskData($task);



        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('deceased_doe', function ($row) {
                return date('d M, Y', strtotime($row->deceased_doe));
            })
            ->editColumn('appl_date', function ($row) {
                return date('d M, Y', strtotime($row->appl_date));
            })
            ->editColumn('applicant_dob', function ($row) {
                return date('d M, Y', strtotime($row->applicant_dob));
            })
            ->addColumn('status_label', function ($row) use ($status_label) {
                return $status_label[$row->status]['status'] ?? "--";
            })
            ->addColumn('action', function ($row) {
                $data = urlencode(json_encode($row));
                return "<div class='d-flex gap-2'>
                            <button class='btn btn-sm btn-primary view-btn' data-row='{$data}'>View</button>
                         <!--   <button class='btn btn-sm btn-primary forward-btn' data-row='{$data}'>Forward</button>
                            <button class='btn btn-sm btn-danger revert-btn' data-row='{$data}'>Revert</button> -->
                        </div>";
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
