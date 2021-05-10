<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function interventions() {
        return $this->hasMany(Intervention::class, 'group_name', 'group_name');
    }

    public function courseEdition() {
        return $this->belongsTo(CourseEdition::class, 'edition_id');
    }
}
