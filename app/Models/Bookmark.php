<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bookmark extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'internship_id',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bookmark) {
            if (!$bookmark->id) {
                $bookmark->id = (string) Str::uuid();
            }

            if (!$bookmark->created_at) {
                $bookmark->created_at = now();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }
}