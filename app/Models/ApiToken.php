<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class ApiToken extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'api_tokens';
    protected $fillable = [
        'user_id',
        'name',
        'token',
        'last_accessed'
    ];

    protected function casts(): array
    {
        return [
            'last_accessed' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'token'])
            ->useLogName('API Tokens')
            ->logOnlyDirty();

    }
}
