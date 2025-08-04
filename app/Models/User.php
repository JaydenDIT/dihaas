<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'mobile',
        'active_status',
        'password',
        'role_id',
        'dept_id',
        'status',
        'ministry_id',
        'post_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getRoleName()
    {
        if (!is_null($this->role_id)) {
            return RoleModel::getRoleName($this->role_id);
        }
        return "Role Not Defined";
    }

    public function scopeGetUsers($query)
    {

        $filterUser = [77, 999];

        return $query->leftJoin("roles_tbl as R", "R.role_id", "=", "users.role_id")
            ->where('users.role_id', '!=', 77)
            ->where('users.role_id', '!=', 999)
            ->get([
                'users.id',
                'users.name',
                'users.mobile',
                'users.email',
                'users.active_status',
                'R.role_name',
            ]);
    }
}
