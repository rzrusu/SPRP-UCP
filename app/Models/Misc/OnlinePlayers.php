<?php

namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon;

class OnlinePlayers extends Model
{
    use HasFactory;
    public $fillable = [
        'players'
    ];

    public static function processPlayerCount($playerCount): void
    {
        $today = now()->format('Y-m-d');
        $dailyPeak = OnlinePlayers::firstOrCreate(['created_at' => $today], ['players' => 0]);

        if ($playerCount > $dailyPeak->players) {
            $dailyPeak->players = $playerCount;
            $dailyPeak->save();
        }
    }

    /*
    public function getCreatedAtAttribute($date)
    {
        return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }*/
}
