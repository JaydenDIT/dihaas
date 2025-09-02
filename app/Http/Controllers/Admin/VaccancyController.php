<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPostVaccancyRequest;
use App\Models\PostVaccancy;
use App\Models\VaccancyPercentage;
use App\Services\CmisApiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccancyController extends Controller
{
    public function addPostVaccancy(Request $request)
    {
        $adminDepts = CmisApiService::apiAdminDepartments();
        usort($adminDepts, function ($a, $b) {
            return strcasecmp($a['adm_dept_desc'], $b['adm_dept_desc']);
        });
        $vaccancies = PostVaccancy::getVaccancy();

        return view('admin.vaccantPosts.add-post-vaccancy', compact('adminDepts', 'vaccancies'));
    }

    /**
     * Calculate vacancy distribution between Die-in-Harness and Direct Recruitment.
     *
     * Business Rule:
     * - At least 10% or 20% or (anything given in vaccancy_percentages table) of the total posts will be reserved for Die-in-Harness (DIA).
     * - The remaining posts will be assigned to Direct Recruitment (DR).
     * - Ceiling function is used to ensure at least 10% is allocated.
     *
     * @param int $total_posts  Total number of vacant posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateVacancyDistribution($total_posts)
    {
        //Get the DIA vaccancy percentage % from the vaccancy_percentages table
        $vaccancyPercentage = VaccancyPercentage::first();
        if (empty($vaccancyPercentage)) {
            return response()->json([
                'message' => 'There is no configuration set for vaccancy. First make it sure that the configuration is set properly.'
            ], 500);
        }

        // Calculate 10% of total posts and round it up to the nearest integer
        $dia = (int) ceil($total_posts * ($vaccancyPercentage->dia_percentage / 100));

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

    //Method to configure the percentage (%) of vaccancy
    public function configureVaccancy()
    {
        $vaccancyPercentage = VaccancyPercentage::first();
        return view('admin.vaccantPosts.configureVaccancy', compact('vaccancyPercentage'));
    }

    //Method to update the vaccancy configureation
    public function saveVaccancyConfigureation(Request $request)
    {

        $request->validate([
            'dia_percentage' => ['required', 'numeric'],
            'effective_date' => ['required', 'date']
        ]);

        $vaccancyPercentage = VaccancyPercentage::first();

        try {
            if (empty($vaccancyPercentage)) {
                //then create
                VaccancyPercentage::create([
                    'dia_percentage' => $request->input('dia_percentage'),
                    'effective_date' => $request->input('effective_date'),
                ]);
            } else {
                $vaccancyPercentage->old_dia_percentage = $vaccancyPercentage->dia_percentage;
                $vaccancyPercentage->old_effective_date = $vaccancyPercentage->effective_date;
                $vaccancyPercentage->dia_percentage = $request->input('dia_percentage');
                $vaccancyPercentage->effective_date = $request->input('effective_date');
                $vaccancyPercentage->save();
            }
            return response()->json([
                'message' => 'Configuration updated.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error has occured while updating vaccancy configuration',
                'server_error' => $e,
            ], 403);
        }
    }
}
