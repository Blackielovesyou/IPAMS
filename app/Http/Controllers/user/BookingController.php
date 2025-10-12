<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Mail\CancellationNotification;
use App\Models\AuditTrailModel;
use App\Models\BookingVisitor;
use App\Models\BookVisitationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function get_booking($transaction_number = null, $bookID = null) {
        $get_booking = BookingVisitor::leftJoin('book_visitation as b', 'booking_visitor.booking_id', '=', 'b.id')
        ->leftJoin('pdl_data as c', 'c.id', '=', 'b.pdl_id')
        ->select(
            DB::raw('MD5(b.id) as bookID'),
            'b.status',
            'b.type',
            'c.name as pdl_name',
            'b.transaction_number',
            'b.valid_id',
            'b.verification_docs',
            'b.link_type',
            'b.meeting_link',
            DB::raw('DATE(b.start_visit) as date'),
            DB::raw('CONCAT(b.start_time, " - ", b.end_time) as time')
        );
        
        if($transaction_number != null && $bookID != null) {
            $get_booking = $get_booking->where('transaction_number', $transaction_number)
                ->whereRaw('MD5(b.id) = ?', [$bookID])->first();
                
            // get visitors 
            $get_visitors = BookingVisitor::leftJoin('book_visitation as b', 'booking_visitor.booking_id', '=', 'b.id')
            ->leftJoin('pdl_data as c', 'c.id', '=', 'b.pdl_id')
            ->leftJoin('users as d', 'd.id', '=', 'booking_visitor.visitor_id')
            ->where('transaction_number', $transaction_number)
            ->whereRaw('MD5(b.id) = ?', [$bookID])
            ->select('d.name', 'd.family_position')->get();
            $html = view('user.bookings.booking_detail', [
                'bookingDetail' => $get_booking,
                'visitors' => $get_visitors
            ])->render();
            return response()->json($html);
        }
        $get_booking = $get_booking->where('booking_visitor.visitor_id', Auth::user()->id)->get();
        return response()->json($get_booking);
    }
    public function cancel_booking(Request $request) {
        $transaction_number = $request->input('transaction_number');
        $bookID = $request->input('bookID');
        $reason = $request->input('reason');

        // Find the booking and update the status to canceled
        $booking = BookVisitationModel::where('transaction_number', $transaction_number)
            ->whereRaw('MD5(id) = ?', [$bookID])
            ->first();

        if ($booking) {
            $booking->status = 2; // Assuming 2 is the status for canceled
            $booking->cancel_reason = $reason;
            $booking->save();
            // Get the PDL name
            $pdl_data = DB::table('pdl_data')->where('id', $booking->pdl_id)->first();
    
            // Prepare booking details for the email
            $bookingDetails = [
                'transaction_number' => $transaction_number,
                'pdl_name' => $pdl_data->name,
                'date' => $booking->start_visit,
                'time' => $booking->start_time . ' - ' . $booking->end_time,
                'type' => $booking->type,
            ];
             // Get visitor emails
            $visitorEmails = BookingVisitor::where('booking_id', $booking->id)
                ->leftJoin('users', 'booking_visitor.visitor_id', '=', 'users.id')
                ->pluck('users.email')
                ->toArray();
        
            // Send cancellation email notification to each visitor
            $email_arr = [];
            foreach ($visitorEmails as $email) {
                $email_arr[] = $email;
            }
            Mail::to($email_arr)->send(new CancellationNotification($bookingDetails, $reason));
            
            AuditTrailModel::create([
                'userID' => Auth::id(),
                'user_email' => Auth::user()->email,
                'action' => 'Booking Canceled',
                'description' => 'Booking canceled with transaction number: ' . $transaction_number . ' and reason: ' . $reason,
                'ip_address' => $request->ip(),
            ]);
            
            NotificationController::make_notification(0,'Booking Canceled', 'Booking canceled with transaction number: ' . $transaction_number . ' and reason: ' . $reason, 'booking', 1);
            return response()->json(['status' => 'success', 'message' => 'Booking canceled successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
        }
    }
}
