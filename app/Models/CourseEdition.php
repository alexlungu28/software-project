<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseEdition extends Model
{
    use HasFactory;

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
     * Creates the rubrics relation.
     *
     * @return HasMany
     */
    public function rubrics()
    {
        return $this->hasMany(Rubric::class);
    }
}
