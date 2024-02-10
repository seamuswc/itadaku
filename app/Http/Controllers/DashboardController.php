<?php

namespace App\Http\Controllers;

use App\Models\Mint;
use App\Models\Redeem;

class DashboardController extends Controller
{
    public function index()
    {
        $mints = Mint::all();
        $redeems = Redeem::all();

        return view('dashboard', compact('mints', 'redeems'));
    }
}
