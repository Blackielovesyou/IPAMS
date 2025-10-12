<?php

namespace App\Http\Controllers;

use App\Models\SchoolYearModel;
use App\Models\TeacherTypeModel;
use Illuminate\Http\Request;

class ShareableController extends Controller
{
    public function get_sy()
    {
        $schoolYears = SchoolYearModel::orderBy('year_from', 'asc')
            ->get(['id', 'year_from', 'year_to', 'status']);

        return response()->json($schoolYears);
    }
    public function get_teacher_type()
    {
        $teacherType = TeacherTypeModel::orderBy('name', 'asc')
            ->get(['id', 'name', 'instruction_hours']);
        return response()->json($teacherType);
    }
}
