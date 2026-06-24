<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addons';

    protected $fillable = [
        'addon_id',
        'addon_name',
        'license_code',
        'status',
    ];

    public function getCreatedAtCustomAttribute()
    {
        return convertToTimezone($this->created_at);
    }
}
