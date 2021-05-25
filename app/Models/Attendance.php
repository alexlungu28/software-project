<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'week',
        'present',
        'reason'];

    /**
     * Creates the user relation.
     *
     * @return BelongsToMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
