<?php
//(Here the App\Repositories is the folder name)
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CommentRepository implements ICommentRepository
{
    public function getComment($complain_id)
    {
        // $case = DB::table('comments')->where(['complaint_id' => $complain_id])->get();

        $case = DB::table('comments')
        ->join('users', 'users.id', '=', 'comments.from')
        ->select('comments.*', DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) as fullname"))->where(['comments.complaint_id' => $complain_id])->get();
        return $case;
    }
}