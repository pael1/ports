<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
{{-- <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

<!-- DataTables  & Plugins -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- sweet alert success --}}
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: ' {{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        })
    </script>
{{-- @elseif(session('errorForm'))
    <script>
        Swal.fire({
            icon: 'error',
            title: ' {{ session('console.errorForm;') }}',
            showConfirmButton: false,
            timer: 2000
        })
    </script> --}}
@endif
<script>
    $(function() {
        $('#example1').DataTable();

        $('.showModal').click(function() {
            var path = $(this).data('path');
            var filename = $(this).data('filename');

            $('#filePreviewLabel').html(filename);
            $('#iframe_file').attr('src', path);
            $('#iframe_file').attr('class', 'w-100 h-100');

            // $('#filePreview').modal('show');
        });

        // $('.closeFileModal').click(function() {
        //     $('#filePreview').modal('hide');
        // });
    });

    //add new fields for complainants
    var complainantIndex = 0;
    $("#addComplainant").click(function() {
        ++complainantIndex;
        $("#dynamicComplainant").append('<div class="row mt-3">' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][firstname]"' +
            'class="form-control" placeholder="First Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][lastname]"' +
            'class="form-control" placeholder="Last Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][middlename]"' +
            'class="form-control" placeholder="Middle Name">' +
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
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<button type="button" class="btn btn-danger remove-data">-</button>' +
            '</div>' +
            '</div>');
    });

    //law violated
    var lawViolatedIndex = 0;
    $("#addLawViolated").click(function() {
        ++lawViolatedIndex;
        $("#dynamicLawViolated").append('<div class="row mt-3">' +
            '<div class="col-11 col-sm-11 col-md-11 col-lg-11">' +
            '<div class="form-group">' +
            '<input type="text" name="addMoreLawViolated[' + lawViolatedIndex +
            '][lawviolated]" class="form-control"' +
            'placeholder="Law Violated">' +
            '</div>' +
            '</div>' +
            '<div class="col-1 col-sm-1 col-md-1 col-lg-1">' +
            '<button type="button" class="btn btn-danger remove-data">-</button>' +
            '</div>' +
            '</div>');
    });

    //respondents
    var respondentIndex = 0;
    $("#addRespondent").click(function() {
        ++respondentIndex;
        $("#dynamicRespondent").append('<div class="row mt-3">' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreRespondent[' + respondentIndex + '][firstname]"' +
            'class="form-control" placeholder="First Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreRespondent[' + respondentIndex + '][lastname]"' +
            'class="form-control" placeholder="Last Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreRespondent[' + respondentIndex + '][middlename]"' +
            'class="form-control" placeholder="Middle Name">' +
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
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<button type="button" class="btn btn-danger remove-data">-</button>' +
            '</div>' +
            '</div>');
    });

    //witness
    var witnessIndex = 0;
    $("#addWitness").click(function() {
        ++witnessIndex;
        $("#dynamicWitness").append('<div class="row mt-3">' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreWitness[' + witnessIndex + '][firstname]"' +
            'class="form-control" placeholder="First Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreWitness[' + witnessIndex + '][lastname]"' +
            'class="form-control" placeholder="Last Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreWitness[' + witnessIndex + '][middlename]"' +
            'class="form-control" placeholder="Middle Name">' +
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
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<button type="button" class="btn btn-danger remove-data">-</button>' +
            '</div>' +
            '</div>');
    });

    // $("#add").click(function() {
    //     ++i;
    //     $("#dynamicTableComplainant").append('<tr>' +
    //         '<td>' +
    //         '<input type="text" name="addMoreComplainant[' + i + '][firstname]" class="form-control" placeholder="First Name">' +
    //         '</td>' +
    //         '<td>' +
    //         '<input type="text" name="addMoreComplainant[' + i + '][lastname]" class="form-control" placeholder="Last Name">' +
    //         '</td>' +
    //         '<td>' +
    //         '<input type="text" name="addMoreComplainant[' + i + '][middlename]" class="form-control" placeholder="Middle Name">' +
    //         '</td>' +
    //         '<td style="width:125px;">' +
    //         '<select class="form-select" name="addMoreComplainant[' + i + '][sex]" id="floatingSelect" aria-label="Floating label select example">' +
    //         '<option value="" disabled selected>Sex</option>' +
    //         '<option value="Male">Male</option>' +
    //         '<option value="Female">Female</option>' +
    //         '</select>' +
    //         '</td>' +
    //         '<td>' +
    //         '<input type="text" name="addMoreComplainant[' + i + '][age]" class="form-control" placeholder="Age">' +
    //         '</td>' +
    //         '<td>' +
    //         '<input type="text" name="addMoreComplainant[' + i + '][address]" class="form-control" placeholder="Address">' +
    //         '</td>' +
    //         '<td><button type="button" class="btn btn-danger remove-tr">-</button></td>'+
    //         '</tr>');
    // });

    // '<td><input type="text" name="addMoreComplainant[' + i + '][name]" placeholder="Enter your Name" class="form-control" /></td>' +
    //     '<td><input type="text" name="addMoreComplainant[' + i + '][qty]" placeholder="Enter your Qty" class="form-control" /></td>' +
    //     '<td><input type="text" name="addMoreComplainant[' + i + '][price]" placeholder="Enter your Price" class="form-control" /></td>' +

    // $(document).on('click', '.remove-tr', function() {
    //     $(this).parents('tr').remove();
    // });

    $(document).on('click', '.remove-data', function() {
        $(this).closest("div.row").remove();
    });
</script>
