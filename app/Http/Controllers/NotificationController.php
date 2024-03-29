<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;
use App\Models\Task;
use App\Models\Project;
use App\Models\Invite;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller {

    public function show() {
        $notifications = Notification::where('user_id', Auth::id())->get();
        foreach ($notifications as $notification) {
            if ($notification->task) {
                // Query to add the name to the notification object
                $taskName = $notification->task->name;
                $notification->task_name = $taskName;
            }
    
            if ($notification->project) {
                // Query to find the project name
                $projectName = $notification->project->name;
                $notification->project_name = $projectName;
            }
        }
        return view('pages.notifications', compact('notifications'));
    }

    public function destroy($id)
    {
        $notification = Notification::find($id);
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Notifications doesn\'t bellong to the user! Permission denied!']);
        }   

        $notification->delete();

        return redirect()->route('notification_show')->with('success', 'Notification deleted successfully!');
    }
    

    public function acceptInvite($id) {
        $notification = Notification::find($id);
    
        // Check if the notification exists and belongs to the authenticated user
        if (!$notification || $notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Notification not found or does not belong to the user. Permission denied!']);
        }
        
        //dd($notification->invite_id);
        // Get the actual invite
        $invite = Invite::find($notification->invite_id);
    
        // Check if the invite exists
        if (!$invite) {
            return response()->json(['message' => 'Invite not found.']);
        }

        // WHEN THE STATE IS UPDATED TO ACCEPTED THE NOTIFICATION IS DELETED AND THE USER IS ADDED TO THE PROJECT AS WELL AS THE INVITE IS DELETED
        // Update the invite status to "accepted"
        $invite->update(['state' => 'Accepted']);
        
        return redirect()->route('notification_show')->with('success', 'Successfully accepted project invitation!');
    }
    

    
    public function refuseInvite($id) {
        $notification = Notification::find($id);

        // Check if the notification exists and belongs to the authenticated user
        if (!$notification || $notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Notification not found or does not belong to the user. Permission denied!']);
        }

        // Get the actual invite
        $invite = Invite::find($notification->invite_id);

        // Check if the invite exists
        if (!$invite) {
            return response()->json(['message' => 'Invite not found.']);
        }

        // WHEN THE STATE IS UPDATED TO DECLINED THE NOTIFICATION IS DELETED AND THE INVITE IS DELETED
        // Update the invite status to "Declined"
        $invite->update(['state' => 'Declined']);

        return redirect()->route('notification_show')->with('success', 'Successfully refuse project invitation!');
    }






}
