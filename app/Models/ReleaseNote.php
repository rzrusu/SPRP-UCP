<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseNote extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'author',
        'slug',
        'description',
        'content',
        'type',
        'inline',
        'image',
        'added',
        'changed',
        'fixed',
        'removed',
        'status',
    ];
}
