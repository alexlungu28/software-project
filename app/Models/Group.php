<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
     * Creates the notes relation.
     *
     * @return morphMany
     */
    public function notes()
    {
        return $this->morphMany('App\Models\Note', 'noteable');
    }

    public function rubricData()
    {
        return $this->hasMany(RubricData::class);
    }
}
