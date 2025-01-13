<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'author',
        'force_highlight',
        'expires_at',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function archive(): void
    {
        $this->expires_at = now();
        $this->save();
    }
}
