<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNewMessages(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('notifications')
                ->select('complaint_id')->where([
                    ['assignedto', '=', $request->id],
                    ['markmsg', '!=', 0]
                ])
                ->get();
            return $data;
        }
        return view('getNewMessages');
    }

    public function openNotif()
    {
        // if (Auth::user()->designation != "Reviewer") {
            $complaint = DB::table('complaints')
                ->join('investigated_cases', 'complaints.id', '=', 'investigated_cases.complaint_id')
                ->join('notifications', 'complaints.id', '=', 'notifications.complaint_id')
                ->join('users', 'notifications.from', '=', 'users.id')
                ->select(
                    'complaints.*',
                    'notifications.*',
                    DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) as name, 
            DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled, users.email")
                )->where('notifications.assignedto', '=', Auth::user()->id)->orderBy('complaints.id', 'desc')->get();
        // } else {
        //     $complaint = DB::table('complaints')
        //         ->join('investigated_cases', 'complaints.id', '=', 'investigated_cases.complaint_id')
        //         ->select(
        //             'complaints.*',
        //             DB::raw("investigated_cases.receivedby as name, 
        //         DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled, investigated_cases.is_read")
        //         )->where('investigated_cases.assignedTo', '=', Auth::user()->id)->get();
        // }
        return response()->json($complaint, 200);
    }

    public function updateMarkMsg($notifno)
    {
        Notification::where('notifno', $notifno)->update(array('markmsg' => 0));
    }
}
