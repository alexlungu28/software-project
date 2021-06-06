<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Intervention extends Model
{
    use HasFactory;

    protected $table = 'interventions_individual';

    /**
     * Creates the groups relation.
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Creates the users relation.
     *
     * @return BelongsToMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
