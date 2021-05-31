<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RubricData extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];


    /**
     * Creates the rubric relation.
     *
     * @return BelongsTo
     */
    public function rubric()
    {
        return $this->belongsTo(Rubric::class, 'rubric_id');
    }

    /**
     * Creates the group relation.
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
