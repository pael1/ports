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
                        <a class="btn btn-secondary btn-sm" href="{{ route('complaints.index') }}"> Back</a>
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
                                                placeholder="NPS DOCKET NO" value="{{ $complaint->NPSDNumber }}">
                                            <label for="floatingNPSDNumber">NPS DOCKET NO</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pr-2 pl-2">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <select class="form-select" name="formtype" id="floatingSelect"
                                                aria-label="Floating label select example">
                                                <option value="" disabled selected>Select</option>
                                                <option value="INQ">INQ</option>
                                                <option value="INV">INV</option>
                                            </select>
                                            <label for="floatingSelect">Form Type</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <select class="form-select" name="assignedto" id="floatingSelect"
                                                aria-label="Floating label select example">

                                                @foreach ($prosecutors as $key => $value)
                                                <option value="{{ $key }}" {{ ( $key == $prosecutorId) ? 'selected' : '' }}> 
                                                    {{ $value }} 
                                                </option>
                                            @endforeach    

                                            </select>
                                            <label for="floatingSelect">Assigned To</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
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
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" name="similar" class="form-control" placeholder="Similar"
                                                value="{{ $complaint->similar }}">
                                            <label for="floatingplaceofCommissionr">Similar</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row pr-2 pl-2 pt-2">
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-6">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" name="counterchargedetails" class="form-control"
                                                placeholder="Counter Charge Details"
                                                value="{{ $complaint->counterChargeDetails }}">
                                            <label for="floatingNPSDNumber">Counter Charge Details</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-6">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" name="relateddetails" class="form-control"
                                                placeholder="Related Details" value="{{ $complaint->relatedDetails }}">
                                            <label for="floatingNPSDNumber">Related Details</label>
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
                                        @if ($attachments->count())
                                            @foreach ($complainants as $value)
                                                <div class="row mt-3">
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][firstname]"
                                                            class="form-control" placeholder="First Name"
                                                            value="{{ $value->firstName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][lastname]"
                                                            class="form-control" placeholder="Last Name"
                                                            value="{{ $value->lastName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][middlename]"
                                                            class="form-control" placeholder="Middle Name"
                                                            value="{{ $value->middleName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <select class="form-select" name="addMoreComplainant[0][sex]"
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
                                                            name="addMoreComplainant[0][age]" class="form-control ml-3"
                                                            placeholder="Age" style="width:72px;"
                                                            value="{{ $value->age }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"
                                                            name="addMoreComplainant[0][address]" class="form-control"
                                                            placeholder="Address" value="{{ $value->address }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <button type="button" name="addRespondent" id="addComplainant"
                                                            class="btn btn-danger">-</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mt-3 text-center">
                                                <p><i class="bi bi-file-earmark-x"></i> No Data.</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- <table class="table" id="dynamicTableComplainant">
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                        </tr>
                                        <tr>
                                            <!-- <td><input type="text" name="addMoreComplainant[0][name]" placeholder="Enter your Name" class="form-control" /></td> -->
                                            <td>
                                                <input type="text" name="addMoreComplainant[0][firstname]"
                                                    class="form-control" placeholder="First Name">
                                            </td>
                                            <td>
                                                <input type="text" name="addMoreComplainant[0][lastname]"
                                                    class="form-control" placeholder="Last Name">
                                            </td>
                                            <td>
                                                <input type="text" name="addMoreComplainant[0][middlename]"
                                                    class="form-control" placeholder="Middle Name">
                                            </td>
                                            <td style="width:125px;">
                                                <select class="form-select" name="designation" id="floatingSelect"
                                                    aria-label="Floating label select example">
                                                    <option value="" disabled selected>Sex</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </td>
                                            <td style="width:80px;">
                                                <input type="text" name="addMoreComplainant[0][age]" class="form-control"
                                                    placeholder="Age">
                                            </td>
                                            <td style="width:329px;">
                                                <input type="text" name="addMoreComplainant[0][address]"
                                                    class="form-control" placeholder="Address">
                                            </td>
                                            <!-- <td><input type="text" name="addMoreComplainant[0][qty]" placeholder="Enter your Qty" class="form-control" /></td> -->
                                            <!-- <td><input type="text" name="addMoreComplainant[0][price]" placeholder="Enter your Price" class="form-control" /></td> -->
                                            <td><button type="button" name="add" id="add" class="btn btn-success">+</button>
                                            </td>
                                        </tr>
                                    </table> --}}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#lawviolated" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseTwo">
                                    Law/s Violated
                                </button>
                            </h2>
                            <div id="lawviolated" class="accordion-collapse collapse"
                                aria-labelledby="panelsStayOpen-headingTwo">
                                <div class="accordion-body">
                                    <div class="" id="dynamicLawViolated">
                                        @if ($lawviolated->count())
                                            @foreach ($lawviolated as $value)
                                                <div class="row">
                                                    <div class="col-11 col-sm-11 col-md-11 col-lg-11">
                                                        <div class="form-group">
                                                            <input type="text" name="addMoreLawViolated[0][lawviolated]"
                                                                class="form-control" placeholder="Law Violated"
                                                                value="{{ $value->details }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-1 col-sm-1 col-md-1 col-lg-1">
                                                        <button type="button" name="addLawViolated" id="addLawViolated"
                                                            class="btn btn-danger">-</button>
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
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#respondent" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseTwo">
                                    Respondent/s Information
                                </button>
                            </h2>
                            <div id="respondent" class="accordion-collapse collapse"
                                aria-labelledby="panelsStayOpen-headingTwo">
                                <div class="accordion-body">
                                    <div class="" id="dynamicRespondent">
                                        @if ($attachments->count())
                                            @foreach ($respondents as $value)
                                                <div class="row mt-3">
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][firstname]"
                                                            class="form-control" placeholder="First Name"
                                                            value="{{ $value->firstName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][lastname]"
                                                            class="form-control" placeholder="Last Name"
                                                            value="{{ $value->lastName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][middlename]"
                                                            class="form-control" placeholder="Middle Name"
                                                            value="{{ $value->middleName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <select class="form-select" name="addMoreComplainant[0][sex]"
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
                                                            name="addMoreComplainant[0][age]" class="form-control ml-3"
                                                            placeholder="Age" style="width:72px;"
                                                            value="{{ $value->age }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"
                                                            name="addMoreComplainant[0][address]" class="form-control"
                                                            placeholder="Address" value="{{ $value->address }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <button type="button" name="addRespondent" id="addComplainant"
                                                            class="btn btn-danger">-</button>
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
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#witness" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseThree">
                                    Witness/es Information
                                </button>
                            </h2>
                            <div id="witness" class="accordion-collapse collapse"
                                aria-labelledby="panelsStayOpen-headingThree">
                                <div class="accordion-body">
                                    <div class="" id="dynamicWitness">
                                        @if ($attachments->count())
                                            @foreach ($witnesses as $value)
                                                <div class="row mt-3">
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][firstname]"
                                                            class="form-control" placeholder="First Name"
                                                            value="{{ $value->firstName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][lastname]"
                                                            class="form-control" placeholder="Last Name"
                                                            value="{{ $value->lastName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                                                        <input type="text" name="addMoreComplainant[0][middlename]"
                                                            class="form-control" placeholder="Middle Name"
                                                            value="{{ $value->middleName }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <select class="form-select" name="addMoreComplainant[0][sex]"
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
                                                            name="addMoreComplainant[0][age]" class="form-control ml-3"
                                                            placeholder="Age" style="width:72px;"
                                                            value="{{ $value->age }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"
                                                            name="addMoreComplainant[0][address]" class="form-control"
                                                            placeholder="Address" value="{{ $value->address }}">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                                        <button type="button" name="addRespondent" id="addComplainant"
                                                            class="btn btn-danger">-</button>
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
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#attachment" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseTwo">
                                    Attachment/s
                                </button>
                            </h2>
                            <div id="attachment" class="accordion-collapse collapse"
                                aria-labelledby="panelsStayOpen-headingTwo">
                                <div class="accordion-body">
                                    @if ($attachments->count())
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="">Filename</th>
                                                    <th scope="" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($attachments as $file)
                                                    <tr>
                                                        <td>{{ $file->filename }}</td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                data-path="{{ URL::asset($file->path) }}"
                                                                data-filename="{{ $file->filename }}" data-toggle="modal"
                                                                data-target="#filePreview"
                                                                class="btn btn-primary showModal btn-sm">
                                                                View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="mt-3 text-center">
                                            <p><i class="bi bi-file-earmark-x"></i> No files uploaded.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
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
