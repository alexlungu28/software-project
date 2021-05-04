<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubricData extends Model
{
    use HasFactory;

    public function rubric(){

        return $this->belongsTo('App\Models\Rubric','rubric_id');

    }
}
