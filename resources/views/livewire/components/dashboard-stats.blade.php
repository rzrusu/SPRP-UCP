<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

new class extends Component {

    // TODO: implement livewire polling for online players!!
    // needs to have setup the player count first.

    // just fake data for now, calculate real data later.
    // cache the numbers and only run a query every 12hrs or less.
    public int $online;
    public int $registered;
    public int $vehicles;
    public int $properties;
    public int $businesses;
    public array $data;

    private int $secondsUntilRefresh =  14400; // 4 hours

    public function mount(): void
    {
        $this->online = \App\Helpers\SampQueryAPI::getServerPlayerCount();

        if ($this->online == -1) {
            $this->online = 0;
        }

        $this->registered = $this->getRegisteredUsersCount();
        $this->vehicles = $this->getVehiclesCount();
        $this->properties = $this->getPropertiesCount();
        $this->businesses = $this->getBusinessesCount();

        $this->data = array(
            'online' => [
                'count' => $this->online,
                'label' => 'Online Players',
                'icon' => 'heroicon-s-users',
                'live' => true,
            ],
            'registered' => [
                'count' => $this->registered,
                'label' => 'Registered Players',
                'icon' => 'heroicon-s-user-group',
                'live' => false,
            ],
            'vehicles' => [
                'count' => $this->vehicles,
                'label' => 'Vehicles',
                'icon' => 'heroicon-s-truck',
                'live' => false,
            ],
            'properties' => [
                'count' => $this->properties,
                'label' => 'Properties',
                'icon' => 'heroicon-s-home-modern',
                'live' => false,
            ],
            'businesses' => [
                'count' => $this->businesses,
                'label' => 'Businesses',
                'icon' => 'heroicon-s-building-storefront',
                'live' => false,
            ],
        );
    }

    // TODO: add functions for each of the data points in the future. use this same exact code to get and cache the data.
    public function getRegisteredUsersCount()
    {
        return Cache::remember('accounts_count', $this->secondsUntilRefresh, function () {
            return DB::table('accounts')->count();
        });

    }

    public function getVehiclesCount()
    {
        return Cache::remember('vehicles_count', $this->secondsUntilRefresh, function () {
            return DB::table('vehicles')->count();
        });
    }

    public function getPropertiesCount()
    {
        return Cache::remember('properties_count', $this->secondsUntilRefresh, function () {
            return DB::table('properties')->where('property_type', '=', 0)->count();
        });
    }

    public function getBusinessesCount()
    {
        return Cache::remember('businesses_count', $this->secondsUntilRefresh, function () {
            return DB::table('properties')->where('property_type', '=', 1)->count();
        });
    }


}; ?>

<section class="space-y-2">

    <header>
        <h2 class="text-lg font-medium text-gray-400">
            {{ __('Server Statistics') }}
        </h2>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 gap-2">
        @foreach($data as $key => $value)
            <div
                class="w-full bg-gray-900 hover:stats_card_effect py-2 px-4 border-l-4 border-blue-500 inline-flex items-center justify-between rounded-r-lg transition @if($loop->last) col-span-full @endif">
                <div class="flex-col flex">
                    <div class="inline-flex items-center space-x-1">
                        <span class="text-gray-500 text-sm">
                            {{$value['label']}}
                        </span>
                        @if($value['live'])
                            <span class="animate-pulse bg-green-500 h-2 w-2 rounded-full inline-block"></span>
                        @endif
                    </div>
                    <span class="text-gray-100 text-3xl font-bold">{{number_format($value['count'])}}</span>
                </div>
                <div class="w-12 h-12 inline-flex items-center justify-center bg-[#586DC0]/15 rounded-lg">
                    @svg($value['icon'], 'w-8 h-8', ['style' => 'color: #586DC0'])
                </div>
            </div>
        @endforeach
    </div>


</section>
