<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
{{-- <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.js"></script>


<!-- DataTables  & Plugins -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- sweet alert success --}}
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: ' {{ session('
        success ') }}',
        showConfirmButton: false,
        timer: 2000
    })
</script>
@endif
@if (Request::is('complaints/create'))
@if ($FType == '')
<script>
    Swal.fire({
        html: '<b style="font-size:17px;">WHAT TYPE OF FORM DO YOU WANT TO CREATE?</b>',
        icon: 'question',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'rgb(99 151 64)',
        confirmButtonText: 'INVESTIGATION',
        cancelButtonText: 'INQUEST'
    }).then((result) => {
        if (result.isConfirmed) {
            document.location.href = "{{ route('complaints.create', ['formType' => 'INV']) }}";
        } else {
            document.location.href = "{{ route('complaints.create', ['formType' => 'INQ']) }}";
        }
    })
</script>
@endif
@endif
<script>
    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var complaintTable = $("#generalTable").DataTable({
            serverSide: true,
            processing: true,
            ajax: "{{ route('complaints.index') }}",
            columns: [{
                    data: 'NPSDNumber',
                    name: 'NPSDNumber'
                },
                {
                    data: 'receivedBy',
                    name: 'receivedBy'
                },
                {
                    data: 'name',
                    name: 'assignedTo'
                },
                {
                    data: 'dateFiled',
                    name: 'dateFiled'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });

        //show complaint/redirect to edit page of the complaint
        var id = "";
        $("body").on('click', '.editComplaint', function() {
            var id = $(this).data("id");
            let url = "{{ route('complaints.edit', ':id') }}";
            url = url.replace(':id', id);
            document.location.href = url;
        });

        //edit tables



        //datatables
        // $('#generalTable').DataTable({
        //     responsive: true
        // });

        //tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        //open modal
        $('.showModal').click(function() {
            var path = $(this).data('path');
            var filename = $(this).data('filename');

            $('#filePreviewLabel').html(filename);
            $('#iframe_file').attr('src', path);
            $('#iframe_file').attr('class', 'w-100 h-100');
        });

        //disabled all form
        $("#formId input").prop("disabled", true);
        // $("#floatingSelect").prop("disabled", true);
        $("#formId .add").hide();
        // $("#formId .form-control").prop("disabled", true);
        $("#formId .form-select").prop("disabled", true);
        $("#btnUpdate").hide();
        $("#disabledUpdateBtn").hide();

        $("#counter-charge").hide();
        $("#related-complaint").hide();

        //if naa ra sa edit disabled
        $("#select2multiple").prop("disabled", true);


        //counter charge checkbox
        if ($('#yesCheckedBoxCC').is(":checked")) {
            $("#counter-charge").show();
            $('#noCheckedBoxCC').prop('checked', false); // Unchecks it
        } else {
            $("#counter-charge").hide();
            $('#counterChargeDetails').val('');
        }

        //related checkbox
        if ($('#yesCheckBoxRC').is(":checked")) {
            $("#related-complaint").show();
            $('#noCheckBoxRC').prop('checked', false); // Unchecks it
        } else {
            $("#related-complaint").hide();
            $('#relatedComplaintDetails').val('');
        }


        //delete complaint
        $("body").on('click', '.deleteComplaint', function() {
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        // url: "/deleteComplaint/" + id,
                        url: "{{ url('deleteComplaint')}}" + '/' + id,
                        type: 'DELETE',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function() {
                            complaintTable.draw();
                            Swal.fire({
                                icon: 'success',
                                title: 'Successfully deleted',
                                showConfirmButton: false,
                                timer: 1500
                            })

                        }
                    });
                }
            })
        });

        $('.selectMultiple').select2({
            placeholder: "Select violation/s",
            width: '100%',
        });

        $('#lastnameComplainant').on('keyup', function() {
            const firstname = $('#firstnameComplainant').val();
            const middlename = $('#middlenameComplainant').val();
            const lastname = $(this).val();
            $.ajax({
                url: "{{ url('search') }}",
                type: 'GET',
                data: {
                    'firstname': firstname,
                    'middlename': middlename,
                    'lastname': lastname
                },
                success: function(data) {
                    console.log(data);
                }
            })
        });

    });

    $(".deleteParty").click(function() {
        var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");
        console.log(id);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    // url: "/party/" + id,
                    url: "{{ url('party')}}" + '/' + id,
                    type: 'DELETE',
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    success: function() {
                        $("#" + id + "").remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully deleted',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });
            }
        })
    });
    $(".deleteViolation").click(function() {
        var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");
        console.log(id);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    // url: "/violation/" + id,
                    url: "{{ url('violation')}}" + '/' + id,
                    type: 'DELETE',
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    success: function() {
                        $("#" + id + "").remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully deleted',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        // $( ".accordion-collapse" ).load(window.location.href + " .accordion-collapse" );
                    }
                });
            }
        })
    });

    $(".deleteAttachment").click(function() {
        var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    // url: "/attachments/" + id,
                    url: "{{ url('attachments')}}" + '/' + id,
                    type: 'DELETE',
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    success: function() {
                        $("#" + id + "").remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully deleted',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        // $("#attachmentsTable").load(window.location.href +
                        //     " #attachmentsTable");
                    }
                });
            }
        })
    });

    //similar checkbox
    function similarYesCheckBox() {
        if ($('#yesCheckedBox').is(":checked")) {
            $('#noCheckedBox').prop('checked', false); // Unchecks it
        }
    }

    function similarNoCheckBox() {
        if ($('#noCheckedBox').is(":checked")) {
            $('#yesCheckedBox').prop('checked', false); // Unchecks it
        }
    }

    //counter charge complaint
    function checkedCheckBoxCC() {
        if ($('#yesCheckedBoxCC').is(":checked")) {
            $("#counter-charge").show();
            $('#noCheckedBoxCC').prop('checked', false); // Unchecks it
        } else {
            $("#counter-charge").hide();
            $('#counterChargeDetails').val('');
        }
    }

    function noCheckBoxCC() {
        if ($('#noCheckedBoxCC').is(":checked")) {
            $("#counter-charge").hide();
            $('#yesCheckedBoxCC').prop('checked', false); // Unchecks it
            $('#counterChargeDetails').val('');
        }
    }

    //yes related complaint
    function checkedCheckBoxRC() {
        if ($('#yesCheckBoxRC').is(":checked")) {
            $("#related-complaint").show();
            $('#noCheckBoxRC').prop('checked', false); // Unchecks it
        } else {
            $("#related-complaint").hide();
            $('#relatedComplaintDetails').val('');
        }
    }

    //no related complaint
    function nocheckedCheckBoxRC() {
        if ($('#noCheckBoxRC').is(":checked")) {
            $("#related-complaint").hide();
            $('#yesCheckBoxRC').prop('checked', false); // Unchecks it
            $('#relatedComplaintDetails').val('');
        }
    }

    //enable update
    $("#enabledUpdateBtn").click(function() {
        $("#enabledUpdateBtn").hide();
        $("#disabledUpdateBtn").show();
        // $("#formId .form-control").prop("disabled", false);
        $("#formId .form-select").prop("disabled", false);
        $("#formId input").prop("disabled", false);
        $("#select2multiple").prop("disabled", false);
        $("#formId .add").show();
        $("#btnUpdate").show();
    });

    //disable update
    $("#disabledUpdateBtn").click(function() {
        $("#enabledUpdateBtn").show();
        // $("#formId .form-control").prop("disabled", true);
        $("#formId .form-select").prop("disabled", true);
        $("#formId input").prop("disabled", true);
        $("#select2multiple").prop("disabled", true);
        $("#formId .add").hide();
        $("#btnUpdate").hide();
        $("#disabledUpdateBtn").hide();
    });

    //add new fields for complainants
    var complainantIndex = 0;
    $("#addComplainant").click(function() {
        --complainantIndex;
        $("#dynamicComplainant").append('<div class="row mt-2">' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][firstname]"' +
            'class="form-control" placeholder="First Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][middlename]"' +
            'class="form-control" placeholder="Middle Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][lastname]"' +
            'class="form-control" placeholder="Last Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<select class="form-select" name="addMoreComplainant[' + complainantIndex +
            '][sex]" id="floatingSelect"' +
            'aria-label="Floating label select example" style="width:101px;">' +
            '<option value="" selected hidden>Sex</option>' +
            '<option value="Male">Male</option>' +
            '<option value="Female">Female</option>' +
            '</select>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"' +
            'name="addMoreComplainant[' + complainantIndex + '][age]" class="form-control ml-3"' +
            'placeholder="Age" style="width:72px;">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"' +
            'name="addMoreComplainant[' + complainantIndex + '][address]" class="form-control"' +
            'placeholder="Address">' +
            '<input type="text"' +
            'name="addMoreComplainant[' + complainantIndex + '][belongsTo]"  value="complainant" class="form-control"' +
            'placeholder="Address" hidden>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<button type="button" title="Remove" class="btn btn-danger btn-sm remove-data">-</button>' +
            '</div>' +
            '</div>');
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    //add new field for law violated
    // var lawViolatedIndex = 0;
    // $("#addLawViolated").click(function() {
    //     ++lawViolatedIndex;
    //     $("#dynamicLawViolated").append('<div class="row">' +
    //         '<div class="col-11 col-sm-11 col-md-11 col-lg-11">' +
    //         '<div class="form-group">' +
    //         '<input type="text" name="addMoreLawViolated[' + lawViolatedIndex +
    //         '][lawviolated]" class="form-control"' +
    //         'placeholder="Law Violated">' +
    //         '</div>' +
    //         '</div>' +
    //         '<div class="col-1 col-sm-1 col-md-1 col-lg-1">' +
    //         '<button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm remove-data">-</button>' +
    //         '</div>' +
    //         '</div>');
    //     $('[data-bs-toggle="tooltip"]').tooltip();
    // });

    //add new field for respondents
    var respondentIndex = 0;
    $("#addRespondent").click(function() {
        --respondentIndex;
        $("#dynamicRespondent").append('<div class="row mt-2">' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreRespondent[' + respondentIndex + '][firstname]"' +
            'class="form-control" placeholder="First Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreRespondent[' + respondentIndex + '][middlename]"' +
            'class="form-control" placeholder="Middle Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreRespondent[' + respondentIndex + '][lastname]"' +
            'class="form-control" placeholder="Last Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<select class="form-select" name="addMoreRespondent[' + respondentIndex +
            '][sex]" id="floatingSelect"' +
            'aria-label="Floating label select example" style="width:101px;">' +
            '<option value="" selected hidden>Sex</option>' +
            '<option value="Male">Male</option>' +
            '<option value="Female">Female</option>' +
            '</select>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"' +
            'name="addMoreRespondent[' + respondentIndex + '][age]" class="form-control ml-3"' +
            'placeholder="Age" style="width:72px;">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"' +
            'name="addMoreRespondent[' + respondentIndex + '][address]" class="form-control"' +
            'placeholder="Address">' +
            '<input type="text"' +
            'name="addMoreRespondent[' + respondentIndex + '][belongsTo]"  value="respondent" class="form-control"' +
            'placeholder="Address" hidden>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm remove-data">-</button>' +
            '</div>' +
            '</div>');
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    //add new field for witness
    var witnessIndex = 0;
    $("#addWitness").click(function() {
        --witnessIndex;
        $("#dynamicWitness").append('<div class="row mt-2">' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreWitness[' + witnessIndex + '][firstname]"' +
            'class="form-control" placeholder="First Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreWitness[' + witnessIndex + '][middlename]"' +
            'class="form-control" placeholder="Middle Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreWitness[' + witnessIndex + '][lastname]"' +
            'class="form-control" placeholder="Last Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<select class="form-select" name="addMoreWitness[' + witnessIndex +
            '][sex]" id="floatingSelect"' +
            'aria-label="Floating label select example" style="width:101px;">' +
            '<option value="" selected hidden>Sex</option>' +
            '<option value="Male">Male</option>' +
            '<option value="Female">Female</option>' +
            '</select>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"' +
            'name="addMoreWitness[' + witnessIndex + '][age]" class="form-control ml-3"' +
            'placeholder="Age" style="width:72px;">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"' +
            'name="addMoreWitness[' + witnessIndex + '][address]" class="form-control"' +
            'placeholder="Address">' +
            '<input type="text"' +
            'name="addMoreWitness[' + witnessIndex + '][belongsTo]"  value="witness" class="form-control"' +
            'placeholder="Address" hidden>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-danger btn-sm remove-data">-</button>' +
            '</div>' +
            '</div>');
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    //remove added field //general remove function
    $(document).on('click', '.remove-data', function() {
        $(this).closest("div.row").remove();
    });
</script>