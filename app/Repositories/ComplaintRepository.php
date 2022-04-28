<?php
//(Here the App\Repositories is the folder name)
namespace App\Repositories;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\User;
use App\Models\Party;
use App\Helpers\Helper;
use App\Models\Complaint;
use App\Models\Attachment;
use App\Models\ViolatedLaw;
use App\Models\Notification;
use App\Models\InvestigatedCase;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ComplaintRepository implements IComplaint
{
    //To view all the data
    public function all()
    {
        //reviewer = maam ivy monitoring and aging
        if (Auth::user()->designation == "Receiving") {
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

        return $complaints;
    }
    //fetch data to datatables
    public function fetchDataDataTables(array $complaints)
    {
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

    //Get an individual record
    public function get($id)
    {
        return Complaint::find($id);
    }
    //Store the data
    // public function store(array $request, $notifNo)
    public function store(array $request)
    {
        $alphabetC = range('A', 'L');
        $monthNumberC = Carbon::now()->month;
        $monthLetterC = $alphabetC[(int)$monthNumberC - 1];
        $yearC = Carbon::now()->format('y');
        $notifNo = Helper::NPSDOCKETNO(new Complaint, 'NPSDNumber', 5, 'NOTIF-' . $yearC . '-' . $monthLetterC);


        $complaints = Complaint::create([
            'formType' => $request['FType'],
            'receivedBy' => Auth::user()->username,
            'assignedTo' => $request['assignedto'],
            'violation' => 'Static',
            'placeofCommission' => $request['placeofcommission'],
            'counterCharge' => 'static',
            'similar' => $request['similar'],
            'counterChargeDetails' => ($request['counterchargedetails'] != "") ? $request['counterchargedetails'] : $request['chargeNo'],
            'relatedComplaint' => 'static',
            'relatedDetails' => ($request['relateddetails'] != "") ? $request['relateddetails'] : $request['complaintNo'],
            'NPSDNumber' => $notifNo
        ]);

        if ($request['addMoreComplainant'] != "") {
            foreach ($request['addMoreComplainant'] as $complainant) {
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

        if ($request['addMoreRespondent'] != "") {
            foreach ($request['addMoreRespondent'] as $respondent) {
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


        if ($request['addMoreWitness'] != "") {
            foreach ($request['addMoreWitness'] as $witness) {
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
        if ($request['violations'] != "") {
            $FType = $request['FType'];
            $alphabet = range('A', 'L');
            $monthNumber = Carbon::now()->month;
            $monthLetter = $alphabet[(int)$monthNumber - 1];
            $year = Carbon::now()->format('y');
            foreach ($request['violations'] as $violatedLaw) {
                $NPSDOCKETNO = Helper::NPSDOCKETNO(new ViolatedLaw(), 'docketNo', 5, 'XI-02-' . $FType . '-' . $year . '-' . $monthLetter);
                $violatedLaws = new ViolatedLaw([
                    'details' => $violatedLaw,
                    'docketNo' => $NPSDOCKETNO,
                ]);

                $complaints->violatedlaw()->save($violatedLaws);
            }
        }

        if ($request['files'] != "") {
            $attachments = $request['files'];
            foreach ($attachments as $image) {
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
            'assignedto' => $request['assignedto'],
            //1 means unread
            'markmsg' => 1,
            'notifno' => $notifNo,
            'from' => Auth::user()->username
        ]);
        $complaints->notification()->save($notifications);

        $case = new InvestigatedCase([
            'receivedby' => $request['assignedto'],
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
            'assignedto' => $request['assignedto'],
            'notifno' => $notifNo
        ];

        $pusher->trigger('my-channel', 'my-event', $data);
    }
    //get all fiscals in table
    public function getProsecutors()
    {
        $prosecutors = User::select(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname) AS name"), 'id')
            ->where("designation", "=", "Fiscal")
            ->get();
        return $prosecutors;
    }

    //get users
    public function getUsers($designation)
    {
        $users = User::select(DB::raw("CONCAT(firstname,' ',middlename,' ',lastname) AS name"), 'id')
            ->where("designation", "=", $designation)
            ->pluck('name', 'id');
        return $users;
    }

    //get parties
    public function getParties($party, $id)
    {
        $parties = DB::table('parties')->where(['belongsTo' => $party, 'complaint_id' => $id])->get();
        return $parties;
    }

    //get violated laws
    public function getViolatedLaws($id)
    {
        $lawviolated = DB::table('violated_laws')->where(['complaint_id' => $id])->get();
        return $lawviolated;
    }

    //get attachments
    public function getAttachments($id)
    {
        $attachments = DB::table('attachments')
            ->select('filename', 'id', 'path', DB::raw("date_format(created_at, '%Y-%m-%d %r') AS created_at"))
            ->where('complaint_id', $id)
            ->get();
        return $attachments;
    }

    //get cases
    public function getCases($id)
    {
        $case = DB::table('investigated_cases')->where(['complaint_id' => $id])->get();
        return $case;
    }

    //Update the data
    public function update($id, array $request)
    {
        // dd($request);
        $complaints = Complaint::updateOrCreate(
            [
                'id'   => $id,
            ],
            [
                'formType' => $request['FType'],
                'receivedBy' => Auth::user()->username,
                'assignedTo' => $request['assignedto'],
                'violation' => 'Static',
                'placeofCommission' => $request['placeofcommission'],
                'counterCharge' => 'static',
                'similar' => $request['similar'],
                'counterChargeDetails' => ($request['counterchargedetails'] != "") ? $request['counterchargedetails'] : $request['chargeNo'],
                'relatedComplaint' => 'static',
                'relatedDetails' => ($request['relateddetails'] != "") ? $request['relateddetails'] : $request['complaintNo'],
                'NPSDNumber' => $request['NPSDNumber']
            ]
        );
        //complainant
        if ($request['addMoreComplainant'] != "") {
            foreach ($request['addMoreComplainant'] as $complainant) {
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
        $FType = $request['FType'];
        $alphabet = range('A', 'L');
        $monthNumber = Carbon::now()->month;
        $monthLetter = $alphabet[(int)$monthNumber - 1];
        $year = Carbon::now()->format('y');
        if ($request['violations'] != "") {
            foreach ($request['violations'] as $violation) {
                $NPSDOCKETNO = Helper::NPSDOCKETNO(new ViolatedLaw(), 'docketNo', 5, 'XI-02-' . $FType . '-' . $year . '-' . $monthLetter);
                $violations = new ViolatedLaw([
                    'details' => $violation,
                    'docketNo' => $NPSDOCKETNO
                ]);
                $complaints->violatedlaw()->save($violations);
            }
        }

        //respondent
        if ($request['addMoreRespondent'] != "") {
            foreach ($request['addMoreRespondent'] as $respondent) {
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
        if ($request['addMoreWitness'] != "") {
            foreach ($request['addMoreWitness'] as $witness) {
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


        if ($request['files'] != "") {
            $attachments = $request['files'];
            foreach ($attachments as $image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $filePath = $image->storeAs('uploads', $fileName, 'public');

                $fm = new Attachment([
                    'filename' => $fileName,
                    'path' => '/storage/' . $filePath
                ]);
                $complaints->attachment()->save($fm);
            }
        }
    }

    //Delete the data
    public function deleteComplaint($tableName, $tableFieldName, $id)
    {
        DB::table($tableName)->where($tableFieldName, $id)->delete();
    }

    //check related party
    public function checkRelatedParty(array $request)
    {
        $data = DB::table('parties')
            ->join('complaints', 'parties.complaint_id', '=', 'complaints.id')
            ->select('parties.*', 'complaints.assignedTo')->where([
                ['lastName', '=', $request['lastname']],
                ['firstName', '=', $request['firstname']],
                ['middleName', '=', $request['middlename']],
                ['belongsTo', '=', $request['type']]
            ])->get();
        return $data;
    }

    //get complaint Id
    public function getComplaintId(array $request)
    {
        $data = DB::table('notifications')
            ->select('complaint_id')->where([
                ['notifno', '=', $request['notifno']],
                ['assignedto', '=', Auth::user()->id]
            ])
            ->get();
        return $data;
    }
}
