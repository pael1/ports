<?php
//(Here the App\Repositories is the folder name)
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CommentRepository implements ICommentRepository
{
    public function getComment($complain_id)
    {
        $case = DB::table('comments')->where(['complaint_id' => $complain_id])->get();
        return $case;
    }
}