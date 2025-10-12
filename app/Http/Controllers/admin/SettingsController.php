<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\AppInfoModel;
use App\Models\admin\Payment;
use App\Models\AuditTrailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    // Update App Info
    public function updateapp_info(Request $request)
    {
        $build_input = [];
        $check_record = AppInfoModel::find(1);

        if ($request->has('appname')) $build_input['app_name'] = $request->input('appname');
        if ($request->hasFile('logo')) {
            if ($check_record && $check_record->logo) Storage::disk('public')->delete($check_record->logo);
            $build_input['logo'] = $request->file('logo')->store('logos', 'public');
        }
        if ($request->hasFile('banner')) {
            if ($check_record && $check_record->banner) Storage::disk('public')->delete($check_record->banner);
            $build_input['banner'] = $request->file('banner')->store('banners', 'public');
        }
        if ($request->has('applink')) $build_input['website'] = $request->input('applink');
        if ($request->has('facebooklink')) $build_input['facebook'] = $request->input('facebooklink');
        if ($request->has('youtubelink')) $build_input['youtube'] = $request->input('youtubelink');
        if ($request->has('contact')) $build_input['contact'] = $request->input('contact');
        if ($request->has('address')) $build_input['address'] = $request->input('address');
        if ($request->has('email')) $build_input['email'] = $request->input('email');
        if ($request->has('guidelines')) $build_input['guidelines'] = $request->input('guidelines');
        if ($request->has('sum_mission_vision')) $build_input['mission_vission'] = $request->input('sum_mission_vision');
        if ($request->has('terms_and_condition')) $build_input['terms_and_condition'] = $request->input('terms_and_condition');
        if ($request->has('about_us')) $build_input['about_us'] = $request->input('about_us');

        $action = $check_record ? 'update' : 'create';
        $description = $check_record ? 'Updated app info' : 'Created new app info';

        if ($check_record) {
            $check_record->update($build_input);
        } else {
            AppInfoModel::create($build_input);
        }

        AuditTrailModel::create([
            'userID'      => Auth::id(),
            'action'      => $action,
            'description' => $description,
            'ip_address'  => $request->ip(),
        ]);

        return response()->json(['message' => $description, 'status' => 200]);
    }

    // Save Payment (Multiple entries allowed)
    public function savePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
            'payment_number' => 'required|string|max:255',
            'payment_qr'     => 'nullable|image|max:2048',
        ]);

        $build_input = [
            'method' => $request->payment_method,
            'number' => $request->payment_number,
        ];

        // Handle QR image if uploaded
        if ($request->hasFile('payment_qr')) {
            $build_input['qr'] = $request->file('payment_qr')->store('payment_qr', 'public');
        }

        // Create new payment record
        $payment = Payment::create($build_input);

        // Audit trail
        AuditTrailModel::create([
            'userID'      => Auth::id(),
            'action'      => 'create',
            'description' => 'Added new payment option (ID: '.$payment->id.')',
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('success', 'Payment option saved successfully!');
    }

    public function settingsPage()
{
    // Fetch all payment options
    $payments = Payment::latest()->get();

    // Fetch app info if needed
    $appInfo = AppInfoModel::find(1);

    return view('admin.pages.settings', compact('payments', 'appInfo'));
}

// Delete payment option
public function deletePayment($id)
{
    $payment = Payment::findOrFail($id);

    // Delete QR image from storage if exists
    if ($payment->qr) {
        Storage::disk('public')->delete($payment->qr);
    }

    $payment->delete();

    // Log in audit trail
    AuditTrailModel::create([
        'userID'      => Auth::id(),
        'action'      => 'delete',
        'description' => 'Deleted payment option (ID: '.$id.')',
        'ip_address'  => request()->ip(),
    ]);

    return back()->with('success', 'Payment option deleted successfully!');
}

}
