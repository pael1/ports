<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function updateMarkMsg($notifno)
    {
        Notification::where('notifno', $notifno)->update(array('markmsg' => 0));
    }
}
