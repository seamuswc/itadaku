<?php

namespace App\Http\Controllers;

use App\Models\Mint;
use App\Models\Redeem;
use App\Models\Done;


class DashboardController extends Controller
{
    public function index()
    {
        $mints = Mint::all();
        $redeems = Redeem::all();
        $dones = Done::all();


        return view('dashboard', compact('mints', 'redeems', 'dones'));
    }
}
