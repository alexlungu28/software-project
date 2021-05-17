<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}