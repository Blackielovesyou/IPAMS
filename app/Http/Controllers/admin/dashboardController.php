<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrailModel;
use App\Models\BookingVisitor;
use App\Models\BookVisitationModel;
use App\Models\CourseModel;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{

    public function display_dashboard()
    {
        return view('admin.dashboard');
    }


}
