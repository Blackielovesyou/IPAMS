<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Mail\BookingNotification;
use App\Models\admin\SlotModel;
use App\Models\admin\VisitorModel;
use App\Models\AuditTrailModel;
use App\Models\BookingVisitor;
use App\Models\BookVisitationModel;
use App\Models\ExcludeBookModel;
use App\Models\Library\FacilitiesModel;
use App\Models\Library\PdlModel;
use App\Models\user_verification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Browsershot\Browsershot;
class VisitController extends Controller
{

    public function get_tag_pdl($userID){
        $pdl_records = VisitorModel::where('userID', $userID)
        ->join('pdl_data', 'visitor_pdl.pdlID', '=', 'pdl_data.id')
        ->select('pdl_data.id as pdlID', 'pdl_data.name as pdlName')
        ->get();
        
        return $pdl_records;
    }
    public function get_booking() {
        return view('user.pages.visitationstatus');
    }
    public function get_tag_visitor(Request $request){
        $visitors_list = VisitorModel::where('pdlID', $request->pdlID)
            ->join('users', 'visitor_pdl.userID', '=', 'users.id')
            ->select('users.id as visitor_id', 'users.name as visitor_name')
            ->get();
            
        return response()->json($visitors_list);
    }
    public function display_form_physical() {
        $userID = Auth::user()->id;
        // get pld tag in user
        $pdl_records = VisitController::get_tag_pdl($userID);
        return view('user.pages.visitform', ['list_pdl' => $pdl_records, 'type' => 'Physical']);
    }
    public function display_form_virtual() {
        $userID = Auth::user()->id;
        // get pld tag in user
        $pdl_records = VisitController::get_tag_pdl($userID);
        return view('user.pages.visitform', ['list_pdl' => $pdl_records, 'type' => 'Virtual']);
    }
    public function add_booking_submit(Request $request) {
        $request->validate([
            'date_visit' => 'required|date',
            'type_visit' => 'required|string',
            'time_visit' => 'required|string',
            'pdl_id' => 'required|integer',
            'list_visitor' => 'required|array',
            'verification_docs' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048' // Ensure verification_docs is required, a file, and of specific types with size limits
        ]);
        
        $date = Carbon::createFromFormat('Y-m-d', $request->date_visit)->startOfDay()->toDateTimeString();
        $type = $request->type_visit;
        $timeSlots = [
            '1' => ['start' => '8:00 am', 'end' => '8:30 am'],
            '2' => ['start' => '8:30 am', 'end' => '9:00 am'],
            '3' => ['start' => '9:00 am', 'end' => '9:30 am'],
            '4' => ['start' => '9:30 am', 'end' => '10:00 am'],
            '5' => ['start' => '10:00 am', 'end' => '10:30 am'],
            '6' => ['start' => '10:30 am', 'end' => '11:00 am'],
            '7' => ['start' => '11:00 am', 'end' => '11:30 am'],
            '8' => ['start' => '11:30 am', 'end' => '12:00 pm'],
            '9' => ['start' => '1:00 pm', 'end' => '1:30 pm'],
            '10' => ['start' => '1:30 pm', 'end' => '2:00 pm'],
            '11' => ['start' => '2:00 pm', 'end' => '2:30 pm'],
            '12' => ['start' => '2:30 pm', 'end' => '3:00 pm'],
            '13' => ['start' => '3:00 pm', 'end' => '3:30 pm'],
            '14' => ['start' => '3:30 pm', 'end' => '4:00 pm'],
            '15' => ['start' => '4:00 pm', 'end' => '4:30 pm'],
            '16' => ['start' => '4:30 pm', 'end' => '5:00 pm'],
        ];
        $start_time = $timeSlots[$request->time_visit]['start'];
        $end_time = $timeSlots[$request->time_visit]['end'];
        
        // Check if book by doctor or lawyer 
        $is_custom_book = ExcludeBookModel::where('start_event', $date)->exists();
        if($is_custom_book){
            return response()->json(['errors' => 'Selected date is invalid, pdl is not available'], 422);
        }
        
        // Check if selected date is blocked
        $is_date_block = SlotModel::where('start', $date)->where('title', 'Blocked')->exists();
        if($is_date_block){
            return response()->json(['errors' => 'Selected date is blocked'], 422);
        }
        // check if there is a available slot in selected date
        $check_if_available = SlotModel::where('start', $date)
            ->where('title', 'Maximum Slot')->first();
            
        if($check_if_available) {
            if ((
                ($type === "Virtual" && $check_if_available['virtual_slots'] == 0) ||
                ($type !== "Virtual" && $check_if_available['slots'] == 0)
            )) {
                return response()->json(['errors' => 'No slot available'], 422);
            }
        } else {
            return response()->json(['errors' => 'No slot available'], 422);
        }
        
        // Check if pdl visitation is deactivated
        $pdl_data = PdlModel::where('id', $request->pdl_id)->where('status', 'Active')->first();
        if(!$pdl_data) {
            return response()->json(['errors' => 'PDL Visitation is Deactivated'], 422);
        }
        
        $existingBooking = DB::table('booking_visitor')
        ->join('book_visitation', 'booking_visitor.booking_id', '=', 'book_visitation.id')
        ->where('book_visitation.start_visit', $request->date_visit)
        ->where('booking_visitor.visitor_id', auth()->id()) // Assuming you are using Laravel's authentication system
        ->whereIn('book_visitation.status', [0, 1])
        ->exists();
    
        if ($existingBooking) {
            return response()->json(['errors' => 'You have already request a visit on this date'], 422);
        }
        
        // Count Facilities
        $facilities = FacilitiesModel::where('status', 'Active')->count();
        // Define the maximum allowed bookings
        $maxBookings = $facilities;
        // check available considering the number of device use by office
        $existingBookingCount = DB::table('booking_visitor')
        ->join('book_visitation', 'booking_visitor.booking_id', '=', 'book_visitation.id')
        ->where('book_visitation.start_visit', $request->date_visit)
        ->where('book_visitation.start_time', $start_time)
        ->whereIn('book_visitation.status', [0, 1])
        ->count();
        // Check if the number of bookings exceeds the maximum limit
        if ($existingBookingCount >= $maxBookings) {
            return response()->json(['error' => 'Maximum number of bookings reached for the selected time and date.'], 422);
        }
        
        $filePath = null;
        if ($request->hasFile('verification_docs')) {
            $filePath = $request->file('verification_docs')->store('verification_docs', 'public');
        }
        $transaction_number = uniqid();
        
        // get verification docs
        $verification_docs = user_verification::where('userID', auth()->id())->first();
        
        $booking = BookVisitationModel::create([
            'transaction_number' => 'book-'.$transaction_number,
            'pdl_id' => $request->pdl_id,
            'start_visit' => $request->date_visit,
            'end_visit' => $request->date_visit,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'type' => $request->type_visit,
            'status' => 0,
            'valid_id' => $verification_docs->id_type, // Save the selected valid ID type
            'verification_docs' => $filePath, // Save the file path
        ]);
        
        // get the pdl name
        $pdl_data = PdlModel::where('id', $request->pdl_id)->first();
        $visitorEmails = [];
        $visitors = $request->list_visitor;
        if ($visitors && is_array($visitors)) {
            foreach ($visitors as $visitorId) {
                // Assuming you have a pivot table to associate visitors with bookings
                $booking->visitors()->attach($visitorId);
                        // Find visitor email
                $visitor = VisitorModel::where('userID', $visitorId)->first();
                if ($visitor) {
                    $visitorEmails[] = $visitor['email'];
                }
            }
        }
        $bookingDetails = [
            'transaction_number' => 'book-' . $transaction_number,
            'pdl_id' => $pdl_data['pdl_id'],
            'pdl_name' => $pdl_data['name'],
            'date' => $request->date_visit,
            'time' => $start_time . ' to ' . $end_time,
            'type' => $type,
        ];
        // Generate QR Code and save as an image
        $QRpath = storage_path('app\\public\\qrcodes\\' . $transaction_number . '.png');
        QrCode::format('png')->size(200)->generate('book-' . $transaction_number, $QRpath);
        
        // Send email notification to each visitor
        Mail::to($visitorEmails)
            ->cc($visitorEmails)
            ->send(new BookingNotification($bookingDetails, $QRpath));
        
         // Update slot
        if ($check_if_available) {
            if ($type == "Physical" && $check_if_available['slots'] != 0) {
                $check_if_available['slots'] -= 1;
                $check_if_available->save();
            }
            if ($type == "Virtual" && $check_if_available['virtual_slots'] != 0) {
                $check_if_available['virtual_slots'] -= 1;
                $check_if_available->save();
            }
        }
        AuditTrailModel::create([
            'userID' => Auth::id(),
            'user_email' => Auth::user()->email,
            'action' => 'Booking Created',
            'description' => 'Booking created with transaction number: book-' . $transaction_number,
            'ip_address' => $request->ip(),
        ]);
        
        NotificationController::make_notification(0,'Booking Created', 'Booking created with transaction number: book-' . $transaction_number, 'booking', 1);
        
        return response()->json(['message' => 'Successfully request a visit please wait for the confirmation of your booking'], 200);
    }
}
