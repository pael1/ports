@extends('layouts.appLTE')


@section('content')
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create Complaint</h3>
            </div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="float-end p-2 mr-5">
                        <a class="btn btn-secondary btn-sm" href="{{ route('complaints.index') }}" data-bs-toggle="tooltip"
                            title="Back"> <i class="fas fa-backward"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('complaints.update', $complaint->id) }}" id="formId" method="POST"
                    accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="text-right">
                        <button type="button" id="enabledUpdateBtn" class="btn btn-info btn-sm">Enable Update</button>
                        <button type="button" id="disabledUpdateBtn" class="btn btn-danger btn-sm">Disable Update</button>
                    </div>
                    <div class="card">
                        <div class="card-boy">
                            <div class="row pr-2 pl-2 pt-2">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" name="NPSDNumber" class="form-control"
                                                placeholder="NPS DOCKET NO" value="{{ $complaint->NPSDNumber }}"
                                                readonly="readonly">
                                            <label for="floatingNPSDNumber">NPS DOCKET NO</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pr-2 pl-2">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <select class="form-select" name="assignedto" id="floatingSelect"
                                                aria-label="Floating label select example">

                                                @foreach ($prosecutors as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $key == $prosecutorId ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach

                                            </select>
                                            <label for="floatingSelect">Assigned To</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" name="placeofcommission" class="form-control"
                                                placeholder="Place Of Commission"
                                                value="{{ $complaint->placeofCommission }}">
                                            <label for="floatingplaceofCommissionr">Place Of Commission</label>
                                            @error('placeofcommission')
                                                <span class="text-danger" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                    <P>1. Has a Similar complaint been filed before any other office?</P>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="similar" type="checkbox" id="yesCheckedBox"
                                            value="Yes" onchange="similarYesCheckBox()"
                                            {{ $complaint->similar != 'No' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="similar" type="checkbox" id="noCheckedBox"
                                            value="No" onchange="similarNoCheckBox()"
                                            {{ $complaint->similar == 'No' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineCheckbox2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row pr-2 pl-2 pt-2">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-6">

                                    <P>2. Is this complaint in the nature of a counter-charge? If yes, indicate details
                                        below.</P>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="chargeYes" type="checkbox"
                                            id="yesCheckedBoxCC" value="Yes" onchange="checkedCheckBoxCC()"
                                            {{ $complaint->counterChargeDetails != 'No' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="chargeNo" type="checkbox" id="noCheckedBoxCC"
                                            value="No" onchange="noCheckBoxCC()"
                                            {{ $complaint->counterChargeDetails == 'No' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineCheckbox2">No</label>
                                    </div>

                                    <div class="form-group" id="counter-charge">
                                        <div class="form-floating">
                                            <input type="text" name="counterchargedetails"
                                                value="{{ $complaint->counterChargeDetails }}" id="counterChargeDetails"
                                                class="form-control" placeholder="Details Here"
                                                value="{{ old('counterchargedetails') }}">
                                            <label for="floatingNPSDNumber">Details Here</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-6">

                                    <P>3. Is this complaint related to another case before this office? If yes, indicate
                                        details below.</P>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="complaintYes" type="checkbox"
                                            id="yesCheckBoxRC" value="Yes" onchange="checkedCheckBoxRC()"
                                            {{ $complaint->relatedDetails != 'No' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="complaintNo" type="checkbox" id="noCheckBoxRC"
                                            value="No" onchange="nocheckedCheckBoxRC()"
                                            {{ $complaint->relatedDetails == 'No' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inlineCheckbox2">No</label>
                                    </div>

                                    <div class="form-group" id="related-complaint">
                                        <div class="form-floating">
                                            <input type="text" name="relateddetails"
                                                value="{{ $complaint->relatedDetails == 'No' ? '' : $complaint->relatedDetails }}"
                                                id="relatedComplaintDetails" class="form-control"
                                                placeholder="Details Here" value="{{ old('relateddetails') }}">
                                            <label for="floatingNPSDNumber">Details Here</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#complainant" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapseOne">
                                    Complainant/s information
                                </button>
                            </h2>
                            <div id="complainant" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingOne">
                                <div class="accordion-body">
                                    <div class="" id="dynamicComplainant">
                                        <div class="row mt-1">
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>FIRST NAME</b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>MIDDLE NAME</b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>LAST NAME</b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <b>SEX</b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <b>AGE</b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
                                                <b>ADDRESS</b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <button type="button" name="addRespondent" id="addComplainant"
                                                    data-bs-toggle="tooltip" title="Add complainant"
                                                    class="btn btn-success btn-sm add float-right">+</button>
                                            </div>
                                        </div>
                                        @if ($complainants->count())
                                            @foreach ($complainants as $value)
                                                <div class="row mt-2" id="{{ $value->id }}">
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text"
                                                            name="addMoreComplainant[{{ $value->id }}][complaint_id]"
                                                            value="{{ $value->complaint_id }}" hidden>
                                                        <input type="text"
                                                            name="addMoreComplainant[{{ $value->id }}][id]"
                                                            value="{{ $value->id }}" hidden>
                                                        <input type="text"
                                                            name="addMoreComplainant[{{ $value->id }}][belongsTo]"
                                                            value="{{ $value->belongsTo }}" hidden>
                                                        <input type="text"
                                                            name="addMoreComplainant[{{ $value->id }}][firstname]"
                                                            class="form-control" placeholder="First Name"
                                                            value="{{ $value->firstName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text"
                                                            name="addMoreComplainant[{{ $value->id }}][middlename]"
                                                            class="form-control" placeholder="Middle Name"
                                                            value="{{ $value->middleName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text"
                                                            name="addMoreComplainant[{{ $value->id }}][lastname]"
                                                            class="form-control" placeholder="Last Name"
                                                            value="{{ $value->lastName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <select class="form-select"
                                                            name="addMoreComplainant[{{ $value->id }}][sex]"
                                                            id="floatingSelect" aria-label="Floating label select example"
                                                            style="width:101px;">
                                                            <option value="" selected hidden>Sex</option>
                                                            <option value="Male"
                                                                {{ $value->sex == 'Male' ? 'selected' : '' }}>Male
                                                            </option>
                                                            <option value="Female"
                                                                {{ $value->sex == 'Female' ? 'selected' : '' }}>Female
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"
                                                            name="addMoreComplainant[{{ $value->id }}][age]"
                                                            class="form-control ml-3" placeholder="Age" style="width:72px;"
                                                            value="{{ $value->age }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"
                                                            name="addMoreComplainant[{{ $value->id }}][address]"
                                                            class="form-control" placeholder="Address"
                                                            value="{{ $value->address }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <button type="button" name="removeComplainant"
                                                            data-id="{{ $value->id }}" data-bs-toggle="tooltip"
                                                            title="Remove complainant"
                                                            class="btn btn-danger btn-sm add deleteParty">-</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mt-3 text-center">
                                                <p><i class="bi bi-file-earmark-x"></i> No Data.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#lawviolated" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseTwo">
                                    Law/s Violated
                                </button>
                            </h2>
                            <div id="lawviolated" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingTwo">
                                <div class="accordion-body">
                                    <div class="" id="dynamicLawViolated">
                                        <div class="row mt-1">
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-11 d-flex justify-content-center">
                                                <b>VIOLATION</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <!-- <button type="button" name="addRespondent" id="addLawViolated"
                                                        data-bs-toggle="tooltip" title="Add Violation"
                                                        class="btn btn-success btn-sm add float-right">+</button> -->
                                            </div>
                                        </div>
                                        @if ($lawviolated->count())
                                            @foreach ($lawviolated as $value)
                                                <div class="row" id="{{ $value->id }}">
                                                    <div class="col-11 col-sm-11 col-md-11 col-lg-11">
                                                        <div class="form-group">
                                                            <input type="text"
                                                                name="addMoreLawViolated[{{ $value->id }}][complaint_id]"
                                                                value="{{ $value->complaint_id }}" hidden>
                                                            <input type="text"
                                                                name="addMoreLawViolated[{{ $value->id }}][id]"
                                                                value="{{ $value->id }}" hidden>
                                                            <input type="text"
                                                                name="addMoreLawViolated[{{ $value->id }}][lawviolated]"
                                                                class="form-control" placeholder="Law Violated"
                                                                value="{{ $value->details.' ('.$value->docketNo .')'}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                                                        <button type="button" name="removeViolation"
                                                            data-id="{{ $value->id }}" data-bs-toggle="tooltip"
                                                            title="Remove Violation"
                                                            class="btn btn-danger btn-sm add deleteViolation">-</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mt-3 text-center">
                                                <p><i class="bi bi-file-earmark-x"></i> No Data.</p>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-1 col-sm-1 col-md-1 col-lg-11">
                                                <select id="select2multiple" class="selectMultiple" name="violations[]"
                                                    multiple="multiple">
                                                    @foreach ($violations as $violation)
                                                        <option value="{{ $violation->law }}">
                                                            {{ $violation->law }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#respondent" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseTwo">
                                    Respondent/s Information
                                </button>
                            </h2>
                            <div id="respondent" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingTwo">
                                <div class="accordion-body">
                                    <div class="" id="dynamicRespondent">
                                        <div class="row mt-1">
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>FIRST NAME</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>MIDDLE NAME</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>LAST NAME</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <b>SEX</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <b>AGE</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
                                                <b>ADDRESS</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <button type="button" name="addRespondent" id="addRespondent"
                                                    data-bs-toggle="tooltip" title="Add Respondent"
                                                    class="btn btn-success btn-sm add float-right">+</button>
                                            </div>
                                        </div>
                                        @if ($respondents->count())
                                            @foreach ($respondents as $value)
                                                <div class="row mt-2" id="{{ $value->id }}">
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text"
                                                            name="addMoreRespondent[{{ $value->id }}][complaint_id]"
                                                            value="{{ $value->complaint_id }}" hidden>
                                                        <input type="text"
                                                            name="addMoreRespondent[{{ $value->id }}][id]"
                                                            value="{{ $value->id }}" hidden>
                                                        <input type="text"
                                                            name="addMoreRespondent[{{ $value->id }}][belongsTo]"
                                                            value="{{ $value->belongsTo }}" hidden>
                                                        <input type="text"
                                                            name="addMoreRespondent[{{ $value->id }}][firstname]"
                                                            class="form-control" placeholder="First Name"
                                                            value="{{ $value->firstName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text"
                                                            name="addMoreRespondent[{{ $value->id }}][middlename]"
                                                            class="form-control" placeholder="Middle Name"
                                                            value="{{ $value->middleName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text"
                                                            name="addMoreRespondent[{{ $value->id }}][lastname]"
                                                            class="form-control" placeholder="Last Name"
                                                            value="{{ $value->lastName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <select class="form-select"
                                                            name="addMoreRespondent[{{ $value->id }}][sex]"
                                                            id="floatingSelect" aria-label="Floating label select example"
                                                            style="width:101px;">
                                                            <option value="" selected hidden>Sex</option>
                                                            <option value="Male"
                                                                {{ $value->sex == 'Male' ? 'selected' : '' }}>Male
                                                            </option>
                                                            <option value="Female"
                                                                {{ $value->sex == 'Female' ? 'selected' : '' }}>Female
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"
                                                            name="addMoreRespondent[{{ $value->id }}][age]"
                                                            class="form-control ml-3" placeholder="Age" style="width:72px;"
                                                            value="{{ $value->age }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"
                                                            name="addMoreRespondent[{{ $value->id }}][address]"
                                                            class="form-control" placeholder="Address"
                                                            value="{{ $value->address }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <button type="button" name="removeRespondent"
                                                            data-id="{{ $value->id }}" id="addRespondent1"
                                                            data-bs-toggle="tooltip" title="Remove respondent"
                                                            class="btn btn-danger btn-sm add deleteParty">-</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mt-3 text-center">
                                                <p><i class="bi bi-file-earmark-x"></i> No Data.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#witness" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseThree">
                                    Witness/es Information
                                </button>
                            </h2>
                            <div id="witness" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingThree">
                                <div class="accordion-body">
                                    <div class="" id="dynamicWitness">
                                        <div class="row mt-1">
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>FIRST NAME</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>MIDDLE NAME</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                                                <b>LAST NAME</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <b>SEX</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <b>AGE</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center">
                                                <b>ADDRESS</b>
                                            </div>
                                            <div
                                                class="col-xs-12 col-sm-6 col-md-4 col-lg-1 d-flex justify-content-center">
                                                <button type="button" name="addRespondent" id="addWitness"
                                                    data-bs-toggle="tooltip" title="Add Witness"
                                                    class="btn btn-success btn-sm add float-right">+</button>
                                            </div>
                                        </div>
                                        @if ($witnesses->count())
                                            @foreach ($witnesses as $value)
                                                <div class="row mt-2" id="{{ $value->id }}">
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreWitness[{{ $value->id }}][id]"
                                                            value="{{ $value->id }}" hidden>
                                                        <input type="text"
                                                            name="addMoreWitness[{{ $value->id }}][complaint_id]"
                                                            value="{{ $value->complaint_id }}" hidden>
                                                        <input type="text"
                                                            name="addMoreWitness[{{ $value->id }}][belongsTo]"
                                                            value="{{ $value->belongsTo }}" hidden>
                                                        <input type="text"
                                                            name="addMoreWitness[{{ $value->id }}][firstname]"
                                                            class="form-control" placeholder="First Name"
                                                            value="{{ $value->firstName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text"
                                                            name="addMoreWitness[{{ $value->id }}][middlename]"
                                                            class="form-control" placeholder="Middle Name"
                                                            value="{{ $value->middleName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text"
                                                            name="addMoreWitness[{{ $value->id }}][lastname]"
                                                            class="form-control" placeholder="Last Name"
                                                            value="{{ $value->lastName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <select class="form-select"
                                                            name="addMoreWitness[{{ $value->id }}][sex]"
                                                            id="floatingSelect" aria-label="Floating label select example"
                                                            style="width:101px;">
                                                            <option value="" selected hidden>Sex</option>
                                                            <option value="Male"
                                                                {{ $value->sex == 'Male' ? 'selected' : '' }}>Male
                                                            </option>
                                                            <option value="Female"
                                                                {{ $value->sex == 'Female' ? 'selected' : '' }}>Female
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"
                                                            name="addMoreWitness[{{ $value->id }}][age]"
                                                            class="form-control ml-3" placeholder="Age" style="width:72px;"
                                                            value="{{ $value->age }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"
                                                            name="addMoreWitness[{{ $value->id }}][address]"
                                                            class="form-control" placeholder="Address"
                                                            value="{{ $value->address }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <button type="button" name="removeWitness"
                                                            data-id="{{ $value->id }}" id="addWitness1"
                                                            data-bs-toggle="tooltip" title="Remove witness"
                                                            class="btn btn-danger btn-sm add deleteParty">-</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mt-3 text-center">
                                                <p><i class="bi bi-file-earmark-x"></i> No Data.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#attachment" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseTwo">
                                    Attachment/s
                                </button>
                            </h2>
                            <div id="attachment" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingTwo">
                                <div class="accordion-body">
                                    {{-- <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-6">
                                            @if ($attachments->count())
                                                <table class="table" id="attachmentsTable">
                                                    <thead>
                                                        <tr>
                                                            <th scope="">Filename</th>
                                                            <th scope="">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($attachments as $file)
                                                            <tr>
                                                                <td>{{ $file->filename }}</td>
                                <td class="text-right">
                                    <button type="button" data-path="{{ URL::asset($file->path) }}" data-filename="{{ $file->filename }}" data-toggle="modal" data-target="#filePreview" class="btn btn-primary showModal btn-sm">
                                        View
                                    </button>
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <button type="button" data-id="{{ $file->id }}" class="btn btn-danger btn-sm deleteAttachment">
                                        delete
                                    </button>
                                </td>
                                </tr>
                                @endforeach
                                </tbody>
                                </table>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                            </div>
                        </div> --}}
                                    @if ($attachments->count())
                                        @foreach ($attachments as $file)
                                            <div class="row mt-2" id="{{ $file->id }}">
                                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-8 text-center">
                                                    {{ $file->filename }}
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                                    <button type="button" data-path="{{ URL::asset($file->path) }}"
                                                        data-filename="{{ $file->filename }}" data-toggle="modal"
                                                        data-target="#filePreview" data-bs-toggle="tooltip" title="View"
                                                        class="btn btn-primary showModal btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                                    <button type="button" data-id="{{ $file->id }}"
                                                        data-bs-toggle="tooltip" title="Remove"
                                                        class="btn btn-danger add btn-sm deleteAttachment">
                                                        -
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="mt-3 text-center">
                                            <p><i class="bi bi-file-earmark-x"></i> No files uploaded.</p>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control add" name="files[]" id="customFile"
                                        accept=".pdf" multiple />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
                        <button type="submit" id="btnUpdate" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="filePreview" tabindex="-1" aria-labelledby="filePreviewLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="filePreviewLabel">Filename</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="iframe_file" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
