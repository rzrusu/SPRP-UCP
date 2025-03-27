<!DOCTYPE HTML>
<html>
<head>
    <title>Singleplayer Roleplay Map</title>
    <!-- Disallow users to scale this page -->
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{$head ?? ''}}

    <style>
        /* Allow the canvas to use the full height and have no margins */
        html, body, #map-canvas {
            height: 100%;
            margin: 0
        }

        /* Define the keyframes for the opacity animation */
        @keyframes fade {
            0%, 100% {
                opacity: 0.35;
            }
            50% {
                opacity: 0;
            }
        }

        /* Apply the animation to the polygon */
        .animate-opacity {
            animation: fade 2s infinite;
        }

        a:link {
            color: black;
        }

        a:visited {
            color: black;
        }

        a:hover {
            color: black;
        }

        a:active {
            color: black;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

</head>
<body>

<div id="floating-panel" x-data="{ houses: true, biz: true, turfs: true, poi:true }"
     class="absolute top-16 md:top-2 md:ml-4 space-x-2 space-y-2 md:space-y-0 md:max-h-[42px] max-h-auto inline-flex flex-wrap md:flex-nowrap items-center m-4 md:m-0 w-fit md:left-20 bg-container-primary rounded-lg border-stroke-primary p-2 z-50">
    <img src="/assets/logos/logo_small.png" class="w-8 h-8 inline-block">

    <div x-show="houses">
        <input onclick="toggleHouses();" @click="houses = ! houses" type=button value="Show Houses"
               class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg hover:cursor-pointer hover:bg-gray-600">
    </div>
    <div x-show="!houses" x-cloak>
        <input onclick="toggleHouses();" @click="houses = ! houses" type=button value="Show Houses"
               class="bg-gray-800 line-through text-sm text-gray-500 px-2 py-1 rounded-lg hover:cursor-pointer hover:bg-gray-600">
    </div>

    <div x-show="biz">
        <input onclick="toggleBiz();" @click="biz = ! biz" type=button value="Show Businesses"
               class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg hover:cursor-pointer hover:bg-gray-600">
    </div>
    <div x-show="!biz" x-cloak>
        <input onclick="toggleBiz();" @click="biz = ! biz" type=button value="Show Businesses"
               class="bg-gray-800 line-through text-sm text-gray-500 px-2 py-1 rounded-lg hover:cursor-pointer hover:bg-gray-600">
    </div>

    <div x-show="turfs">
        <input onclick="toggleTurfs();" @click="turfs = ! turfs" type=button value="Show Turfs"
               class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg hover:cursor-pointer hover:bg-gray-600">
    </div>
    <div x-show="!turfs" x-cloak>
        <input onclick="toggleTurfs();" @click="turfs = ! turfs" type=button value="Show Turfs"
               class="bg-gray-800 line-through text-sm text-gray-500 px-2 py-1 rounded-lg hover:cursor-pointer hover:bg-gray-600">
    </div>

    <div x-show="poi">
        <input onclick="togglePoi();" @click="poi = ! poi" type=button value="Show Points of Interest"
               class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg hover:cursor-pointer hover:bg-gray-600">
    </div>
    <div x-show="!poi" x-cloak>
        <input onclick="togglePoi();" @click="poi = ! poi" type=button value="Show Points of Interest"
               class="bg-gray-800 line-through text-sm text-gray-500 px-2 py-1 rounded-lg hover:cursor-pointer hover:bg-gray-600">
    </div>
</div>

<div id="legend" x-data="legendComponent()"
     class="absolute bottom-2 min-h-12 inline-flex flex-wrap items-center m-4 w-fit md:left-20 bg-container-primary rounded-lg border-stroke-primary p-2 z-50">
    <div class="inline-flex items-center space-x-2 md:mr-2">
        <button @click="isLegendVisible = !isLegendVisible">
            <x-heroicon-m-eye class="h-4 w-4 inline-block text-gray-500"/>
        </button>
        <p class="text-sm text-gray-200 rounded-lg">Legend</p>
    </div>
    <div x-show="isLegendVisible"
         class="h-auto max-w-full md:max-w-1/3 flex-wrap flex items-center space-x-1 space-y-1 xl:space-y-0">
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/biz.png" class="w-4 h-4 inline-block mr-1">
            <span>Houses</span>
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/houses.png" class="w-4 h-4 inline-block mr-1">
            Businesses
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_gyma.png" class="w-4 h-4 inline-block mr-1">
            Gym
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_modGaragea.png" class="w-4 h-4 inline-block mr-1">
            Chopshop
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_hostpitala.png" class="w-4 h-4 inline-block mr-1">
            Hospital
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_policea.png" class="w-4 h-4 inline-block mr-1">
            Police Department
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_impounda.png" class="w-4 h-4 inline-block mr-1">
            Dealership
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_spraya.png" class="w-4 h-4 inline-block mr-1">
            Pay 'n' Spray
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_trucka.png" class="w-4 h-4 inline-block mr-1">
            Jobs
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_lighta.png" class="w-4 h-4 inline-block mr-1">
            Realty Listings
        </div>
        <div class="bg-gray-700 text-sm text-gray-200 px-2 py-1 rounded-lg">
            <img src="/assets/mapicons/radar_bulldozera.png" class="w-4 h-4 inline-block mr-1">
            Scrapyard
        </div>

    </div>
</div>

<!-- The container the map is rendered in -->
<div id="map-canvas"></div>


<!-- Load all javascript -->
<script src="http://maps.google.com/maps/api/js?key="></script>
<script src="../js/SanMap.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
<script>

    function legendComponent() {
        return {
            isLegendVisible: window.innerWidth >= 768, // default visibility based on screen width
            checkScreenSize() {
                this.isLegendVisible = window.innerWidth >= 768;
            },
            init() {
                // Initial check
                this.checkScreenSize();

                // Add a resize event listener to handle screen size changes
                window.addEventListener('resize', () => {
                    this.checkScreenSize();
                });
            }
        };
    }

    /*
     * Define the map types we will be using.
     *
     * SanMapType parameters: minZoom, maxZoom, getTileUrlFunction, [optional]tileSize.
     *
     * The default value for tileSize is 512.
     */

    var mapType = new SanMapType(0, 4, function (zoom, x, y) {
        return x == -1 && y == -1
            ? "tiles/map.outer.png"
            : "tiles/map." + zoom + "." + x + "." + y + ".png";//Where the tiles are located
    });

    /*
     * Create the map.
     *
     * createMap parameters: canvas, mapTypes, [optional]defaultZoomLevel,
     *     [optional]defaultLocation, [optional]allowRepeating, [optional]defaultMapType.
     *
     * The default value for defaultZoomLevel is 2.
     * The default value for defaultLocation is null.
     * The default value for allowRepeating is false.
     * The default value for defaultMapType is the first key in mapTypes.
     */
    var map = SanMap.createMap(document.getElementById('map-canvas'),
        {'Map': mapType}, 2, SanMap.getLatLngFromPos(1500, -1590), false, 'Map');

    /*
     *
     * The above code contain methods SanMap provide
     * From here on forth we only use methods provided by the Google API
     *
     */

    var TurfZones = [];
    var Turfs = [];
    var TurfColor = [];
    // Add Turfs
    @foreach($gangzones as $zone)
        TurfZones[{{ $zone->gz_sqlid }}] = {
        'array': [
            SanMap.getLatLngFromPos({{ $zone->gz_min_x }}, {{ $zone->gz_min_y}}),
            SanMap.getLatLngFromPos({{ $zone->gz_min_x }}, {{ $zone->gz_max_y}}),
            SanMap.getLatLngFromPos({{ $zone->gz_max_x }}, {{ $zone->gz_max_y }}),
            SanMap.getLatLngFromPos({{ $zone->gz_max_x }}, {{ $zone->gz_min_y }}),
        ]
    };

    @php
        // get faction color safely
        $factionIndex = $zone->gz_faction - 1;
        $hexValue = $zone->gz_faction > 0 ? $factions[$factionIndex]->faction_hex : 0x808080;
        $hexString = $zone->gz_faction > 0 
            ? str_pad(dechex($hexValue & 0xFFFFFFFF), 8, '0', STR_PAD_LEFT)
            : '808080'; 
    @endphp

    TurfColor[{{ $zone->gz_sqlid }}] = "#{{ $hexString }}";

    console.log(TurfColor)

    Turfs[{{ $zone->gz_sqlid }}] = new google.maps.Polygon({
        paths: TurfZones[{{ $zone->gz_sqlid }}]['array'],
        strokeColor: TurfColor[{{ $zone->gz_sqlid }}],
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: TurfColor[{{ $zone->gz_sqlid }}],
        fillOpacity: 0.35,
        map: map,
        id: {{ $zone->gz_sqlid }}
    });

    google.maps.event.addListener(Turfs[{{ $zone->gz_sqlid }}], 'click', function (event) {
        var pos = SanMap.getPosFromLatLng(event.latLng);
        infoWindow.setPosition(SanMap.getLatLngFromPos(pos.x, pos.y));
        @php
        // get faction name
        $factionText = 'Unowned Turf';
        if ($zone->gz_faction > 0 && isset($factions[$zone->gz_faction - 1])) {
            $factionText = $factions[$zone->gz_faction - 1]->faction_name . ' Turf';
        }
        @endphp
        infoWindow.setContent('<b>{{ $factionText }}</b>');
        infoWindow.open(map);
    });

    // generate a random number between 1 and 10, if the number is 5, animate the polygon
    @if($zone->gz_contested)
        animateOpacity([{{ $zone->gz_sqlid }}]);
    @endif

    @endforeach

    console.log(TurfColor)

    function animateOpacity(ids) {
        let increasing = false;
        let opacity = 0.35;
        const speed = 0.009;

        function step() {
            if (increasing) {
                opacity += speed;
                if (opacity >= 0.35) {
                    opacity = 0.35;
                    increasing = false;
                }
            } else {
                opacity -= speed;
                if (opacity <= 0) {
                    opacity = 0;
                    increasing = true;
                }
            }

            // Update the opacity of specified polygons
            ids.forEach(id => {
                if (Turfs[id]) {
                    Turfs[id].setOptions({fillOpacity: opacity});
                }
            });

            // Continue the animation
            requestAnimationFrame(step);
        }

        // Start the animation
        requestAnimationFrame(step);
    }

    var Houses = [];
    var Biz = [];
    var infoWindow = new google.maps.InfoWindow();
    var PointsOfInterest = [];

    // Add Properties
    @foreach($properties as $property)
    @if($property->property_type == 0)
    @if($property->property_owner == -1)
    var icon = '/assets/mapicons/radar_propertyRa.png';
    @else
    var icon = '/assets/mapicons/radar_propertyGa.png';
    @endif
        Houses[{{$property->property_id}}] = new google.maps.Marker({
        position: SanMap.getLatLngFromPos({{ $property->property_ext_x }}, {{$property->property_ext_y}}),
        map: map,
        icon: icon,
    });

    google.maps.event.addListener(Houses[{{$property->property_id}}], 'click', function () {
        infoWindow.setPosition(SanMap.getLatLngFromPos({{ $property->property_ext_x }}, {{$property->property_ext_y}}));
        infoWindow.setContent('<b>{{$property->property_id}}</b>');
        infoWindow.open(map);
    });
    @endif
    @endforeach

    @foreach($properties as $property)
    @if($property->property_type == 1)
    @if($property->property_owner == -1)
    var icon = '/assets/mapicons/biz_unowned.png';
    @else
    var icon = '/assets/mapicons/biz_owned.png';
    @endif
        Biz[{{$property->property_id}}] = new google.maps.Marker({
        position: SanMap.getLatLngFromPos({{ $property->property_ext_x }}, {{$property->property_ext_y}}),
        map: map,
        icon: icon,
    });

    google.maps.event.addListener(Biz[{{$property->property_id}}], 'click', function () {
        infoWindow.setPosition(SanMap.getLatLngFromPos({{ $property->property_ext_x }}, {{$property->property_ext_y}}));
        infoWindow.setContent('<b>{{$property->property_id}}</b>');
        infoWindow.open(map);
    });
    @endif
    @endforeach

    // Add Properties
    @foreach($points_of_interest as $poi)

        PointsOfInterest[{{$poi["id"]}}] = new google.maps.Marker({
        position: SanMap.getLatLngFromPos({{ $poi["x"] }}, {{$poi["y"]}}),
        map: map,
        icon: '{{$poi["icon_path"]}}',
    });

    google.maps.event.addListener(PointsOfInterest[{{$poi["id"]}}], 'click', function () {
        infoWindow.setPosition(SanMap.getLatLngFromPos({{ $poi["x"] }}, {{$poi["y"]}}));
        infoWindow.setContent('<b>{{$poi["name"]}}</b>');
        infoWindow.open(map);
    });
    @endforeach

    function toggleBiz() {
        for (var marker in Biz) {
            if (Biz[marker].getVisible()) {
                Biz[marker].setVisible(false);
            } else {
                Biz[marker].setVisible(true);
            }
        }
    }

    function toggleHouses() {
        for (var marker in Houses) {
            if (Houses[marker].getVisible()) {
                Houses[marker].setVisible(false);
            } else {
                Houses[marker].setVisible(true);
            }
        }
    }

    function toggleTurfs() {
        for (var turf in Turfs) {
            if (Turfs[turf].getVisible()) {
                Turfs[turf].setVisible(false);
            } else {
                Turfs[turf].setVisible(true);
            }
        }
    }

    function togglePoi() {
        for (var poi in PointsOfInterest) {
            if (PointsOfInterest[poi].getVisible()) {
                PointsOfInterest[poi].setVisible(false);
            } else {
                PointsOfInterest[poi].setVisible(true);
            }
        }
    }


</script>
</body>
