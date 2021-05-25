<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEditionUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'course_edition_id',
        'role'
    ];

    protected $table = 'course_edition_user';

    public function isHeadTA()
    {
        return $this->role === 'Head TA';
    }
}
