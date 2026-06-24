<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class AddonSetting extends Model
{
    use HasFactory;

    protected $table = 'addon_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    protected array $encryptedKeys = [
        'TRANSCRIPTION_KEY',
        'SUMMARY_KEY',
    ];

    protected static function booted()
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget('addon_settings');
        });

        static::deleted(function ($setting) {
            Cache::forget('addon_settings');
        });
    }

    //setter function
    public function setValueAttribute($value)
    {
        // if key is set and it's in encrypted list, encrypt the value
        if (!empty($this->attributes['key']) && in_array($this->attributes['key'], $this->encryptedKeys, true)) {
            $this->attributes['value'] = $value ? Crypt::encryptString($value) : null;
            return;
        }

        $this->attributes['value'] = $value;
    }

    //getter function
    public function getValueAttribute($value)
    {
        if (!empty($this->attributes['key']) && in_array($this->attributes['key'], $this->encryptedKeys, true)) {
            try {
                return $value ? Crypt::decryptString($value) : null;
            } catch (DecryptException $e) {
                // Backward compatible: value is plaintext (old data)
                return $value;
            }
        }

        return $value;
    }
}