<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class PostVaccancy
 *
 * This model represents the `post_vaccancies` table and provides methods
 * to retrieve and update vacant post details for DIA (Direct Intake Authority) 
 * and DR (Direct Recruitment).
 */
class PostVaccancy extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "post_vaccancies";

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "vaccancy_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'adm_dept_cd',
        'adm_dept_name',
        'field_dept_cd',
        'field_dept_name',
        'dsg_srno',
        'dsg_name',
        'no_of_posts_dia',
        'no_of_posts_dr',
        'created_by',
    ];

    /**
     * Scope a query to get grouped vacancy details.
     *
     * This query fetches the vacancies by department and designation,
     * and sums up the DIA and DR posts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Support\Collection
     */
    public function scopeGetVaccancy($query)
    {
        return $query->select(
            'adm_dept_cd',
            'adm_dept_name',
            'field_dept_cd',
            'field_dept_name',
            'dsg_srno',
            'dsg_name',
            DB::raw('sum(no_of_posts_dia) as dia_vacc_posts'),
            DB::raw('sum(no_of_posts_dr) as dr_vacc_posts'),
        )->groupBy(
            'adm_dept_cd',
            'adm_dept_name',
            'field_dept_cd',
            'field_dept_name',
            'dsg_srno',
            'dsg_name',
        )->orderBy('adm_dept_name')->get();
    }

    /**
     * Deduct one DIA vacant post for a given department and designation.
     *
     * Logic:
     * 1. Find a record that matches the given `field_dept_cd` and `dsg_srno`.
     * 2. Ensure that `no_of_posts_dia` is greater than 0 (i.e., vacancy exists).
     * 3. If found, decrement the value of `no_of_posts_dia` by 1.
     * 4. If not found, return false (no vacancy available).
     *
     * @param int|string $fieldDeptCd  The field department code.
     * @param int|string $dsgSrno      The designation serial number.
     * @return bool Returns true if a post was deducted, false otherwise.
     * 
     * Note: dia means Die-In-Harness
     */
    public static function deductDiaPost($fieldDeptCd, $dsgSrno)
    {
        // Find the first record with non-zero dia posts
        $vaccancy = self::where('field_dept_cd', $fieldDeptCd)
            ->where('dsg_srno', $dsgSrno)
            ->where('no_of_posts_dia', '>', 0)
            ->first();

        if ($vaccancy) {
            $vaccancy->decrement('no_of_posts_dia', 1);
            return true; // successfully deducted
        }

        return false; // no vacant post available
    }

    /**
     * Method to get the total number of vaccant posts for a particular post (designation) in a 
     * department
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Support\Collection
     */
    public function scopeGetDepartmentPostVaccancy($query, $fieldDeptCd, $dsgSrno): int
    {
        return $query->where('field_dept_cd', $fieldDeptCd)->where('dsg_srno', $dsgSrno)->sum('no_of_posts_dia');
    }
}
