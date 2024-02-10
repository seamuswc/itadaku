<?php

namespace App\Http\Controllers;

use App\Models\Mint;
use Illuminate\Http\Request;
use Mail;

class MintController extends Controller
{
    public function store(Request $request)
    {
        $mint = Mint::create($request->all());


        try {
            Mail::raw("New mint entry: " . implode(", ", $request->all()), function ($message) {
                $message->to('jamesthaiphone@gmail.com')->subject('New Mint Entry');
            });
        } catch (Exception $e) {
            // Do nothing and continue
            // You can leave this block empty or add a comment
            //return back()->with('error', 'Email error');
        }

       
        session(['formData' => $request->all()]);
        session(['form_submitted' => true]);
        return redirect()->route('mint.success');
    
    }

  

}
