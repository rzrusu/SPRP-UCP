<?php

namespace App\Models\Character;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    public $table = 'characters';
    public $fillable = [

        'player_name',
        'player_ip',
        'player_skinid',

        'player_attribute_sex',
        'player_attribute_age',
        'player_attribute_race',
        'player_attribute_eyes',
        'player_attribute_hair',
        'player_attribute_body',
        'player_attribute_height',
        'player_attribute_desc',

        'player_registerdate',

    ];

    public $casts = [
        'player_registerdate' => 'timestamp',
    ];

    public $timestamps = false;
    protected $primaryKey = 'player_id';

    public function getCleanName(): string
    {
        return str_replace('_', ' ', $this->player_name);
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id', 'account_id');
    }
}
