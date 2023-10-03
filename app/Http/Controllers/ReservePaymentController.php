<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservePaymentController extends Controller
{
    public function payment(Request $request)
    {

        return Inertia::render('Reserve/index', [
            'seach' => 'a',
            ]);
    }
}
