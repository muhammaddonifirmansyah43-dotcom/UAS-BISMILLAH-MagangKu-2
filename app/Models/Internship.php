<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Internship extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'created_by',
        'title',
        'type',
        'description',
        'requirements',
        'location',
        'registration_url',
        'status',
        'open_date',
        'close_date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($internship) {
            if (!$internship->id) {
                $internship->id = (string) Str::uuid();
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}