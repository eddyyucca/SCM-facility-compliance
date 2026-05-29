<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaundryMess extends Model
{
    protected $fillable = ['area_id', 'name', 'description'];

    public function area(): BelongsTo
    {
        return $this->belongsTo(LaundryArea::class, 'area_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LaundryTransaction::class, 'mess_id');
    }
}
