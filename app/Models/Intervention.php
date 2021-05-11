<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    public function group() {
        return $this->belongsTo(Group::class, 'group_name', 'group_name');
    }

    public function notes() {
        return $this->hasMany(Note::class, 'foreign_id', 'intervention_id');
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
