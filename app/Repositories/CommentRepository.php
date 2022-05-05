<?php
//(Here the App\Repositories is the folder name)
namespace App\Repositories;

use Pusher\Pusher;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class CommentRepository implements ICommentRepository
{
    public function getComment($complain_id)
    {
        // $case = DB::table('comments')->where(['complaint_id' => $complain_id])->get();

        $case = DB::table('comments')
        ->join('users', 'users.id', '=', 'comments.from')
        ->select('comments.*', DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) as fullname"))->where(['comments.complaint_id' => $complain_id])
        ->orderBy('comments.id', 'desc')->get();
        return $case;
    }

    public function save(array $request)
    {
        Comment::create([
            'complaint_id' => $request['complaint_id'],
            'from' => $request['from'],
            'to' => $request['to'],
            'comment' => $request['comment']
        ]);

        Notification::updateOrCreate(
            [
                'complaint_id' => $request['complaint_id'],
                'assignedto' => $request['to'],
            ],
            [
                'assignedto' => $request['to'],
                'complaint_id' => $request['complaint_id'],
                //1 means unread
                'markmsg' => 1,
                'notifno' => $request['notifno'],
                'from' => $request['from']
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

        $data = [
            'assignedto' => $request['to'],
            'notifno' => $request['notifno']
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