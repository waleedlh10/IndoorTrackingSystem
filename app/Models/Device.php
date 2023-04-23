<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    use HasFactory;
    protected $fillable = ['MAC', 'Name', 'Status', 'Position_x', 'Position_y', 'room_id'];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function data_entries(): HasMany
    {
        return $this->hasMany(data_entry::class);
    }
}
