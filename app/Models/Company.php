<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'logo_url',
        'website',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (!$company->id) {
                $company->id = (string) Str::uuid();
            }
        });
    }

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }
}