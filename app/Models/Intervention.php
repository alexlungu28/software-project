<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'intervention_user');
    }
}
