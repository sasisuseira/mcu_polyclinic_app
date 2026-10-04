<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeveloperUpdateLog extends Model
{
    protected $fillable = [
        'title',
        'version',
        'category',
        'summary',
        'details',
        'released_at',
        'visibility',
        'author_name',
    ];

    protected $casts = [
        'released_at' => 'date',
    ];
}