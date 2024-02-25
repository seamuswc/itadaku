<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Mint;
use App\Models\Redeem;
use App\Models\Done;
use App\Models\City;



class DashboardController extends Controller
{
    public function index()
    {
        $mints = Mint::orderBy('created_at', 'desc')->get();
        $redeems = Redeem::orderBy('created_at', 'desc')->get();
        $dones = Done::orderBy('created_at', 'desc')->get();
        $city = City::orderBy('created_at', 'desc')->first();

        return view('dashboard', compact('mints', 'redeems', 'dones', 'city'));
    }
}
