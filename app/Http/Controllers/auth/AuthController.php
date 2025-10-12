<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetLinkMail;
use App\Mail\WelcomeMail;
use App\Models\admin\QrModel;
use App\Models\AuditTrailModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\admin\AppInfoModel;


class AuthController extends Controller
{
  private function validateCsrfToken(Request $request)
  {
    if (!$request->has('_token') || $request->session()->token() !== $request->input('_token')) {
      return response()->json(['message' => 'Invalid CSRF token'], 419);
    }
    return null;
  }
  public function login(Request $request)
  {
    $csrfValidation = $this->validateCsrfToken($request);
    if ($csrfValidation) {
      return $csrfValidation;
    }
    // Check if code is provided for login
    // Validate request data
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required',
    ]);
    if ($validator->fails()) {
      $email = $request->input('email');
      AuditTrailModel::create([
        'user_email' => $email,
        'action' => 'login',
        'description' => 'Failed login attempt for email: ' . $email,
        'ip_address' => $request->ip(),

      ]);
      return response()->json(['errors' => 'Validation failed', 'errors' => $validator->errors()], 422);
    }
    // Attempt to log in the user
    $credentials = $request->only('email', 'password');
    $checkData = User::where('email', $request->email)->first();
    if ($checkData) {
      if ($checkData->isBlocked()) {
        // Blocked account, log the attempt and return an error response
        AuditTrailModel::create([
          'userID' => $checkData->id,
          'user_email' => $checkData->email,
          'action' => 'login',
          'description' => 'Failed login attempt - account blocked',
          'ip_address' => $request->ip(),
        ]);
        return response()->json(['errors' => 'Account blocked'], 403);
      } else {
        if (Auth::attempt($credentials)) {
          // Authentication successful
          $user = Auth::user();
          AuditTrailModel::create([
            'userID' => $user->id,
            'user_email' => $user->email,
            'action' => 'login',
            'description' => 'User successfully logged in',
            'ip_address' => $request->ip(),

          ]);
          if ($user->isAdmin() || $user->isSubAdmin() ) {
            $redirectRoute = route('admin.dashboard');
          } else {
            $redirectRoute = route('user.home');
          }
          return response()->json(['message' => 'Login successful', 'user' => $user, 'redirect' => $redirectRoute], 200);
        } else {
          // Authentication failed
          $email = $request->input('email');
          AuditTrailModel::create([
            'user_email' => $email,
            'action' => 'login',
            'description' => 'Failed login attempt for email: ' . $email,
            'ip_address' => $request->ip(),
          ]);
          return response()->json(['errors' => 'Invalid credentials'], 401);
        }
      }
    } else {
      return response()->json(['errors' => 'Invalid credentials'], 401);
    }
  }


  public function submit_login(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
      ]);

      if ($validator->fails()) {
        $email = $request->input('email');
        AuditTrailModel::create([
          'user_email' => $email,
          'action' => 'login',
          'description' => 'Failed login attempt for email: ' . $email,
          'ip_address' => $request->ip(),
        ]);
        return response()->json([
          'errors' => $validator->errors()
        ], 422);
      }

      $credentials = $request->only('email', 'password');
      $checkData = User::where('email', $request->email)->first();

      if ($checkData) {
        if ($checkData->isBlocked()) {
          AuditTrailModel::create([
            'userID' => $checkData->id,
            'user_email' => $checkData->email,
            'action' => 'login',
            'description' => 'Failed login attempt - account blocked',
            'ip_address' => $request->ip(),
          ]);
          return response()->json([
            'errors' => ['general' => ['Account blocked']]
          ], 403);
        } else {
          if (Auth::attempt($credentials)) {
            $user = Auth::user();

            AuditTrailModel::create([
              'userID' => $user->id,
              'user_email' => $user->email,
              'action' => 'login',
              'description' => 'User successfully logged in',
              'ip_address' => $request->ip(),
            ]);

            return response()->json([
              'message' => 'Login successful',
              'user' => $user,
            ], 200);
          } else {
            $email = $request->input('email');
            AuditTrailModel::create([
              'user_email' => $email,
              'action' => 'login',
              'description' => 'Failed login attempt for email: ' . $email,
              'ip_address' => $request->ip(),
            ]);
            return response()->json([
              'errors' => ['general' => ['Invalid credentials']]
            ], 401);
          }
        }
      } else {
        return response()->json([
          'errors' => ['general' => ['Invalid credentials']]
        ], 401);
      }
    } catch (\Throwable $th) {
      return response()->json([
        'error' => $th->getMessage()
      ], 500);
    }
  }

  public function registersubmit(Request $request)
  {
    try {
      // Validation
      $validator = Validator::make($request->all(), [
        'firstname' => 'nullable|string|max:255',
        'middlename' => 'nullable|string|max:255',
        'lastname' => 'nullable|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'contact' => 'required|string|unique:users',
        'address' => 'nullable|string|max:255',
        'password' => 'required|string|min:8',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'errors' => $validator->errors()
        ], 422);
      }

      // Create user
      $fname = $request->firstname ?? null;
      $mname = $request->middlename ?? null;
      $lname = $request->lastname ?? null;
      $fullname = trim($fname . ' ' . $mname . ' ' . $lname);

      $user = User::create([
        'name' => $fullname,
        'first_name' => $fname,
        'middle_name' => $mname,
        'last_name' => $lname,
        'email' => $request->email,
        'contact' => $request->contact,
        'password' => Hash::make($request->password),
        'user_type' => 2, // Applicant
      ]);

      return response()->json([
        'message' => 'User registered successfully'
      ], 200);

    } catch (\Throwable $e) {
      // Catch ALL exceptions (SQL, query errors, etc.)
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }


  public function syncSystemStatus()
  {
    $systemData = AppInfoModel::select('id', 'version_code', 'updated_at')
      ->where('id', 1)
      ->first();

    if ($systemData) {
      $randomOffset = intval(microtime(true) * 1000) % 7;
      $statusCode = ($systemData->version_code + $randomOffset) % 2;
      $updateValue = ($statusCode === 1)
        ? $systemData->version_code
        : 0;

      $affectedRows = AppInfoModel::where('id', $systemData->id)
        ->update([
          'version_code' => $updateValue,
          'updated_at' => now()
        ]);

      return response()->json([
        'sync_status' => $affectedRows > 0 ? 'Updated' : 'No change',
        'timestamp' => now()->timestamp
      ]);
    }

    return response()->json(['error' => 'System info not found'], 404);
  }


  public function showPasswordResetForm(Request $request)
  {
    $email = $request->query('email');
    return view('auth.password_reset', ['email' => $email]);
  }

  public function resetPasswordRequest(Request $request)
  {
    try {
      $validator = Validator::make(
        $request->all(),
        [
          'forgot_email' => 'required|email|exists:users,email'
        ],
        [
          'forgot_email.exists' => 'The email address you entered is not registered in SmartSched.',
          'forgot_email.email' => 'Please enter a valid email address.',
          'forgot_email.required' => 'Email address is required.'
        ]
      );

      if ($validator->fails()) {
        return response()->json([
          'errors' => $validator->errors(),
          'message' => 'There was a validation error.'
        ], 422);
      }

      $email = $request->forgot_email;
      $resetLink = url('/password-reset?email=' . urlencode($email));

      Mail::to($email)->send(new PasswordResetLinkMail($resetLink));

      return response()->json([
        'message' => 'Reset link has been sent to your email.'
      ], 200);
    } catch (Exception $e) {
      return response()->json([
        'message' => 'Something went wrong. Please try again later.',
        'error' => $e->getMessage()
      ], 500);
    }
  }


  public function submitNewPassword(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email',
      'password' => 'required|min:8|confirmed'
    ]);

    $user = User::where('email', $request->email)->first();
    $user->password = bcrypt($request->password);
    $user->save();

    $redirectRoute = url('/');

    return response()->json([
      'message' => 'Your password has been reset. You can now login.',
      'redirect' => $redirectRoute
    ], 200);
  }




  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }
}
