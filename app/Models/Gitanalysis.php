<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gitanalysis extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'week_number', 'names', 'emails', 'blame', 'timeline'];

    /**
     * Returns the group model that is linked to this gitanalysis.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
