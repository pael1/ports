<?php

namespace App\Http\Controllers;

use Pusher\Pusher;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function save(Request $request)
    {
        Comment::create([
            'complaint_id' => $request->complaint_id,
            'from' => Auth::user()->username,
            'to' => $request->complaint_id,
            'comment' => $request->comment
        ]);

        Notification::updateOrCreate(
            [
                'complaint_id' => $request->complaint_id,
                'assignedto' => $request->assignedto,
            ],
            [
                'assignedto' => $request->assignedto,
                'complaint_id' => $request->complaint_id,
                //1 means unread
                'markmsg' => 1,
                'notifno' => $request->notifno,
                'from' => $request->from
            ]
        );


        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        // $data = [
        //     'assignedto' => $request->assignedto,
        //     'complaint_id' => $request->complaint_id,
        //     'admin' => 'yes'
        // ];
        $data = [
            'assignedto' => $request->assignedto,
            'notifno' => $request->notifno
        ];

        $pusher->trigger('my-channel', 'my-event', $data);

        return response()->json(
            [
                'success' => true,
                'message' => 'Data inserted successfully'
            ]
        );
    }
}
