<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AuditController;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Mail\WelcomeMail;
use App\Models\Department;
use App\Models\MinorSubjectCategoryModel;
use App\Models\User;
use App\Models\user_verification;
use App\Models\ModuleAccessModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add_staff_account()
    {
        return view('admin.pages.inputs.add_staff_account');
    }

    public function add_inspector_account()
    {
        return view('admin.pages.inputs.add_inspector_account');
    }

    public function add_applicant_account()
    {
        return view('admin.pages.inputs.add_applicant_account');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'contact' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'password' => 'nullable|min:8|confirmed',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'validID' => 'required|string',
            'verification_docs' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $loggedInUser = Auth::user();
        if ($validator->fails()) {
            AuditController::logging('Add profile', 'Update profile failed: ' . json_encode($validator->errors()), $request);

            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = Auth::user();
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->gender = $request->gender;
        $user->contact = $request->contact;
        $user->address = $request->address;
        $user->first_open = 1;

        if ($request->hasFile('profile_img')) {
            $profilePath = $request->file('profile_img')->store('profile', 'public');
            $user->profile_img = $profilePath;
        }

        $filePath = null;
        if ($request->hasFile('verification_docs')) {
            $filePath = $request->file('verification_docs')->store('verification_docs', 'public');
            $user->valid_id = $filePath;
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $build_verification_data = [
            'userID' => $user->id,
            'id_type' => $request->validID,
            'id_file_url' => $filePath,
            'userStatus' => 0
        ];

        user_verification::insert($build_verification_data);

        AuditController::logging('Update profile', 'Profile updated successfully.', $request);
        return response()->json(['message' => 'Profile updated successfully.'], 200);
    }
    public function get_users(Request $request)
    {
        // Default: no users
        $query = User::query();

        $get_users = $query
            ->leftJoin('blocked_account', 'users.id', '=', 'blocked_account.userID')
            ->leftJoin('user_verification', 'user_verification.userID', '=', 'users.id')
            ->select(
                'users.*',
                DB::raw('MD5(users.id) as encrypt_id'),
                DB::raw('CASE WHEN blocked_account.userID IS NULL THEN "Active" ELSE "blocked" END as status'),
                'user_verification.userStatus',
                'user_verification.id_type',
                'user_verification.id_file_url',
                DB::raw('COALESCE(user_verification.userStatus, "3") as userStatus')
            )
            ->whereNot('users.user_type', 0)
        ;

        if (isset($request->type)) {
            if ($request->type == 3) {
                $subQuery = $query->where('users.user_type', 1)->where('is_inspector', 1)->get();
            } elseif ($request->type == 1) {
                $subQuery = $query->where('users.user_type', 1)->where('is_inspector', 0)->get();
            } elseif ($request->type == 2) {
                $subQuery = $query->where('users.user_type', 2)->get();
            }
        }

        return response()->json($subQuery);
    }
    public function verifyUser(Request $request)
    {
        $user_verification = user_verification::where(DB::raw('MD5(userID)'), $request->id)->first();
        if ($user_verification) {
            $user_verification->userStatus = $request->action;
            $user_verification->save();

            AuditController::logging('User verification', 'User status updated to ' . ($request->action == 1 ? 'Approved' : 'Declined') . ' for user id: ' . $user_verification->userID, $request);


            return response()->json(['success' => true, 'message' => 'User status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'User not found.']);
    }


    public function submit_staff_account(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact' => 'required|string',
            'user_type' => 'required|in:1,2',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {

            AuditController::logging('Add account', 'Add account failed: ' . $request->input('email'), $request);

            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Send email with the autogenerated password
        // try {
        //     Mail::to($request->email)->send(new WelcomeMail($fullname, $request->email, $randomPassword));
        // } catch (\Exception $e) {
        //     AuditController::logging('Add account', 'Add account failed (mail not sent): ' . $request->input('email'), $request);
        //     return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        // }

        $user = new User();
        $user->first_name = $request->input('firstname');
        $user->middle_name = $request->input('middlename');
        $user->last_name = $request->input('lastname');
        $user->name = $request->input('firstname') . ' ' . ($request->input('middlename') ? $request->input('middlename') . ' ' : '') . $request->input('lastname');
        $user->email = $request->input('email');
        $user->contact = $request->input('contact');
        $user->password = Hash::make($request->input('password'));
        $user->user_type = $request->input('user_type');
    
        $user->save();

        $moduleCodes = [];
        foreach ($moduleCodes as $code) {
            ModuleAccessModel::create([
                'userID' => $user->id,
                'module_code' => $code,
            ]);
        }

        AuditController::logging('Add account', 'Account created successfully for ' . $user->name, $request);

        return response()->json([
            'message' => 'Account created successfully. Email and password have been sent to ' . $request->email
        ], 200);
    }


    public function submit_inspector_account(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact' => 'required|string',
            'user_type' => 'required|in:1,2',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {

            AuditController::logging('Add account', 'Add account failed: ' . $request->input('email'), $request);

            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Send email with the autogenerated password
        // try {
        //     Mail::to($request->email)->send(new WelcomeMail($fullname, $request->email, $randomPassword));
        // } catch (\Exception $e) {
        //     AuditController::logging('Add account', 'Add account failed (mail not sent): ' . $request->input('email'), $request);
        //     return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        // }

        $user = new User();
        $user->first_name = $request->input('firstname');
        $user->middle_name = $request->input('middlename');
        $user->last_name = $request->input('lastname');
        $user->email = $request->input('email');
        $user->contact = $request->input('contact');
        $user->password = Hash::make($request->input('password'));
        $user->user_type = $request->input('user_type');
        $user->name = $request->input('firstname') . ' ' . ($request->input('middlename') ? $request->input('middlename') . ' ' : '') . $request->input('lastname');
        $user->is_inspector = 1;
        $user->save();

        $moduleCodes = [];
        foreach ($moduleCodes as $code) {
            ModuleAccessModel::create([
                'userID' => $user->id,
                'module_code' => $code,
            ]);
        }

        AuditController::logging('Add account', 'Account created successfully for ' . $user->name, $request);

        return response()->json([
            'message' => 'Account created successfully. Email and password have been sent to ' . $request->email
        ], 200);
    }


    public function submit_applicant_account(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact' => 'required|string',
            'user_type' => 'required|in:1,2',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {

            AuditController::logging('Add account', 'Add account failed: ' . $request->input('email'), $request);

            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Send email with the autogenerated password
        // try {
        //     Mail::to($request->email)->send(new WelcomeMail($fullname, $request->email, $randomPassword));
        // } catch (\Exception $e) {
        //     AuditController::logging('Add account', 'Add account failed (mail not sent): ' . $request->input('email'), $request);
        //     return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        // }

        $user = new User();
        $user->first_name = $request->input('firstname');
        $user->middle_name = $request->input('middlename');
        $user->last_name = $request->input('lastname');
        $user->email = $request->input('email');
        $user->contact = $request->input('contact');
        $user->password = Hash::make($request->input('password'));
        $user->user_type = $request->input('user_type');
        $user->save();

        $moduleCodes = [];
        foreach ($moduleCodes as $code) {
            ModuleAccessModel::create([
                'userID' => $user->id,
                'module_code' => $code,
            ]);
        }

        AuditController::logging('Add account', 'Account created successfully for ' . $user->name, $request);

        return response()->json([
            'message' => 'Account created successfully. Email and password have been sent to ' . $request->email
        ], 200);
    }

    public function display_user_profile($id)
    {
        $userDetails = User::select("users.*")
            ->where('users.id', $id)
            ->first();
        if ($userDetails) {
            $html = view('admin.pages.user_profile', [
                'userID' => $id,
                'user_data' => $userDetails
            ])->render();

            return response()->json(['html' => $html]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function changePassword(Request $request)
    {
        // Validate the request data
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if the old password matches the user's current password
        if (!Hash::check($request->old_password, Auth::user()->password)) {

            AuditController::logging('Edit password', 'Edit password failed: ' . Auth::user()->email, $request);

            return response()->json(['message' => 'The provided old password is incorrect.'], 500);
        }

        // Update the user's password
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        AuditController::logging('Edit password', 'Edit password success: ' . Auth::user()->email, $request);

        // Redirect the user back with a success message
        return response()->json(['message' => 'Password changed successfully.'], 200);
    }
    public function resetPassword(Request $request)
    {
        $randomPassword = Str::random(8);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        AuditController::logging('Reset password', 'Password reset for user: ' . $user->email, $request);

        $user->password = Hash::make($randomPassword);
        $user->save();

        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user->name, $user->email, $randomPassword));
        } catch (\Exception $e) {
            AuditController::logging('Reset password', 'Reset password failed (mail not sent): ' . $user->email, $request);
            return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Password reset successfully.', 'email' => $user->email], 200);
    }
}
