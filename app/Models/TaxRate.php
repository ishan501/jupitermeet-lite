<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class TaxRate extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'tax_rates';

    protected $casts = [
        'regions' => 'object'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'percentage', 'type', 'status', 'regions'])
            ->useLogName('Tax rate')
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


    public function scopeSearchName(Builder $query, $value)
    {
        return $query->where('name', 'like', '%' . $value . '%');
    }

    public function scopeOfType(Builder $query, $value)
    {
        return $query->where('type', '=', $value);
    }

    public function scopeOfRegion(Builder $query, $value)
    {
        $query->whereNull('regions')
            ->when($value, function ($query) use ($value) {
                $query->orWhere('regions', 'like', '%' . $value . '%');
            });

        return $query;
    }
}
