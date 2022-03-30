@extends('layouts.appLTE')


@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add</h3>
        </div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="float-end p-2 mr-5">
                    <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
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


            <form action="{{ route('products.store') }}" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                @csrf


                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            <input type="text" name="name" value="{{$testformatedId}}" class="form-control" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Detail:</strong>
                            <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail"></textarea>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label for="files">File Upload</label>
                            <input id="files" type="file" name="files[]" class="form-control" accept=".pdf" multiple>
                            @error('files')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>


                <!-- <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="text" name="firstname" class="form-control" placeholder="First Name">
                                        <label for="floatingPassword">First Name</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-floating">
                                        <select class="form-select col-md-6" name="designation" id="floatingSelect" aria-label="Floating label select example">
                                            <option value="" disabled selected>Select</option>
                                            <option value="Reviewer">Reviewer</option>
                                            <option value="Online">Online</option>
                                            <option value="Manual">Manual</option>
                                            <option value="Chief">Chief</option>
                                        </select>
                                        <label for="floatingSelect">Designation</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="text" name="lastname" class="form-control" placeholder="Last Name">
                                        <label for="floatingPassword">Last Name</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="text" name="email" class="form-control" placeholder="Email">
                                        <label for="floatingPassword">Email</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-floating">
                                                <input type="text" name="middlename" class="form-control" placeholder="Middle Name">
                                                <label for="floatingPassword">Middle Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" name="add" id="add" class="btn btn-success">+</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-floating">
                                        <input type="text" name="middlename" class="form-control" placeholder="Middle Name">
                                        <label for="floatingPassword">Middle Name</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                Witness information
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">
                                <table class="table" id="dynamicTable">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <!-- <td><input type="text" name="addmore[0][name]" placeholder="Enter your Name" class="form-control" /></td> -->

                                        <td>
                                            <input type="text" name="addmore[0][firstname]" class="form-control" placeholder="First Name">
                                        </td>
                                        <td>
                                            <input type="text" name="addmore[0][lastname]" class="form-control" placeholder="Last Name">
                                        </td>
                                        <td>
                                            <input type="text" name="addmore[0][middlename]" class="form-control" placeholder="Middle Name">
                                        </td>
                                        <td style="width:125px;">
                                            <select class="form-select" name="designation" id="floatingSelect" aria-label="Floating label select example">
                                                <option value="" disabled selected>Sex</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="addmore[0][middlename]" class="form-control" placeholder="Age">
                                        </td>
                                        <td>
                                            <input type="text" name="addmore[0][middlename]" class="form-control" placeholder="Address">
                                        </td>

                                        <!-- <td><input type="text" name="addmore[0][qty]" placeholder="Enter your Qty" class="form-control" /></td> -->
                                        <!-- <td><input type="text" name="addmore[0][price]" placeholder="Enter your Price" class="form-control" /></td> -->
                                        <td><button type="button" name="add" id="add" class="btn btn-success">+</button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                Accordion Item #2
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                Accordion Item #3
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                            <div class="accordion-body">
                                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                </div>



            </form>
        </div>

    </div>
</div>
@endsection