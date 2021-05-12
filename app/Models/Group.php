<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function interventions() {
        return $this->hasMany(Intervention::class);
    }

    public function courseEdition() {
        return $this->belongsTo(CourseEdition::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'group_user');
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }
}
