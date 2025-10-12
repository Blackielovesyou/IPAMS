<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Payment;


class PermitController extends Controller
{
    public function showPermit(Request $request)
    {
        $type = $request->query('type');  // building, plumbing, etc.
        $payments = Payment::all(); 
              // fetch all payment options

        return view('user.pages.permit', compact('type', 'payments'));
    }
}
