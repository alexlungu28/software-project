<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Creates the course editions relation.
     *
     * @return HasMany
     */
    public function courseEditions()
    {
        return $this->hasMany(CourseEdition::class);
    }
}
