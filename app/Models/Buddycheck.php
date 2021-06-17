<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buddycheck extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'group_id', 'week', 'data'];
}
