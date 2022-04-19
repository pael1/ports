<?php

namespace App\Http\Controllers;

use App\Models\InvestigatedCase;
use Pusher\Pusher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvestigatedCaseController extends Controller
{
    public function save(Request $request)
    {
        DB::table('investigated_cases')->insert([
            'name' => $request->name,
            'days' => $request->days,
            'receivedby' => $request->receivedby,
            'complaint_id' => $request->complaint_id,
            'assignedto' => $request->assignedto,
            'is_read' => $request->is_read,
            'created_at' => Carbon::now(),
        ]);

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
            'assignedto' => $request->assignedto,
            'complaint_id' => $request->complaint_id,
            'admin' => 'yes'
        ];

        $pusher->trigger('my-channel', 'my-event', $data);

        return response()->json(
            [
                'success' => true,
                'message' => 'Data inserted successfully'
            ]
        );
    }

    public function updateNotif($complaint_id)
    {
        InvestigatedCase::where('complaint_id', $complaint_id)->update(array('is_read' => 0));
    }
}
