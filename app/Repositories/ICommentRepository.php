<?php
//(Here the App\Repositories is the folder name)
namespace App\Repositories;

interface ICommentRepository
{
    public function getComment($complain_id);

    public function save(array $request);
 
}