<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Contact extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'contacts';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->useLogName('Contact')
            ->logOnlyDirty();

    }

    protected $fillable = [
        'name',
        'email',
        'user_id'
    ];

    //user contact relation
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
