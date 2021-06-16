<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'content',
        'grade',
        'course_edition_id',
    ];

    /**
     * Creates the interventions relation.
     *
     * @return HasMany
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    /**
     * Creates the course edition relation.
     *
     * @return BelongsTo
     */
    public function courseEdition()
    {
        return $this->belongsTo(CourseEdition::class);
    }

    /**
     * Creates the users relation.
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user');
    }

    /**
     * Returns the users with their grade from the group.
     *
     * @return BelongsToMany
     */
    public function usersWithGrade()
    {
        return $this->belongsToMany(Group::class, 'group_user')
                    ->withPivot('student_grade');
    }

    /**
     * Creates the notes relation.
     *
     * @return HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Creates the groupnotes relation.
     *
     * @return HasMany
     */
    public function groupnotes()
    {
        return $this->hasMany(NoteGroup::class);
    }

    /**
     * Creates the relationship between groups
     * and individual interventions.
     *
     * @return HasMany
     */
    public function groupIndividualInterventions()
    {
        return $this->hasMany(Intervention::class);
    }

    public function rubricData()
    {
        return $this->hasMany(RubricData::class);
    }
}
