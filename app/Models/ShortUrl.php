<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'created_by',
        'original_url',
        'short_code'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
