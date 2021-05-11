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

    public function users() {
        return $this->belongsToMany(User::class, 'course_edition_user', 'edition_id', 'user_id');
    }

    public function usersRoles() {
        return $this->belongsToMany(User::class, 'course_edition_user', 'edition_id', 'user_id')
                    ->using(UserRole::class);
    }
}
