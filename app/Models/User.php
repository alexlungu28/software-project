<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'net_id',
        'last_name',
        'first_name',
        'email',
        'org_defined_id',
        'affiliation',
    ];

    protected $table = 'users';
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function interventions()
    {
        return $this->belongsToMany(Intervention::class, 'intervention_user');
    }

    public function courseEditions()
    {
        return $this->belongsToMany(CourseEdition::class, 'course_edition_user');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user');
    }

    public function attendances()
    {
        return $this->belongsToMany(Attendance::class, 'attendance');
    }
}
