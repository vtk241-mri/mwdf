<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['trainer_id', 'name', 'description', 'duration_weeks', 'price'])]
class TrainingProgram extends Model
{
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * @return HasMany
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }
}
