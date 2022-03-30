<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Party;
use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Witness;
use App\Models\Complaint;
use App\Models\Respondent;
use App\Models\Complainant;
use App\Models\ViolatedLaw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDataRequest;
use App\Models\Attachment;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complaints = Complaint::all();
        return view('complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $year = Carbon::now()->format('y');
        $NPSDOCKETNO = Helper::NPSDOCKETNO(new Product, 'name', 5, 'XI-02-FType-' . $year);

        return view('complaints.create', compact('NPSDOCKETNO'));
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
            'formtype' => 'required',
            'assignedto' => 'required',
            'placeofcommission' => 'required',
            'similar' => 'required',
            'counterchargedetails' => 'required',
            'relateddetails' => 'required',
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
            'counterChargeDetails' => $request->counterchargedetails,
            'relatedComplaint' => 'static',
            'relatedDetails' => $request->relateddetails,
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
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
