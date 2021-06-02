<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rubric extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['rubric_name', 'week'];
    protected $dates = ['deleted_at'];

    /**
     * Deletes the rubric data and entry from the database.
     *
     * @return void
     */
    public function delete()
    {
        $res = parent::delete();
        if ($res==true) {
            $this->rubricData()->delete();
            $this->rubricEntry()->delete();
        }
    }

    /**
     * Creates the course edition relation.
     *
     * @return BelongsTo
     */
    public function courseEdition()
    {
        return $this->belongsTo(Rubric::class, 'course_edition_id');
    }

    /**
     * Creates the rubric data relation.
     *
     * @return HasMany
     */
    public function rubricData()
    {
        return $this->hasMany(RubricData::class);
    }

    /**
     * Creates the rubric entry relation.
     *
     * @return HasMany
     */
    public function rubricEntry()
    {
        return $this->hasMany(RubricEntry::class);
    }

    /**
     * Returns all deleted entries coupled to this rubric
     *
     * @return HasMany
     */
    public function deletedEntries()
    {
        return $this->hasMany(RubricEntry::class)->onlyTrashed();
    }
}
