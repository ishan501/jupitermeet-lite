<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCode extends Model
{
    use HasFactory;

    protected $table = 'user_codes';
    protected $fillable = [
        'user_id',
        'code',
    ];
}
