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
                    <a class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" title="Back" href="{{ route('complaints.index') }}"> <i class="fas fa-backward"></i></a>
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

            <form action="{{ route('complaints.store') }}" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                @csrf
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#complainant" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                Complainant/s information
                            </button>
                        </h2>
                        <div id="complainant" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">

                                <div class="" id="dynamicComplainant">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreComplainant[0][firstname]" class="form-control firstname" placeholder="First Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreComplainant[0][middlename]" class="form-control middlename" placeholder="Middle Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreComplainant[0][lastname]" class="form-control lastname" placeholder="Last Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <select class="form-select" name="addMoreComplainant[0][sex]" aria-label="Floating label select example" style="width:101px;">
                                                <option value="" selected hidden>Sex</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text" name="addMoreComplainant[0][age]" class="form-control ml-3" placeholder="Age" style="width:72px;">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text" name="addMoreComplainant[0][address]" class="form-control" placeholder="Address">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <button type="button" name="addRespondent" id="addComplainant" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Add complainant">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#respondent" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                Respondent/s Information
                            </button>
                        </h2>
                        <div id="respondent" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <div class="" id="dynamicRespondent">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreRespondent[0][firstname]" class="form-control" placeholder="First Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreRespondent[0][middlename]" class="form-control" placeholder="Middle Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreRespondent[0][lastname]" class="form-control" placeholder="Last Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <select class="form-select" name="addMoreRespondent[0][sex]" id="floatingSelect" aria-label="Floating label select example" style="width:101px;">
                                                <option value="" selected hidden>Sex</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text" name="addMoreRespondent[0][age]" class="form-control ml-3" placeholder="Age" style="width:72px;">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text" name="addMoreRespondent[0][address]" class="form-control" placeholder="Address">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <button type="button" name="addRespondent" id="addRespondent" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Add respondent">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#lawviolated" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                Law/s Violated
                            </button>
                        </h2>
                        <div id="lawviolated" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <div class="" id="dynamicLawViolated">
                                    <div class="row">
                                        <div class="col-11 col-sm-11 col-md-11 col-lg-11">
                                            <!-- <div class="form-group">
                                                        <input type="text" name="addMoreLawViolated[0][lawviolated]"
                                                            class="form-control" placeholder="Law Violated">
                                                    </div> -->
                                            <select id="select2multipleCreate" class="selectMultiple" name="violations[]" multiple="multiple">
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
                    <div class="card mt-3">
                        <div class="card-boy mt-2">
                            <!-- <div class="row pr-2 pl-2 pt-2">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" name="NPSDNumber" class="form-control"
                                                placeholder="NPS DOCKET NO" value="{{ $NPSDOCKETNO }}"
                                                readonly="readonly">
                                            <label for="floatingNPSDNumber">NPS DOCKET NO</label>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <input type="text" name="FType" value="{{ $FType }}" hidden>
                            <div class="row pr-2 pl-2">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <select class="form-select" name="assignedto" id="assignedToId" aria-label="Floating label select example">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($prosecutors as $prosecutor)
                                                <option value="{{ $prosecutor->id }}">
                                                    {{ $prosecutor->firstname . ', ' . $prosecutor->middlename . ', ' . $prosecutor->lastname }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelect">Assigned To</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-5">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" name="placeofcommission" class="form-control" placeholder="Place Of Commission" value="{{ old('placeofcommission') }}">
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
                                        <input class="form-check-input" name="similar" type="checkbox" id="yesCheckedBox" value="Yes" onchange="similarYesCheckBox()">
                                        <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="similar" type="checkbox" id="noCheckedBox" value="No" onchange="similarNoCheckBox()">
                                        <label class="form-check-label" for="inlineCheckbox2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row pr-2 pl-2 pt-2">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-6">

                                    <P>2. Is this complaint in the nature of a counter-charge? If yes, indicate details
                                        below.</P>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="chargeYes" type="checkbox" id="yesCheckedBoxCC" value="Yes" onchange="checkedCheckBoxCC()">
                                        <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="chargeNo" type="checkbox" id="noCheckedBoxCC" value="No" onchange="noCheckBoxCC()">
                                        <label class="form-check-label" for="inlineCheckbox2">No</label>
                                    </div>

                                    <div class="form-group" id="counter-charge">
                                        <div class="form-floating">
                                            <input type="text" name="counterchargedetails" id="counterChargeDetails" class="form-control" placeholder="Details Here" value="{{ old('counterchargedetails') }}">
                                            <label for="floatingNPSDNumber">Details Here</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-6">

                                    <P>3. Is this complaint related to another case before this office? If yes, indicate
                                        details below.</P>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="complaintYes" type="checkbox" id="yesCheckBoxRC" value="Yes" onchange="checkedCheckBoxRC()">
                                        <label class="form-check-label" for="inlineCheckbox1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="complaintNo" type="checkbox" id="noCheckBoxRC" value="No" onchange="nocheckedCheckBoxRC()">
                                        <label class="form-check-label" for="inlineCheckbox2">No</label>
                                    </div>

                                    <div class="form-group" id="related-complaint">
                                        <div class="form-floating">
                                            <input type="text" name="relateddetails" id="relatedComplaintDetails" class="form-control" placeholder="Details Here" value="{{ old('relateddetails') }}">
                                            <label for="floatingNPSDNumber">Details Here</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#witness" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                Witness/es Information
                            </button>
                        </h2>
                        <div id="witness" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                            <div class="accordion-body">
                                <div class="" id="dynamicWitness">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreWitness[0][firstname]" class="form-control" placeholder="First Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreWitness[0][middlename]" class="form-control" placeholder="Middle Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                            <input type="text" name="addMoreWitness[0][lastname]" class="form-control" placeholder="Last Name">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <select class="form-select" name="addMoreWitness[0][sex]" id="floatingSelect" aria-label="Floating label select example" style="width:101px;">
                                                <option value="" selected hidden>Sex</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text" name="addMoreWitness[0][age]" class="form-control ml-3" placeholder="Age" style="width:72px;">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text" name="addMoreWitness[0][address]" class="form-control" placeholder="Address">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <button type="button" name="addWitness" id="addWitness" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Add witness">+</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#attachment" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                Attachment/s
                            </button>
                        </h2>
                        <div id="attachment" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <input type="file" class="form-control" name="files[]" id="customFile" accept=".pdf" multiple />
                                @error('files')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 text-center">
                    <button type="submit" class="btn btn-success btn_save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection