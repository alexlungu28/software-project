<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEdition extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Creates the course relation.
     *
     * @return BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Creates the groups relation.
     *
     * @return HasMany
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Creates the users relation.
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_edition_user');
    }

    /**
     * Returns the users with their role for a course edition.
     *
     * @return BelongsToMany
     */
    public function usersWithRole()
    {
        return $this->belongsToMany(User::class, 'course_edition_user')
                    ->withPivot('role');
    }

    /**
     * Returns the teaching assistents for a course edition.
     *
     * @return BelongsToMany
     */
    public function teachingAssistants()
    {
        return $this->belongsToMany(User::class, 'course_edition_user')->wherePivot('role', '=', 'TA');
    }

    /**
     * Creates the rubrics relation.
     *
     * @return HasMany
     */
    public function rubrics()
    {
        return $this->hasMany(Rubric::class);
    }
}
