<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    use HasFactory;

    public $table = 'factions';
    public $primaryKey = 'faction_id';

    public $fillable = [
        'faction_name',
        'faction_hex',
    ];
}
