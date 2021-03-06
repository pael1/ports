<?php

namespace App\Http\Controllers;

use Pusher\Pusher;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ICommentRepository;

class CommentController extends Controller
{
    protected $CommentRepository;

    //example of multiple __construct
    // public function __construct(IComplaint $complaintRepository, IComplaint1 $complaintRepository1)
    public function __construct(ICommentRepository $CommentRepository)
    {
        //list of Repo global names
        $this->CommentRepository = $CommentRepository;
    }

    public function save(Request $request)
    {
        // Comment::create([
        //     'complaint_id' => $request->complaint_id,
        //     'from' => $request->from,
        //     'to' => $request->to,
        //     'comment' => $request->comment
        // ]);

        // Notification::updateOrCreate(
        //     [
        //         'complaint_id' => $request->complaint_id,
        //         'assignedto' => $request->to,
        //     ],
        //     [
        //         'assignedto' => $request->to,
        //         'complaint_id' => $request->complaint_id,
        //         //1 means unread
        //         'markmsg' => 1,
        //         'notifno' => $request->notifno,
        //         'from' => $request->from
        //     ]
        // );


        // $options = array(
        //     'cluster' => 'ap1',
        //     'useTLS' => true
        // );

        // $pusher = new Pusher(
        //     env('PUSHER_APP_KEY'),
        //     env('PUSHER_APP_SECRET'),
        //     env('PUSHER_APP_ID'),
        //     $options
        // );

        // $data = [
        //     'assignedto' => $request->to,
        //     'notifno' => $request->notifno
        // ];

        // $pusher->trigger('my-channel', 'my-event', $data);

        // return response()->json(
        //     [
        //         'success' => true,
        //         'message' => 'Data inserted successfully'
        //     ]
        // );
        $this->CommentRepository->save($request->all());
    }
}
