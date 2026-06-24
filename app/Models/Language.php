<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Language extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'languages';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'code', 'direction', 'default', 'status'])
            ->useLogName('Language')
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                $activity->properties->get('attributes', []),
                [
                    'name' => $this->name,
                ]
            ),
        ]);
    }

}
