<?php

namespace App\Http\Controllers;

use App\Models\NotificationModel;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function make_notification($user_id, $title, $content, $type, $user_type) {
        $notification = new NotificationModel();
        
        // check if admin 
        if($user_type == 1) {
            $get = User::where('user_type', 0)->first()->toArray();
            $user_id = $get['id'];
        }
        
        $notification->user_id = $user_id;
        $notification->title = $title;
        $notification->content = $content;
        $notification->type = $type;
        $notification->save();
    }
    public function getUnreadNotifications()
    {
        $unreadCount = auth()->user()->notifications()->where('is_click', 0)->count();
        return response()->json(['unread_count' => $unreadCount]);
    }
    public function markNotificationsAsRead()
    {
        // Update all notifications for the authenticated user to set is_click = 1
        auth()->user()->notifications()->where('is_click', 0)->update(['is_click' => 1]);
    
        return response()->json(['success' => true]);
    }
}
