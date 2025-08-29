<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPostVaccancyRequest;
use App\Models\PostVaccancy;
use App\Services\CmisApiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccancyController extends Controller
{
    public function addPostVaccancy(Request $request)
    {
        $adminDepts = CmisApiService::apiAdminDepartments();
        $departments = CmisApiService::apiFieldDepartments();

        usort($adminDepts, function ($a, $b) {
            return strcasecmp($a['adm_dept_desc'], $b['adm_dept_desc']);
        });



        $vaccancies = PostVaccancy::getVaccancy();


        //dd($vaccancies, $mapAdminDept, $mapDept);
        return view('admin.vaccantPosts.add-post-vaccancy', compact('adminDepts', 'vaccancies'));
    }

    /**
     * Calculate vacancy distribution between Die-in-Harness and Direct Recruitment.
     *
     * Business Rule:
     * - At least 10% of the total posts will be reserved for Die-in-Harness (DIA).
     * - The remaining posts will be assigned to Direct Recruitment (DR).
     * - Ceiling function is used to ensure at least 10% is allocated.
     *
     * @param int $total_posts  Total number of vacant posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateVacancyDistribution($total_posts)
    {
        // Calculate 10% of total posts and round it up to the nearest integer
        $dia = (int) ceil($total_posts * 0.10);

        // Remaining posts go to Direct Recruitment
        $dr = $total_posts - $dia;

        // Return distribution as JSON response
        return response()->json([
            'dia' => $dia,
            'dr'  => $dr,
        ]);
    }

    public function storePostVaccancies(AddPostVaccancyRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();
        try {
            PostVaccancy::create($data);

            return response()->json([
                'message' => 'You have successfully added vaccant post.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Method to load vaccancy data
    public function getVaccancyData()
    {
        $vaccancies = PostVaccancy::getVaccancy();
        return response()->json([
            'vaccancies' => $vaccancies
        ]);
    }
}
