<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index()
    {

        $points_of_interest = [
            ["id" => 0, "name" => "Los Santos Police Department", "x" => 1544.9873, "y" => -1675.1959, "icon_path" => "/assets/mapicons/radar_policea.png"],
            ["id" => 1, "name" => "County General Hospital", "x" => 2036.3203, "y" => -1403.9027, "icon_path" => "/assets/mapicons/radar_hostpitala.png"],
            ["id" => 2, "name" => "Department of Motor Vehicles", "x" => 1838.3475, "y" => -1443.8835, "icon_path" => "/assets/mapicons/radar_trucka.png"],
            ["id" => 3, "name" => "Realty Listings", "x" => 1742.9880, "y" => -1456.2135, "icon_path" => "/assets/mapicons/radar_lighta.png"],

            ["id" => 4, "name" => "Car Scrapyard", "x" => 2269.2249, "y" => -2126.1326, "icon_path" => "/assets/mapicons/radar_bulldozera.png"],
            ["id" => 5, "name" => "Boat Scrapyard", "x" => 2532.5679, "y" => -2272.2878, "icon_path" => "/assets/mapicons/radar_bulldozera.png"],
            ["id" => 6, "name" => "Plane Scrapyard", "x" => 2108.2424, "y" => -2437.9275, "icon_path" => "/assets/mapicons/radar_bulldozera.png"],

            ["id" => 7, "name" => "Ocean Docks - Dockworker Job", "x" => 2753.4329, "y" => -2453.7937, "icon_path" => "/assets/mapicons/radar_gyma.png"],
            ["id" => 8, "name" => "Ocean Docks - Garbageman Job", "x" => 2247.1138, "y" => -2672.9709, "icon_path" => "/assets/mapicons/radar_gyma.png"],

            ["id" => 9,"name" => "Budget Dealership", "x" => 2131.9111, "y" => -1150.7538, "icon_path" => "/assets/mapicons/radar_impounda.png"],
            [ "id" => 10,"name" => "Muscle Dealership", "x" => 2050.3438, "y" => -1901.6233, "icon_path" => "/assets/mapicons/radar_impounda.png"],
            [ "id" => 11,"name" => "Sports Dealership", "x" => 1359.7362, "y" => -1854.9513, "icon_path" => "/assets/mapicons/radar_impounda.png"],
            ["id" => 12, "name" => "Utility Dealership", "x" => 2420.1533, "y" => -2089.4521,"icon_path" => "/assets/mapicons/radar_impounda.png"],
            ["id" => 13, "name" => "Aircraft Dealership", "x" => 1957.5844, "y" => -2183.7864, "icon_path" => "/assets/mapicons/radar_impounda.png"],
            ["id" => 14, "name" => "Bike Dealership", "x" => 2352.9255, "y" => -1485.1898, "icon_path" => "/assets/mapicons/radar_impounda.png"],
            ["id" => 15, "name" => "Boat Dealership", "x" => 2370.5146, "y" => -2527.9280, "icon_path" => "/assets/mapicons/radar_impounda.png"],

            ["id" => 16, "name" => "Chop Shop", "x" => 2081.6187, "y" => -2033.8485, "icon_path" => "/assets/mapicons/radar_modGaragea.png"],

            ["id" => 17, "name" => "Gym: East Los Santos", "x" => 2359.5649, "y" => -1312.1975,  "icon_path" => "/assets/mapicons/radar_gyma.png"],
            ["id" => 18, "name" => "Gym: South Los Santos", "x" => 2148.6804, "y" => -1885.4329,  "icon_path" => "/assets/mapicons/radar_gyma.png"],
            ["id" => 19, "name" => "Gym: West Los Santos", "x" => 666.3855, "y" => -1879.8040,  "icon_path" => "/assets/mapicons/radar_gyma.png"],
            ["id" => 20, "name" => "Gym: Central Los Santos", "x" => 2238.9714, "y" => -1694.6267, "icon_path" => "/assets/mapicons/radar_gyma.png"],

            ["id" => 21, "name" => "Pay 'N' Spray: Willowfield", "x" => 2334.9880, "y" => -1991.6436, "icon_path" => "/assets/mapicons/radar_spraya.png"],
            ["id" => 22, "name" => "Pay 'N' Spray: Pershing Square", "x" => 1296.8934, "y" => -1865.5447, "icon_path" => "/assets/mapicons/radar_spraya.png"],
            ["id" => 23, "name" => "Pay 'N' Spray: Rodeo", "x" => 207.11590, "y" => -1446.4102, "icon_path" => "/assets/mapicons/radar_spraya.png"],
            ["id" => 24, "name" => "Pay 'N' Spray: Glen Park", "x" => 1833.8820, "y" => -1398.2865, "icon_path" => "/assets/mapicons/radar_spraya.png"]
        ];


        $factions = DB::select('SELECT * FROM factions');

        // get all gangzones from the db
        $gangzones = DB::select('SELECT * FROM gangzones WHERE gz_faction != -1');

        // get all properties from the db
        $properties = DB::select('SELECT * FROM properties WHERE property_ext_vw = 0 AND property_type != 2');

        //dd($gangzones);
        // return the map view with the gangzones
        return view('map', compact('gangzones', 'properties', 'points_of_interest', 'factions'));
    }
}
