<?php

namespace App\Console\Commands;

use App\Helpers\SampQueryAPI;
use App\Models\Misc\OnlinePlayers;
use Illuminate\Console\Command;

class UpdateDailyPeak extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'players:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the player count from the API and update the daily peak if higher than the last peak.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $playerCount = SampQueryAPI::getServerPlayerCount();
        if ($playerCount == -1) {
            $this->info('Failed to fetch player count from the API. Server may be down. Value not updated.');
        } else {
            // Process and store player count
            OnlinePlayers::processPlayerCount($playerCount);
            $this->info('Player count updated successfully.');
        }
    }
}
