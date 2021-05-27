<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rubric extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['rubric_name'];
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
     * Creates the rubric data relation.
     *
     * @return HasMany
     */
    public function rubricData()
    {
        return $this->hasMany('App\Models\RubricData');
    }

    /**
     * Creates the rubric entry relation.
     *
     * @return HasMany
     */
    public function rubricEntry()
    {
        return $this->hasMany('App\Models\RubricEntry');
    }
}
