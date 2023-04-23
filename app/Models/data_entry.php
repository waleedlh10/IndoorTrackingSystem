<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_entry extends Model
{
    use HasFactory;

    protected $fillable = ['MAC', 'sensor_id', 'pwr' ,'log_at'];

    const CREATED_AT = 'log_at';
    const UPDATED_AT = 'updated_at';

    // public function device(): BelongsTo
    // {
    //     return $this->belongsTo(Room::class);
    // }


    // public function sensor(): BelongsTo
    // {
    //     return $this->belongsTo(Room::class);
    // }


    
}
