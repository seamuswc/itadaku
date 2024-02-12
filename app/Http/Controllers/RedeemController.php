<?php

namespace App\Http\Controllers;

use App\Models\Redeem;
use App\Models\Done;

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
        
        session(['formData' => $request->all()]);
        session(['form_submitted' => true]);
        return redirect()->route('redeem.success');
    }

    public function markAsRedeemed(Request $request, $redeemId)
    {
        $request->validate([
            'shipping_number' => 'required|string|max:255',
        ]);

        $redeem = Redeem::find($redeemId);
        
        if (!$redeem) {
            return back()->with('error', 'Failed to find the redeem item.');
        }

        // Optionally check if the item is already marked as redeemed
        if ($redeem->redeemed) {
            return back()->with('error', 'Item is already marked as redeemed.');
        }

        $redeem->redeemed = true;
        $redeem->save();

        Done::create([
            'redeem_id' => $redeemId,
            'shipping_number' => $request->shipping_number,
        ]);

        return back()->with('success', 'Item marked as redeemed successfully.');
    }

}
