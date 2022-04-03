<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Party;
use App\Helpers\Helper;
// use App\Models\Product;
use App\Models\Complaint;
use App\Models\Attachment;
use App\Models\Prosecutor;
use App\Models\ViolatedLaw;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $complaints = Complaint::all();
        $complaints = DB::table('complaints')
        ->join('prosecutors', 'complaints.assignedTo', '=', 'prosecutors.id')
        ->select('complaints.*', 
        DB::raw("CONCAT(prosecutors.ext, ' ', prosecutors.firstname, ' ', prosecutors.middlename, ' ', prosecutors.lastname) as name"))->get();
        // dd($complaints);
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

        $FType = $request->input('formType');
        $alphabet = range('A', 'L');
        $monthNumber = Carbon::now()->month;
        $monthLetter = $alphabet[(int)$monthNumber - 1];
        $year = Carbon::now()->format('y');
        $NPSDOCKETNO = "";
        if ($FType != "") {
            $NPSDOCKETNO = Helper::NPSDOCKETNO(new Complaint, 'NPSDNumber', 5, 'XI-02-' . $FType . '-' . $year . '-' . $monthLetter);
        }

        return view('complaints.create', compact('NPSDOCKETNO', 'FType', 'prosecutors'));
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

        // $validator = Validator::make($request->all(), [
        //     'formtype' => 'required',
        //     'assignedto' => 'required',
        //     'placeofcommission' => 'required',
        //     'similar' => 'required',
        //     'counterchargedetails' => 'required',
        //     'relateddetails' => 'required',
        //     'files.*' => 'required|mimes:pdf|max:2000',

        //     // 'addMoreComplainant.*.firstname' => 'required',
        //     // 'addMoreComplainant.*.qty' => 'required',
        //     // 'addMoreComplainant.*.price' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->with('errorForm', $validator->errors()->getMessages())
        //         ->withInput();
        // }

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

        foreach ($request->addMoreComplainant as $complainant) {
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

        foreach ($request->addMoreRespondent as $respondent) {
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

        foreach ($request->addMoreWitness as $witness) {
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

        foreach ($request->addMoreLawViolated as $violatedLaw) {
            $violatedLaws = new ViolatedLaw([
                'details' => $violatedLaw['lawviolated'],
            ]);

            $complaints->violatedlaw()->save($violatedLaws);
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
            'id')
            ->pluck('name', 'id');
        $prosecutorId = Complaint::where('id',$id)->first()->assignedTo;

        $respondents = DB::table('parties')->where(['belongsTo' => 'respondent', 'complaint_id' => $id])->get();
        $complainants = DB::table('parties')->where(['belongsTo' => 'complainant', 'complaint_id' => $id])->get();
        $witnesses = DB::table('parties')->where(['belongsTo' => 'witness', 'complaint_id' => $id])->get();
        $lawviolated = DB::table('violated_laws')->where(['complaint_id' => $id])->get();
        $attachments = DB::table('attachments')
            ->select('filename', 'id', 'path', DB::raw("date_format(created_at, '%Y-%m-%d %r') AS created_at"))
            ->where('complaint_id', $id)
            ->get();
        return view('complaints.edit', compact('complaint', 'complainants', 'respondents', 'witnesses', 'lawviolated', 'attachments', 'prosecutors', 'prosecutorId'));
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
        //
        dd($request->all());
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
}
