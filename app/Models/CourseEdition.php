<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEdition extends Model
{
    use HasFactory;

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function groups() {
        return $this->hasMany(Group::class, 'edition_id');
    }
}
