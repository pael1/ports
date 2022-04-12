<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Party;
use App\Helpers\Helper;
use App\Models\Complaint;
use App\Models\Attachment;
use App\Models\Prosecutor;
use App\Models\ViolatedLaw;
use App\Models\Violation;
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
        // $complaints = Complaint::all();


        $complaints = DB::table('complaints')
            ->join('prosecutors', 'complaints.assignedTo', '=', 'prosecutors.id')
            ->select(
                'complaints.*',
                DB::raw("CONCAT(prosecutors.ext, ' ', prosecutors.firstname, ' ', prosecutors.middlename, ' ', prosecutors.lastname) as name, 
                DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled")
            )->get();
        // return view('complaints.index', compact('complaints'));

        // $complaints = Complaint::get();
        if ($request->ajax()) {
            $allData = DataTables::of($complaints)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Show complaint" class="btn btn-primary btn-sm editComplaint">View</a> ';
                    $btn .= '<a href="javascript:void(0)" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Show complaint" class="btn btn-danger btn-sm deleteComplaint">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
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
        $prosecutors = Prosecutor::all();
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

        $complaints = Complaint::create([
            'formType' => $request->formtype,
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
            foreach ($request->violations as $violatedLaw) {
                $violatedLaws = new ViolatedLaw([
                    'details' => $violatedLaw,
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
        // $prosecutors = Prosecutor::pluck('firstname', 'id');

        $prosecutors = Prosecutor::select(
            DB::raw("CONCAT(firstname,' ',middlename,'. ',lastname) AS name"),
            'id'
        )->pluck('name', 'id');
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
        return view('complaints.edit', compact('complaint', 'complainants', 'respondents', 'witnesses', 'lawviolated', 'attachments', 'prosecutors', 'prosecutorId', 'violations'));
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
        // dd($request->all());
        // $complaints = Complaint::updateOrCreate(
        $complaints = Complaint::updateOrCreate(
            [
                'id'   => $id,
            ],
            [
                'formType' => $request->formtype,
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
        if ($request->violations != "") {
            foreach ($request->violations as $violation) {
                $violations = new ViolatedLaw([
                    'details' => $violation
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
}
