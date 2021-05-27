<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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

    /**
     * Creates the interventions relation.
     *
     * @return BelongsToMany
     */
    public function interventions()
    {
        return $this->belongsToMany(Intervention::class, 'intervention_user');
    }

    /**
     * Creates the course editions relation.
     *
     * @return BelongsToMany
     */
    public function courseEditions()
    {
        return $this->belongsToMany(CourseEdition::class, 'course_edition_user');
    }

    /**
     * Creates the groups relation.
     *
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user');
    }

    /**
     * Creates the attendances relation.
     *
     * @return BelongsToMany
     */
    public function attendances()
    {
        return $this->belongsToMany(Attendance::class, 'attendance');
    }

    /**
     * Verifies whether the user has a student affiliation.
     *
     * @return bool
     */
    public function isStudent()
    {
        return $this->affiliation === 'student';
    }


    /**
     * Verifies whether the user has an employee affiliation.
     *
     * @return bool
     */
    public function isEmployee()
    {
        return $this->affiliation === 'employee';
    }
}
