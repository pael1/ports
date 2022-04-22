<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\User;
use App\Models\Party;
use App\Helpers\Helper;
use App\Models\Complaint;
use App\Models\Violation;
use App\Models\Attachment;
use App\Models\InvestigatedCase;
use App\Models\ViolatedLaw;
use App\Models\Notification;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// use DataTables;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    public function index(Request $request)
    {
        //reviewer = maam ivy monitoring and aging
        if (Auth::user()->designation == "Encoder") {
            $complaints = DB::table('complaints')
                ->join('users', 'complaints.assignedTo', '=', 'users.id')
                ->join('investigated_cases', 'complaints.id', '=', 'investigated_cases.complaint_id')
                ->select(
                    'complaints.*',
                    'investigated_cases.name',
                    DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) as fullname, 
                DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled")
                )->get();
        } else {
            $complaints = DB::table('complaints')
                ->join('users', 'complaints.assignedTo', '=', 'users.id')
                ->join('investigated_cases', 'complaints.id', '=', 'investigated_cases.complaint_id')
                ->join('notifications', 'complaints.id', '=', 'notifications.complaint_id')
                ->select(
                    'complaints.*',
                    'investigated_cases.name',
                    DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) as fullname, 
                DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled")
                    // )->where('complaints.assignedTo', '=', Auth::user()->id)->get();
                )->where('notifications.assignedto', '=', Auth::user()->id)->orderBy('complaints.id', 'desc')->get();
        }
        //else {
        //     $complaints = DB::table('complaints')
        //         ->join('investigated_cases', 'complaints.id', '=', 'investigated_cases.complaint_id')
        //         ->select(
        //             'complaints.*',
        //             'investigated_cases.name',
        //             // DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) as name, 
        //             DB::raw("investigated_cases.receivedby as fullname, 
        //         DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled")
        //         )->where('investigated_cases.assignedTo', '=', Auth::user()->id)->get();
        // }
        if ($request->ajax()) {
            $allData = DataTables::of($complaints)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Show complaint" class="btn btn-primary btn-sm editComplaint" id="' . $row->NPSDNumber . '">View</a> ';
                    $btn .= '<a href="javascript:void(0)" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Show complaint" class="btn btn-danger btn-sm deleteComplaint" id="' . $row->NPSDNumber . '">Delete</a>';
                    return $btn;
                })
                ->addColumn('name', function ($row) {
                    $className = ($row->name == "Pending") ? "bg-warning" : (($row->name == "Summary") ? "bg-info" : "bg-success");
                    // $name = ($row->name == "") ? "Pending" : $row->name;
                    $caseData = '<h5><span class="badge ' . $className . '">' . $row->name . '</span></h5>';
                    return $caseData;
                })
                ->rawColumns(['action', 'name'])
                ->make(true);
            return $allData;
        }
        return view('complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $prosecutors = User::select(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname) AS name"), 'id')
            ->where("designation", "=", "Fiscal")
            ->get();

        $violations = Violation::all();


        $FType = $request->input('formType');
        $alphabet = range('A', 'L');
        $monthNumber = Carbon::now()->month;
        $monthLetter = $alphabet[(int)$monthNumber - 1];
        $year = Carbon::now()->format('y');
        $NPSDOCKETNO = "";
        if ($FType != "") {
            $NPSDOCKETNO = Helper::NPSDOCKETNO(new Complaint, 'NPSDNumber', 5, 'XI-02-' . $FType . '-' . $year . '-' . $monthLetter);
        }

        return view('complaints.create', compact('NPSDOCKETNO', 'FType', 'prosecutors', 'violations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            // 'NPSDNumber' => 'required',
            // 'formtype' => 'required',
            'assignedto' => 'required',
            'placeofcommission' => 'required',
            // 'similar' => 'required',
            // 'counterchargedetails' => 'required',
            // 'relateddetails' => 'required',
            'files.*' => 'mimes:pdf|max:2000',

            // 'addMoreComplainant.*.name' => 'required',
            // 'addMoreComplainant.*.qty' => 'required',
            // 'addMoreComplainant.*.price' => 'required',

            // 'addMoreRespondent.*.name' => 'required',
            // 'addMoreRespondent.*.qty' => 'required',
            // 'addMoreRespondent.*.price' => 'required',

            // 'addMorewitness.*.name' => 'required',
            // 'addMorewitness.*.qty' => 'required',
            // 'addMorewitness.*.price' => 'required',
        ]);

        $alphabetC = range('A', 'L');
        $monthNumberC = Carbon::now()->month;
        $monthLetterC = $alphabetC[(int)$monthNumberC - 1];
        $yearC = Carbon::now()->format('y');
        $notifNo = Helper::NPSDOCKETNO(new Complaint, 'NPSDNumber', 5, 'NOTIF-' . $yearC . '-' . $monthLetterC);

        $complaints = Complaint::create([
            'formType' => $request->FType,
            'receivedBy' => Auth::user()->username,
            'assignedTo' => $request->assignedto,
            'violation' => 'Static',
            'placeofCommission' => $request->placeofcommission,
            'counterCharge' => 'static',
            'similar' => $request->similar,
            'counterChargeDetails' => ($request->counterchargedetails != "") ? $request->counterchargedetails : $request->chargeNo,
            'relatedComplaint' => 'static',
            'relatedDetails' => ($request->relateddetails != "") ? $request->relateddetails : $request->complaintNo,
            'NPSDNumber' => $notifNo
        ]);

        if ($request->addMoreComplainant != "") {
            foreach ($request->addMoreComplainant as $complainant) {
                if ($complainant['lastname'] != "") {
                    $complainants = new Party([
                        'lastName' => $complainant['lastname'],
                        'firstName' => $complainant['firstname'],
                        'middleName' => $complainant['middlename'],
                        'sex' => $complainant['sex'],
                        'age' => $complainant['age'],
                        'address' => $complainant['address'],
                        'belongsTo' => 'complainant'
                    ]);
                    $complaints->party()->save($complainants);
                }
            }
        }

        if ($request->addMoreRespondent != "") {
            foreach ($request->addMoreRespondent as $respondent) {
                if ($respondent['lastname'] != "") {
                    $respondents = new Party([
                        'lastName' => $respondent['lastname'],
                        'firstName' => $respondent['firstname'],
                        'middleName' => $respondent['middlename'],
                        'sex' => $respondent['sex'],
                        'age' => $respondent['age'],
                        'address' => $respondent['address'],
                        'belongsTo' => 'respondent'
                    ]);

                    $complaints->party()->save($respondents);
                }
            }
        }


        if ($request->addMoreWitness != "") {
            foreach ($request->addMoreWitness as $witness) {
                if ($witness['lastname'] != "") {
                    $witnesses = new Party([
                        'lastName' => $witness['lastname'],
                        'firstName' => $witness['firstname'],
                        'middleName' => $witness['middlename'],
                        'sex' => $witness['sex'],
                        'age' => $witness['age'],
                        'address' => $witness['address'],
                        'belongsTo' => 'witness'
                    ]);

                    $complaints->party()->save($witnesses);
                }
            }
        }
        if ($request->violations != "") {
            $FType = $request->FType;
            $alphabet = range('A', 'L');
            $monthNumber = Carbon::now()->month;
            $monthLetter = $alphabet[(int)$monthNumber - 1];
            $year = Carbon::now()->format('y');
            foreach ($request->violations as $violatedLaw) {
                $NPSDOCKETNO = Helper::NPSDOCKETNO(new ViolatedLaw(), 'docketNo', 5, 'XI-02-' . $FType . '-' . $year . '-' . $monthLetter);
                $violatedLaws = new ViolatedLaw([
                    'details' => $violatedLaw,
                    'docketNo' => $NPSDOCKETNO,
                ]);

                $complaints->violatedlaw()->save($violatedLaws);
            }
        }

        if ($request->exists('files')) {
            $images = $request->file('files');
            foreach ($images as $image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $filePath = $image->storeAs('uploads', $fileName, 'public');

                $fm = new Attachment([
                    'filename' => $fileName,
                    'path' => '/storage/' . $filePath
                ]);
                $complaints->attachment()->save($fm);
            }
        }

        $notifications = new Notification([
            'assignedto' => $request->assignedto,
            //1 means unread
            'markmsg' => 1,
            'notifno' => $notifNo,
            'from' => Auth::user()->username
        ]);
        $complaints->notification()->save($notifications);

        $case = new InvestigatedCase([
            'receivedby' => $request->assignedto,
            //status of case
            'name' => "Pending",
            'days' => Carbon::now(),
            'assignedto' => 0
        ]);
        $complaints->case()->save($case);

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
            'notifno' => $notifNo
        ];

        $pusher->trigger('my-channel', 'my-event', $data);

        return redirect()->route('complaints.index')->with('success', 'Created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $complaint = Complaint::find($id);

        $prosecutors = User::select(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname) AS name"), 'id')
            ->where("designation", "=", "Fiscal")
            ->pluck('name', 'id');

        $reviewerMTCC = User::select(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname) AS name"), 'id')
            ->where("designation", "=", "MTCC")
            ->pluck('name', 'id');
            
        $chief = User::select(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname) AS name"), 'id')
            ->where("designation", "=", "Chief")
            ->pluck('name', 'id');

        $monitoringReviewer = User::select(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname) AS name"), 'id')
            ->where("designation", "=", "monitoring")
            ->pluck('name', 'id');

        $reviewerRTC = User::select(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname) AS name"), 'id')
            ->where("designation", "=", "RTC")
            ->pluck('name', 'id');

        $violations = Violation::all();
        $prosecutorId = Complaint::where('id', $id)->first()->assignedTo;

        $respondents = DB::table('parties')->where(['belongsTo' => 'respondent', 'complaint_id' => $id])->get();
        $complainants = DB::table('parties')->where(['belongsTo' => 'complainant', 'complaint_id' => $id])->get();
        $witnesses = DB::table('parties')->where(['belongsTo' => 'witness', 'complaint_id' => $id])->get();
        $lawviolated = DB::table('violated_laws')->where(['complaint_id' => $id])->get();
        $attachments = DB::table('attachments')
            ->select('filename', 'id', 'path', DB::raw("date_format(created_at, '%Y-%m-%d %r') AS created_at"))
            ->where('complaint_id', $id)
            ->get();
        $case = DB::table('investigated_cases')->where(['complaint_id' => $id])->get();
        return view('complaints.edit', compact('complaint', 'complainants', 'respondents', 'witnesses', 'lawviolated', 'attachments', 'prosecutors', 'prosecutorId', 'violations', 'case', 'reviewerMTCC', 'reviewerRTC', 'monitoringReviewer', 'chief'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $complaints = Complaint::updateOrCreate(
        $complaints = Complaint::updateOrCreate(
            [
                'id'   => $id,
            ],
            [
                'formType' => $request->FType,
                'receivedBy' => Auth::user()->username,
                'assignedTo' => $request->assignedto,
                'violation' => 'Static',
                'placeofCommission' => $request->placeofcommission,
                'counterCharge' => 'static',
                'similar' => $request->similar,
                'counterChargeDetails' => ($request->counterchargedetails != "") ? $request->counterchargedetails : $request->chargeNo,
                'relatedComplaint' => 'static',
                'relatedDetails' => ($request->relateddetails != "") ? $request->relateddetails : $request->complaintNo,
                'NPSDNumber' => $request->NPSDNumber
            ]
        );
        //complainant
        if ($request->addMoreComplainant != "") {
            foreach ($request->addMoreComplainant as $complainant) {
                if (isset($complainant["id"])) {
                    $complainants =
                        [
                            'lastName' => $complainant['lastname'],
                            'firstName' => $complainant['firstname'],
                            'middleName' => $complainant['middlename'],
                            'sex' => $complainant['sex'],
                            'age' => $complainant['age'],
                            'address' => $complainant['address'],
                        ];
                    Party::where([
                        ['id', '=', $complainant["id"]],
                        ['complaint_id', '=', $id]
                    ])->update($complainants);
                } else {
                    $complainants = new Party([
                        'lastName' => $complainant['lastname'],
                        'firstName' => $complainant['firstname'],
                        'middleName' => $complainant['middlename'],
                        'sex' => $complainant['sex'],
                        'age' => $complainant['age'],
                        'address' => $complainant['address'],
                        'belongsTo' => $complainant['belongsTo']
                    ]);
                    $complaints->party()->save($complainants);
                }
            }
        }

        //violation
        $FType = $request->FType;
        $alphabet = range('A', 'L');
        $monthNumber = Carbon::now()->month;
        $monthLetter = $alphabet[(int)$monthNumber - 1];
        $year = Carbon::now()->format('y');
        if ($request->violations != "") {
            foreach ($request->violations as $violation) {
                $NPSDOCKETNO = Helper::NPSDOCKETNO(new ViolatedLaw(), 'docketNo', 5, 'XI-02-' . $FType . '-' . $year . '-' . $monthLetter);
                $violations = new ViolatedLaw([
                    'details' => $violation,
                    'docketNo' => $NPSDOCKETNO
                ]);
                $complaints->violatedlaw()->save($violations);
            }
        }

        //respondent
        if ($request->addMoreRespondent != "") {
            foreach ($request->addMoreRespondent as $respondent) {
                if (isset($respondent["id"])) {
                    $respondents =
                        [
                            'lastName' => $respondent['lastname'],
                            'firstName' => $respondent['firstname'],
                            'middleName' => $respondent['middlename'],
                            'sex' => $respondent['sex'],
                            'age' => $respondent['age'],
                            'address' => $respondent['address'],
                        ];
                    Party::where([
                        ['id', '=', $respondent["id"]],
                        ['complaint_id', '=', $id]
                    ])->update($respondents);
                } else {
                    $respondents = new Party([
                        'lastName' => $respondent['lastname'],
                        'firstName' => $respondent['firstname'],
                        'middleName' => $respondent['middlename'],
                        'sex' => $respondent['sex'],
                        'age' => $respondent['age'],
                        'address' => $respondent['address'],
                        'belongsTo' => $respondent['belongsTo']
                    ]);
                    $complaints->party()->save($respondents);
                }
            }
        }

        //witness
        if ($request->addMoreWitness != "") {
            foreach ($request->addMoreWitness as $witness) {
                if (isset($witness["id"])) {
                    $witnesses =
                        [
                            'lastName' => $witness['lastname'],
                            'firstName' => $witness['firstname'],
                            'middleName' => $witness['middlename'],
                            'sex' => $witness['sex'],
                            'age' => $witness['age'],
                            'address' => $witness['address'],
                        ];
                    Party::where([
                        ['id', '=', $witness["id"]],
                        ['complaint_id', '=', $id]
                    ])->update($witnesses);
                } else {
                    $witnesses = new Party([
                        'lastName' => $witness['lastname'],
                        'firstName' => $witness['firstname'],
                        'middleName' => $witness['middlename'],
                        'sex' => $witness['sex'],
                        'age' => $witness['age'],
                        'address' => $witness['address'],
                        'belongsTo' => $witness['belongsTo']
                    ]);
                    $complaints->party()->save($witnesses);
                }
            }
        }


        if ($request->exists('files')) {
            $images = $request->file('files');
            foreach ($images as $image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $filePath = $image->storeAs('uploads', $fileName, 'public');

                $fm = new Attachment([
                    'filename' => $fileName,
                    'path' => '/storage/' . $filePath
                ]);
                $complaints->attachment()->save($fm);
            }
        }

        return redirect()->route('complaints.edit', $id)->with('success', 'Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("complaints")->where("id", $id)->delete();
        DB::table("parties")->where("complaint_id", $id)->delete();
        DB::table("attachments")->where("complaint_id", $id)->delete();
        DB::table("violated_laws")->where("complaint_id", $id)->delete();

        return redirect()->route('complaints.index')->with('success', 'Deleted successfully!');
    }

    //delete complaint
    public function deleteComplaint($id)
    {
        DB::table("complaints")->where("id", $id)->delete();
        DB::table("parties")->where("complaint_id", $id)->delete();
        DB::table("attachments")->where("complaint_id", $id)->delete();
        DB::table("violated_laws")->where("complaint_id", $id)->delete();
        DB::table("notifications")->where("complaint_id", $id)->delete();
        DB::table("investigated_cases")->where("complaint_id", $id)->delete();

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    //delete specific id 
    public function deleteParty($id)
    {

        Party::find($id)->delete();

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    //delete violation
    public function deleteViolation($id)
    {

        ViolatedLaw::find($id)->delete();

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    public function autosearch(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('parties')
                ->join('complaints', 'parties.complaint_id', '=', 'complaints.id')
                ->select('parties.*', 'complaints.assignedTo')->where([
                    ['lastName', '=', $request->lastname],
                    ['firstName', '=', $request->firstname],
                    ['middleName', '=', $request->middlename]
                ])
                ->get();
            return $data;
        }
        return view('autosearch');
    }

    public function getComplaint_id(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('notifications')
                ->select('complaint_id')->where([
                    ['notifno', '=', $request->notifno],
                    ['assignedto', '=', Auth::user()->id]
                ])
                ->get();
            return $data;
        }
        return view('getComplaint_id');
    }

    // public function openNotif()
    // {
    //     // if (Auth::user()->designation != "Reviewer") {
    //         $complaint = DB::table('complaints')
    //             // ->join('users', 'complaints.receivedBy', '=', 'users.username')
    //             ->join('notifications', 'complaints.id', '=', 'notifications.complaint_id')
    //             ->join('users', 'notifications.from', '=', 'users.username')
    //             ->select(
    //                 'complaints.*',
    //                 'notifications.*',
    //                 DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) as name, 
    //         DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled, users.email")
    //             )->where('notifications.assignedto', '=', Auth::user()->id)->orderBy('complaints.id', 'desc')->get();
    //     // } else {
    //     //     $complaint = DB::table('complaints')
    //     //         ->join('investigated_cases', 'complaints.id', '=', 'investigated_cases.complaint_id')
    //     //         ->select(
    //     //             'complaints.*',
    //     //             DB::raw("investigated_cases.receivedby as name, 
    //     //         DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled, investigated_cases.is_read")
    //     //         )->where('investigated_cases.assignedTo', '=', Auth::user()->id)->get();
    //     // }
    //     return response()->json($complaint, 200);
    // }
}
