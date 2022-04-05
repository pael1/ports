<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

class File extends Controller
{
    public function destroy($id){
   
        Attachment::find($id)->delete();
      
        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }
}
