<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rubric extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function delete()
    {
        $res = parent::delete();
        if ($res==true) {
            $this->rubricData()->delete();
            $this->rubricEntry()->delete();
        }
    }

    public function rubricData()
    {
        return $this->hasMany('App\Models\RubricData');
    }
    public function rubricEntry()
    {
        return $this->hasMany('App\Models\RubricEntry');
    }
}
