<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubric extends Model
{
    use HasFactory;


    public function rubricData()
    {
        return $this->hasMany('App\Models\RubricData');
    }

    public function rubricEntry()
    {
        return $this->hasMany('App\Models\RubricEntry');
    }
}
