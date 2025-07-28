<?php

namespace App\Http\Controllers;

use App\Models\CmisVacancyHistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\CmisVacancyModel;
use Carbon\Carbon;

class CmisVacancyController extends Controller
{
    public function index()
    {


        /////////////////////////////////////API FROM CMIS/////////////////////

        $vacancyList = CmisVacancyModel::get()->toArray();
       
        if (count($vacancyList) > 0) {
            foreach ($vacancyList as $vacancy) {
                CmisVacancyHistoryModel::create([
                    'field_dept_code' => $vacancy['field_dept_code'],
                    'department' => $vacancy['department'],
                    'dsg_srno' => $vacancy['dsg_srno'],
                    'designation' => $vacancy['designation'],
                    'post_count' => $vacancy['post_count'],
                    'emp_cnt' => $vacancy['emp_cnt'],
                    'vacancy' => $vacancy['vacancy'],
                    'pull_year' =>  $vacancy['pull_year'], // Set the year field to the current year     
                ]);
            }

           
            $vacancyList=CmisVacancyModel::where('status', null)->delete();
            
            $vacanciesCMIS = array();
            $notfound = "";

            $response = Http::get('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-post-vacancy?', [

                'token' => "b000e921eeb20a0d395e341dfcd6117a",
            ]);
            $vacanciesCMIS = json_decode($response->getBody(), true);
            $getDate = Carbon::now()->format('Y-m-d');

            foreach ($vacanciesCMIS as $vacancy) {
                CmisVacancyModel::create([
                    'field_dept_code' => $vacancy['field_dept_code'],
                    'department' => $vacancy['department'],
                    'dsg_srno' => $vacancy['dsg_srno'],
                    'designation' => $vacancy['designation'],
                    'post_count' => $vacancy['post_count'],
                    'emp_cnt' => $vacancy['emp_cnt'],
                    'vacancy' => $vacancy['vacancy'],
                    'pull_year' =>  $getDate, // Set the year field to the current year          


                ]);
            }
           
            return back()->with(compact('vacanciesCMIS'));
        }

        if (count($vacancyList) == 0) {

            $vacanciesCMIS = array();
            $notfound = "";

            $response = Http::get('http://manipurtemp02.nic.in/cmis_api/public/api/get-all-post-vacancy?', [

                'token' => "b000e921eeb20a0d395e341dfcd6117a",
            ]);
            $vacanciesCMIS = json_decode($response->getBody(), true);
            $getDate = Carbon::now()->format('Y-m-d');

            foreach ($vacanciesCMIS as $vacancy) {
                CmisVacancyModel::create([
                    'field_dept_code' => $vacancy['field_dept_code'],
                    'department' => $vacancy['department'],
                    'dsg_srno' => $vacancy['dsg_srno'],
                    'designation' => $vacancy['designation'],
                    'post_count' => $vacancy['post_count'],
                    'emp_cnt' => $vacancy['emp_cnt'],
                    'vacancy' => $vacancy['vacancy'],
                    'pull_year' =>  $getDate, // Set the year field to the current year          


                ]);
            }

            return back()->with(compact('vacanciesCMIS'));
        }
    }
}
