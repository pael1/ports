<?php

namespace App\Http\Controllers;

use Pusher\Pusher;
use App\Helpers\Helper;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\InvestigatedCase;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class InvestigatedCaseController extends Controller
{
    public function save(Request $request)
    {
        // if (InvestigatedCase::where('complaint_id', $request->complaint_id)->exists()) {
        $updateInvestigation =
            [
                // 'is_read' => 1,
                'days' => $request->days,
                'name' => $request->name,
                'assignedto' => $request->assignedto,
            ];
        InvestigatedCase::where([
            ['complaint_id', '=', $request->complaint_id]
        ])->update($updateInvestigation);
        // InvestigatedCase::where('complaint_id', $request->complaint_id)->update(array(['is_read' => 1, 'name' => $request->name]));
        // } else {
        //     DB::table('investigated_cases')->insert([
        //         'name' => $request->name,
        //         'days' => $request->days,
        //         'receivedby' => $request->receivedby,
        //         'complaint_id' => $request->complaint_id,
        //         'assignedto' => $request->assignedto,
        //         'is_read' => $request->is_read,
        //         'created_at' => Carbon::now(),
        //     ]);
        // }

        $alphabetC = range('A', 'L');
        $monthNumberC = Carbon::now()->month;
        $monthLetterC = $alphabetC[(int)$monthNumberC - 1];
        $yearC = Carbon::now()->format('y');
        $notifNo = Helper::NPSDOCKETNO(new Complaint, 'NPSDNumber', 5, 'NOTIF-' . $yearC . '-' . $monthLetterC);

        // DB::table('notifications')->insert([
        //     'assignedto' => $request->assignedto,
        //     'complaint_id' => $request->complaint_id,
        //     //1 means unread
        //     'markmsg' => 1,
        //     'notifno' => $notifNo
        // ]);
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
            'notifno' => $notifNo
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
            'notifno' => $notifNo
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
