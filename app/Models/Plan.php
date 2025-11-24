<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = ['name', 'price', 'duration_in_months', 'description'];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
