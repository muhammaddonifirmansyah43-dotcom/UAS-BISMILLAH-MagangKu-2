<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'action',
        'target_type',
        'target_id',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            if (!$log->id) {
                $log->id = (string) Str::uuid();
            }

            if (!$log->created_at) {
                $log->created_at = now();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}