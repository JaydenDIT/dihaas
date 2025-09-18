<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPostVaccancyRequest;
use App\Http\Requests\UpdatePostVaccancyRequest;
use App\Models\PostVaccancy;
use App\Models\VaccancyPercentage;
use App\Services\CmisApiService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccancyController extends Controller
{
    /**
     * Only for super-admin
     */
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
     * For departmental user
     * 
     * Post vacccancy data entry only for a department
     */

    public function addPostVaccancyByDept(Request $request)
    {
        //Current department
        $user = Auth::user();
        $department = CmisApiService::apiFieldDepartments($user->field_dept_cd);

        //Retrieve current post vaccancies for the current department
        $vaccancies = PostVaccancy::getVaccancyEntries($user->field_dept_cd);
        $vaccancyPercentage = VaccancyPercentage::first();
        $posts = CmisApiService::apiAllPostUnderDepartment($user->field_dept_cd);
        //arranging in alphabetical order of designation
        usort($posts, function ($a, $b) {
            return strcasecmp($a['dsg_desc'], $b['dsg_desc']);
        });

        return view('admin.vaccantPosts.add-dept-post-vaccancy', compact('department', 'vaccancies', 'vaccancyPercentage', 'posts'));
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

    /**
     * To update post vaccancies
     */
    public function updatePostVaccancies(UpdatePostVaccancyRequest $request)
    {
        $data = $request->validated();
        $data['updated_by'] = Auth::id();
        try {
            PostVaccancy::where('vaccancy_id', $data['vaccancy_id'])->update($data);

            return response()->json([
                'message' => 'You have successfully updated vaccant post.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * This endpoint fetches the post vacancies of different posts/designation in a department,
     * Business Logic: 
     * If $fieldDeptCd is not zero (0), then the query will retrieve only the overall vaccancy record for
     * the particular department, otherwise vaccancies for all departments.
     * This will give only the sums up values the DIA and DR posts
     * 
     * @param integer $fieldDeptCd, this is the department code
     * @return json data
     */
    public function getVaccancyData($fieldDeptCd = 0)
    {
        $vaccancies = PostVaccancy::getVaccancy($fieldDeptCd);
        return response()->json([
            'vaccancies' => $vaccancies
        ]);
    }

    /**
     * Method to delete a post vaccancy
     */
    public function deletePostVaccancy($vaccancyId)
    {
        $postVaccancy = PostVaccancy::find($vaccancyId);
        if (empty($postVaccancy)) {
            return response()->json([
                'message' => 'Invalid request'
            ], 404);
        }

        try {
            $postVaccancy->delete();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error occurs while trying to delete post vaccancy.'
            ], 500);
        }
        return response()->json([
            'message' => 'You have successfully deleted the vaccancy entry.'
        ], 200);
    }


    //Method to configure the percentage (%) of vaccancy
    public function configureVaccancy()
    {
        $vaccancyPercentage = VaccancyPercentage::first();
        return view('admin.vaccantPosts.configureVaccancy', compact('vaccancyPercentage'));
    }

    //Method to update the vaccancy configuration
    public function saveVaccancyConfiguration(Request $request)
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

    // End point to get the number of vaccancies (counts) for a particular post in a department
    public function getVaccancyCount(Request $request, $fieldDeptCd, $dsgSrno)
    {
        $count = PostVaccancy::getDepartmentPostVaccancy($fieldDeptCd, $dsgSrno);
        return response()->json(compact('count'));
    }

    /**
     *   Endpoint to retrieve all the vaccancy data entries for a particular department
     *   (no sums up values of the DIA and DR posts)
     *   But every single entry will be retrieved (Can be used for log activities)
     */

    public function getPostVaccancyEntries($fieldDeptCd = 0)
    {
        //Retrieve current post vaccancies for a department
        $vaccancies = PostVaccancy::getVaccancyEntries($fieldDeptCd);
        if ($vaccancies->count() == 0) {
            return response()->json([
                'message' => 'No data entries for vaccancy of the post for the department'
            ], 404);
        }

        return response()->json(compact('vaccancies'), 200);
    }
}
