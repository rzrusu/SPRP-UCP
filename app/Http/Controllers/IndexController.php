<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Misc\OnlinePlayers;

class IndexController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function dashboard()
    {
        // get the player numbers for the past 7 days
        $onlinePlayers = OnlinePlayers::orderBy('created_at', 'desc')->take(7)->get();

        $xaxis = $onlinePlayers->pluck('created_at');
        $xaxis = $xaxis->map(function ($date) {
            return $date->format('l');
        });

        $yaxis = $onlinePlayers->pluck('players');

        return view('dashboard', compact(['xaxis', 'yaxis']));
    }
}
