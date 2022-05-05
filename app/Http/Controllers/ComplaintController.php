<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Party;
use App\Models\Complaint;
use App\Models\Violation;
use App\Models\ViolatedLaw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDataRequest;
use App\Models\Report;
use App\Repositories\ICommentRepository;
use App\Repositories\IComplaint;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     //list of Repo variables
    protected $complaintRepository;
    protected $CommentRepository;

    //example of multiple __construct
    // public function __construct(IComplaint $complaintRepository, IComplaint1 $complaintRepository1)
    public function __construct(IComplaint $complaintRepository, ICommentRepository $CommentRepository)
    {
        //list of Repo global names
        $this->complaintRepository = $complaintRepository;
        $this->CommentRepository = $CommentRepository;
    }

    // public function index()
    public function index(Request $request)
    {
        $offices = $this->complaintRepository->getOffices();
        $complaints = $this->complaintRepository->all($request->filter);
        if ($request->ajax()) {
            $allData = $this->complaintRepository->fetchDataDataTables($complaints->all());
            return $allData;
        }
        return view('complaints.index', compact('complaints', 'offices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $prosecutors = $this->complaintRepository->getProsecutors();
        $offices = $this->complaintRepository->getOffices();
        $violations = Violation::all();
        $FType = $request->input('formType');
        return view('complaints.create', compact('FType', 'prosecutors', 'violations', 'offices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    public function store(StoreDataRequest $request)
    {
        if ($request['violations'] != "") {
            $request['violations'] = $request['violations'];
        } else {
            $request['violations'] = "";
        }
        if ($request->exists('files')) {
            $request['files'] = $request->file('files');
        } else {
            $request['files'] = "";
        }
        $this->complaintRepository->store($request->all());
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
        if(Auth::user()->designation != "Receiving"){
            $notifications = $this->complaintRepository->getNotification(Auth::user()->id, $id);
            if($notifications->isEmpty()){
                abort(404, 'not found');
            } 
        }
        $complaint = Complaint::find($id);
        $prosecutors = $this->complaintRepository->getUsers("Fiscal");
        $reviewerMTCC = $this->complaintRepository->getUsers("MTCC");
        $chief = $this->complaintRepository->getUsers("Chief");
        $monitoringReviewer = $this->complaintRepository->getUsers("monitoring");
        $reviewerRTC = $this->complaintRepository->getUsers("RTC");
        $encoder = $this->complaintRepository->getUsers("Encoder");
        $violations = Violation::all();
        $prosecutorId = Complaint::where('id', $id)->first()->assignedTo;
        $respondents = $this->complaintRepository->getParties("respondent", $id);
        $complainants = $this->complaintRepository->getParties("complainant", $id);
        $witnesses = $this->complaintRepository->getParties("witness", $id);
        $lawviolated = $this->complaintRepository->getViolatedLaws($id);
        $attachments = $this->complaintRepository->getAttachments($id);
        $case = $this->complaintRepository->getCases($id);
        $comments = $this->CommentRepository->getComment($id);
        return view('complaints.edit', compact('complaint', 'complainants', 'respondents', 'witnesses', 'lawviolated', 'attachments', 'prosecutors', 'prosecutorId', 'violations', 'case', 'reviewerMTCC', 'reviewerRTC', 'monitoringReviewer', 'chief', 'encoder', 'comments'));
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
        if ($request['addMoreComplainant'] != "") {
            $request['addMoreComplainant'] = $request['addMoreComplainant'];
        } else {
            $request['addMoreComplainant'] = "";
        }

        if ($request['addMoreRespondent'] != "") {
            $request['addMoreRespondent'] = $request['addMoreRespondent'];
        } else {
            $request['addMoreRespondent'] = "";
        }

        if ($request['addMoreWitness'] != "") {
            $request['addMoreWitness'] = $request['addMoreWitness'];
        } else {
            $request['addMoreWitness'] = "";
        }

        if ($request['violations'] != "") {
            $request['violations'] = $request['violations'];
        } else {
            $request['violations'] = "";
        }

        if ($request->exists('files')) {
            $request['files'] = $request->file('files');
        } else {
            $request['files'] = "";
        }

        $this->complaintRepository->update($id, $request->all());

        return redirect()->route('complaints.edit', $id)->with('success', 'Updated successfully!');
    }

    //delete complaint
    public function deleteComplaint($id)
    {
        $this->complaintRepository->deleteComplaint("complaints", "id", $id);
        $this->complaintRepository->deleteComplaint("parties", "complaint_id", $id);
        $this->complaintRepository->deleteComplaint("attachments", "complaint_id", $id);
        $this->complaintRepository->deleteComplaint("violated_laws", "complaint_id", $id);
        $this->complaintRepository->deleteComplaint("notifications", "complaint_id", $id);
        $this->complaintRepository->deleteComplaint("investigated_cases", "complaint_id", $id);
        $this->complaintRepository->deleteComplaint("comments", "complaint_id", $id);

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
            $data = $this->complaintRepository->checkRelatedParty($request->all());
            return $data;
        }
        return view('autosearch');
    }

    public function autosearchViolatedLaws(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->complaintRepository->checkViolatedLaw($request->all());
            return $data;
        }
        return view('autosearchViolatedLaws');
    }

    public function getComplaint_id(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->complaintRepository->getComplaintId($request->all());
            return $data;
        }
        return view('getComplaint_id');
    }

    public function exportpdf()
    {
        $complaints = DB::table('complaints')
            ->join('users', 'complaints.assignedTo', '=', 'users.id')
            ->join('investigated_cases', 'complaints.id', '=', 'investigated_cases.complaint_id')
            ->select(
                'complaints.*',
                'investigated_cases.name',
                DB::raw("CONCAT(users.firstname, ' ', users.middlename, ' ', users.lastname) as fullname, 
                DATE_FORMAT(complaints.created_at, '%d-%M-%y') as dateFiled")
            )->get();
        //directory
        $pdf = PDF::loadView('pdf.users', [
            'complaints' => $complaints
        ]);
        $filename = time() . '_' . 'test.pdf';
        $path = 'public/pdf/' . $filename;
        $complaints = Report::create([
            'filename' => $filename,
            'path' => '/storage/' . $path,
            'type' => 'type'
        ]);
        Storage::put('public/pdf/' . $filename, $pdf->output());
        return $pdf->stream();
        // return $pdf->stream($filename, array("Attachment" => false));
    }
}
