<?php

namespace App\Http\Controllers;

use App\Models\Mint;
use App\Models\Redeem;
use App\Models\Done;


class DashboardController extends Controller
{
    public function index()
    {
        $mints = Mint::orderBy('created_at', 'desc')->get();
        $redeems = Redeem::orderBy('created_at', 'desc')->get();
        $dones = Done::orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('mints', 'redeems', 'dones'));
    }
}
