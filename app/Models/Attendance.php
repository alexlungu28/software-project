<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'week', 'present','reason'];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
