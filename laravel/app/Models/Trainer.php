<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['first_name', 'last_name', 'email', 'phone', 'specialization'])]
class Trainer extends Model
{
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function trainingPrograms(): HasMany
    {
        return $this->hasMany(TrainingProgram::class);
    }
}
