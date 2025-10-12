<?php

namespace App\Http\Controllers;

use App\Models\AuditTrailModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
  public function index(Request $request)
  {
    $date_from = $request->input('date_from', Carbon::now()->format('Y-m-d'));
    $date_to = $request->input('date_to', Carbon::now()->format('Y-m-d'));

    $query = AuditTrailModel::query();

    if ($date_from && $date_to) {
      $query->whereBetween('created_at', [
        Carbon::parse($date_from)->startOfDay(),
        Carbon::parse($date_to)->endOfDay()
      ]);
    } elseif ($date_from) {
      $query->whereDate('created_at', $date_from);
    }

    $auditTrails = $query->get();

    return response()->json($auditTrails);
  }
  public static function logging($action, $description, $request)
  {
    AuditTrailModel::create([
      'userID' => Auth::id(),
      'action' => $action,
      'description' => $description,
      'ip_address' => $request->ip(), // Fetch the IP address
    ]);
  }
}
