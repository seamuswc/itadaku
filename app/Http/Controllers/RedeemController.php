<?php

namespace App\Http\Controllers;

use App\Models\Redeem;
use Illuminate\Http\Request;
use Mail;

class RedeemController extends Controller
{
    public function store(Request $request)
    {
        $redeem = Redeem::create($request->all());

        try {
            Mail::raw("New redeem request: " . implode(", ", $request->all()), function ($message) {
                $message->to('jamesthaiphone@gmail.com')->subject('New Redeem request');
            });
        } catch (Exception $e) {
            // Do nothing and continue
            // You can leave this block empty or add a comment
        }
        
        return back()->with('success', 'Redeem form submitted successfully.');
    }
}
