<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plugin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'plugins';

    protected $fillable = [
        'product_id',
        'product_name',
        'token',
    ];

    public function getCreatedAtCustomAttribute()
    {
        return convertToTimezone($this->created_at);
    }
}
