<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function updateCity(Request $request)
    {
     
        $city = City::create($request->all());


        return back()->with('success', 'City updated successfully.');
 
    }
}
