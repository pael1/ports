<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
{{-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script> --}}
<script src="{{ asset('plugins/pusher/pusher.min.js') }}"></script>
<!-- Bootstrap 4 -->
{{-- <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('dist/js/select2.js') }}"></script>


<!-- DataTables  & Plugins -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<script src="{{ asset('plugins/customize/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/customize/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/customize/sweetalert2@11.js') }}"></script>
<script src="{{ asset('plugins/customize/dataTables.responsive.min.js') }}"></script>
{{-- sweet alert success --}}
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: ' {{ session(' success ') }}',
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
@stack('scripts')
<script>
    $(function() {

        var pusher = new Pusher('60d1f9fb0b13cd84a90d', {
            cluster: 'ap1'
        });
        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {

            if (data.assignedto == {!! json_encode(Auth::user()->id) !!}) {
                document.getElementById('audio').play();
                let pending = parseInt($('#' + data.assignedto).find('.pending')
                    .html());
                if (pending) {
                    $('#' + data.assignedto).find('.pending').html(pending + 1);
                } else {
                    $('#' + data.assignedto).html(
                        '<i class="far fa-comments"></i>' +
                        '<span class="badge badge-danger navbar-badge pending">1</span>' +
                        '</a>');
                }

                Swal.fire({
                    icon: 'info',
                    text: 'You have new notification.',
                    target: '#custom-target',
                    showCancelButton: true,
                    confirmButtonText: 'Read',
                    customClass: {
                        container: 'position-absolute'
                    },
                    toast: true,
                    position: 'bottom-right'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var token = $("meta[name='csrf-token']").attr("content");
                        $.ajax({
                            url: "{{ url('read') }}" + '/' + data.notifno,
                            type: 'PUT',
                            data: {
                                "notifno": data.notifno,
                                "_token": token,
                            },
                            success: function(data) {
                                console.log(data);
                            }
                        })
                        //redirect to the complaint details
                        $.ajax({
                            url: "{{ url('complaint_id') }}",
                            type: 'GET',
                            data: {
                                'notifno': data.notifno
                            },
                            success: function(data) {
                                console.log(data);
                                let url =
                                    "{{ route('complaints.edit', ':id') }}";
                                url = url.replace(':id', data[0].complaint_id);
                                document.location.href = url;
                            }
                        })
                    } else {

                    }
                })


                // Swal.fire({
                //     title: 'You have new notification.',
                //     text: "You want to open it?",
                //     icon: 'info',
                //     showCancelButton: true,
                //     confirmButtonColor: '#3085d6',
                //     cancelButtonColor: '#d33',
                //     confirmButtonText: 'Yes, open it',
                //     cancelButtonText: 'No, thanks'
                // }).then((result) => {
                //     if (result.isConfirmed) {
                //         //check if this notification is for monitoring case aging
                //         // if (data.admin == "yes") {
                //         //     //read message
                //         //     var token = $("meta[name='csrf-token']").attr("content");
                //         //     $.ajax({
                //         //         url: "{{ url('readAdmin') }}" + '/' + data
                //         //             .complaint_id,
                //         //         type: 'PUT',
                //         //         data: {
                //         //             "complaint_id": data.complaint_id,
                //         //             "_token": token,
                //         //         },
                //         //         success: function(data) {
                //         //             console.log(data);
                //         //         }
                //         //     })

                //         //     //redirecto to the complaint
                //         //     let url = "{{ route('complaints.edit', ':id') }}";
                //         //     url = url.replace(':id', data.complaint_id);
                //         //     document.location.href = url;
                //         // } else {
                //             //read message
                //             var token = $("meta[name='csrf-token']").attr("content");
                //             $.ajax({
                //                 url: "{{ url('read') }}" + '/' + data.notifno,
                //                 type: 'PUT',
                //                 data: {
                //                     "notifno": data.notifno,
                //                     "_token": token,
                //                 },
                //                 success: function(data) {
                //                     console.log(data);
                //                 }
                //             })
                //             //redirect to the complaint details
                //             $.ajax({
                //                 url: "{{ url('complaint_id') }}",
                //                 type: 'GET',
                //                 data: {
                //                     'notifno': data.notifno
                //                 },
                //                 success: function(data) {
                //                     console.log(data);
                //                     let url =
                //                         "{{ route('complaints.edit', ':id') }}";
                //                     url = url.replace(':id', data[0].complaint_id);
                //                     document.location.href = url;
                //                 }
                //             })
                //         // }
                //     } else {
                //         let pending = parseInt($('#' + data.assignedto).find('.pending')
                //             .html());
                //         if (pending) {
                //             $('#' + data.assignedto).find('.pending').html(pending + 1);
                //         } else {
                //             $('#' + data.assignedto).html(
                //                 '<a class="nav-link" data-toggle="dropdown" href="#" onClick="showDiv();>' +
                //                 '<i class="far fa-comments"></i>' +
                //                 '<span class="badge badge-danger navbar-badge pending">1</span>' +
                //                 '</a>');
                //         }
                //     }
                // })


            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // var complaintTable = $("#generalTable").DataTable({
        //     serverSide: true,
        //     processing: true,
        //     ajax: "{{ route('complaints.index') }}",
        //     columns: [{
        //             data: 'receivedBy',
        //             name: 'receivedBy'
        //         },
        //         {
        //             data: 'name',
        //             name: 'assignedTo'
        //         },
        //         {
        //             data: 'dateFiled',
        //             name: 'dateFiled'
        //         },
        //         {
        //             data: 'action',
        //             name: 'action'
        //         },
        //     ]
        // });

        // //show complaint/redirect to edit page of the complaint
        // var id = "";
        // $("body").on('click', '.editComplaint', function() {
        //     var id = $(this).data("id");
        //     let url = "{{ route('complaints.edit', ':id') }}";
        //     url = url.replace(':id', id);
        //     document.location.href = url;
        // });

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


        // //delete complaint
        // $("body").on('click', '.deleteComplaint', function() {
        //     var id = $(this).data("id");
        //     var token = $("meta[name='csrf-token']").attr("content");
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, delete it!'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 // url: "/deleteComplaint/" + id,
        //                 url: "{{ url('deleteComplaint') }}" + '/' + id,
        //                 type: 'DELETE',
        //                 data: {
        //                     "id": id,
        //                     "_token": token,
        //                 },
        //                 success: function() {
        //                     complaintTable.draw();
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'Successfully deleted',
        //                         showConfirmButton: false,
        //                         timer: 1500
        //                     })

        //                 }
        //             });
        //         }
        //     })
        // });

        //multiple select violated laws
        $('.selectMultiple').select2({
            placeholder: "Select violation/s",
            width: '100%',
        });

        $('.selectCrime').select2();

        //notifications
        // const notification = () => {
        //     $.ajax({
        //         url: "{{ url('notifications') }}",
        //         type: 'GET',
        //         data: {
        //             'id': {!! json_encode(Auth::user()->id) !!}
        //         },
        //         success: function(data) {
        //             console.log(data);
        //             $("#numberNotif").text(data.length);
        //         }
        //     })
        // }
        // setInterval(notification,1000);
    });

    function showDiv() {
        $(".notif").empty();
        $.ajax({
            type: "GET",
            url: "{{ url('openNotification') }}",
            success: function(data) {
                console.log(data);
                if (data != "") {
                    len = data.length;

                    for (var i = 0; i < len; i++) {
                        var id = data[i].id;
                        var receivedBy = data[i].receivedBy;
                        var email = data[i].email;
                        var dateFiled = data[i].dateFiled;
                        var markmsg = data[i].markmsg;
                        var is_read = data[i].is_read;
                        let NPSDNumber = data[i].NPSDNumber;
                        let classNotif = (markmsg != 1) ? 'text-secondary' : 'text-danger';
                        let className = (markmsg != 1) ? 'fw-light' : 'fw-bold';
                        var option = '<a href="#" class="dropdown-item open-notif" data-id="' + data[i].id +
                            '" id="' + NPSDNumber + '">' +
                            '<div class="media openNotification">' +
                            '<div class="media-body">' +
                            '<h3 class="dropdown-item-title ' + className + '">' +
                            '' + data[i].name + '' +
                            '<span class="float-right text-sm ' + classNotif +
                            '"><i class="fas fa-bell"></i></span>' +
                            '</h3>' +
                            //   '<p class="text-sm">'+email+'</p>'+
                            '<p class="text-sm text-muted ' + className +
                            '"><i class="far fa-clock mr-1"></i> ' + dateFiled +
                            '</p>' +
                            '</div>' +
                            '</div>' +
                            '</a>' +
                            '<div class="dropdown-divider"></div>'
                        $(".notif").append(option);
                    }
                    var offsetHeight = document.getElementById('notificationBox').offsetHeight;
                    console.log(offsetHeight);
                    if (offsetHeight > 287) {
                        $('#notificationBox').css({
                            "overflow": "scroll",
                            "height": "288px"
                        });
                    }
                } else {
                    var option = '<div class="text-center"><i>No message found</i></div>'
                    $(".notif").append(option);
                }

            }
        });
    }

    //disabled save btn if clicked
    // $(".btn_save" ).click(function() {
    //     $(".btn_save").hide();
    // });

    // $('#lastnameComplainant').on('keyup', function() {
    //     const firstname = $('#firstnameComplainant').val();
    //     const middlename = $('#middlenameComplainant').val();
    //     const lastname = $(this).val();
    //     $.ajax({
    //         url: "{{ url('search') }}",
    //         type: 'GET',
    //         data: {
    //             'firstname': firstname,
    //             'middlename': middlename,
    //             'lastname': lastname
    //         },
    //         success: function(data) {
    //             console.log(data);
    //         }
    //     })
    // });

    // //checking if not typing for 3secs
    // let typingTimer;                //timer identifier
    // let doneTypingInterval = 5000;  //time in ms (5 seconds)

    // $(document).on('keyup', '.lastname', function() {
    //     clearTimeout(typingTimer);
    //     if ($('.lastname').val()) {
    //         typingTimer = setTimeout(doneTyping, doneTypingInterval);
    //     }
    // });
    // //user is "finished typing," do something
    // function doneTyping () {
    //     console.log('test');
    // }
    // //end checking

    //array for complaint_id every complainant inputed
    // const complaints_id = [];
    // //validate every name encoded
    // //complainant
    // $(document).on('keyup', '.lastname', function() {
    //     const firstname = $(this).closest('div.row').find('.firstname').val();
    //     const middlename = $(this).closest('div.row').find('.middlename').val();
    //     const lastname = $(this).closest('div.row').find('.lastname').val();
    //     console.log(firstname);
    //     console.log(middlename);
    //     console.log(lastname);
    //     $.ajax({
    //         url: "{{ url('search') }}",
    //         type: 'GET',
    //         data: {
    //             'firstname': firstname,
    //             'middlename': middlename,
    //             'lastname': lastname,
    //             'type': 'complainant'
    //         },
    //         success: function(data) {
    //             console.log(data);
    //             if (data != '') {
    //                 //save to complaints_id array if exist in tbl
    //                 if (complaints_id.indexOf(data[0].complaint_id)) {
    //                     complaints_id.push(data[0].complaint_id);
    //                 }
    //                 console.log(complaints_id);
    //             }
    //         }
    //     })
    // });

    // //validate every name encoded
    // //respondent
    // $(document).on('keyup', '.lastnameR', function() {
    //     const firstname = $(this).closest('div.row').find('.firstnameR').val();
    //     const middlename = $(this).closest('div.row').find('.middlenameR').val();
    //     const lastname = $(this).closest('div.row').find('.lastnameR').val();
    //     console.log(complaints_id);
    //     console.log(firstname);
    //     console.log(middlename);
    //     console.log(lastname);

    //     $.ajax({
    //         url: "{{ url('search') }}",
    //         type: 'GET',
    //         data: {
    //             'firstname': firstname,
    //             'middlename': middlename,
    //             'lastname': lastname,
    //             'type': 'respondent'
    //         },
    //         success: function(data) {
    //             console.log(data);
    //             if (data != '') {
    //                 complaints_id.forEach((complaint_id, i) => {
    //                     if (data[0].complaint_id === complaint_id) {
    //                         Swal.fire({
    //                             html: '<b style="font-size:17px;">This complainant was related to...</b>',
    //                             icon: 'error',
    //                             showCancelButton: true,
    //                             allowOutsideClick: false,
    //                             confirmButtonColor: '#3085d6',
    //                             cancelButtonColor: 'rgb(211 71 71)',
    //                             confirmButtonText: 'OK',
    //                             cancelButtonText: 'CANCEL'
    //                         }).then((result) => {
    //                             if (result.isConfirmed) {
    //                                 console.log(data[0].complaint_id);
    //                                 $('#assignedToId option[value=' + data[0]
    //                                         .assignedTo + ']')
    //                                     .attr('selected', 'selected');
    //                             } else {

    //                             }
    //                         })
    //                     }
    //                 });

    //             }
    //         }
    //     })
    // });

    // $('.lastname').on('keyup', function() {
    //     console.log('');
    // })

    // $(".deleteParty").click(function() {
    //     var id = $(this).data("id");
    //     var token = $("meta[name='csrf-token']").attr("content");
    //     console.log(id);
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 // url: "/party/" + id,
    //                 url: "{{ url('party') }}" + '/' + id,
    //                 type: 'DELETE',
    //                 data: {
    //                     "id": id,
    //                     "_token": token,
    //                 },
    //                 success: function() {
    //                     $("#" + id + "").remove();
    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: 'Successfully deleted',
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     })
    //                 }
    //             });
    //         }
    //     })
    // });
    // $(".deleteViolation").click(function() {
    //     var id = $(this).data("id");
    //     var token = $("meta[name='csrf-token']").attr("content");
    //     console.log(id);
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 // url: "/violation/" + id,
    //                 url: "{{ url('violation') }}" + '/' + id,
    //                 type: 'DELETE',
    //                 data: {
    //                     "id": id,
    //                     "_token": token,
    //                 },
    //                 success: function() {
    //                     $("#" + id + "").remove();
    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: 'Successfully deleted',
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     })
    //                     // $( ".accordion-collapse" ).load(window.location.href + " .accordion-collapse" );
    //                 }
    //             });
    //         }
    //     })
    // });

    // $(".deleteAttachment").click(function() {
    //     var id = $(this).data("id");
    //     var token = $("meta[name='csrf-token']").attr("content");

    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 // url: "/attachments/" + id,
    //                 url: "{{ url('attachments') }}" + '/' + id,
    //                 type: 'DELETE',
    //                 data: {
    //                     "id": id,
    //                     "_token": token,
    //                 },
    //                 success: function() {
    //                     $("#" + id + "").remove();
    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: 'Successfully deleted',
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     })
    //                     // $("#attachmentsTable").load(window.location.href +
    //                     //     " #attachmentsTable");
    //                 }
    //             });
    //         }
    //     })
    // });

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
    // $("#enabledUpdateBtn").click(function() {
    //     $("#enabledUpdateBtn").hide();
    //     $("#disabledUpdateBtn").show();
    //     // $("#formId .form-control").prop("disabled", false);
    //     $("#formId .form-select").prop("disabled", false);
    //     $("#formId input").prop("disabled", false);
    //     $("#select2multiple").prop("disabled", false);
    //     $("#formId .add").show();
    //     $("#btnUpdate").show();
    // });

    //disable update
    // $("#disabledUpdateBtn").click(function() {
    //     $("#enabledUpdateBtn").show();
    //     // $("#formId .form-control").prop("disabled", true);
    //     $("#formId .form-select").prop("disabled", true);
    //     $("#formId input").prop("disabled", true);
    //     $("#select2multiple").prop("disabled", true);
    //     $("#formId .add").hide();
    //     $("#btnUpdate").hide();
    //     $("#disabledUpdateBtn").hide();
    // });

    //add new fields for complainants
    var complainantIndex = 0;
    $("#addComplainant").click(function() {
        --complainantIndex;
        $("#dynamicComplainant").append('<div class="row mt-2">' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 opsy">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][firstname]"' +
            'class="form-control firstname" placeholder="First Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][middlename]"' +
            'class="form-control middlename" placeholder="Middle Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreComplainant[' + complainantIndex + '][lastname]"' +
            'class="form-control lastname" placeholder="Last Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<select class="form-select forMobile" name="addMoreComplainant[' + complainantIndex +
            '][sex]" id="floatingSelect"' +
            'aria-label="Floating label select example" style="width:101px;">' +
            '<option value="" selected hidden>Sex</option>' +
            '<option value="Male">Male</option>' +
            '<option value="Female">Female</option>' +
            '</select>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"' +
            'name="addMoreComplainant[' + complainantIndex +
            '][age]" class="form-control ageGrid forMobile"' +
            'placeholder="Age" style="width:72px;">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"' +
            'name="addMoreComplainant[' + complainantIndex + '][address]" class="form-control"' +
            'placeholder="Address">' +
            '<input type="text"' +
            'name="addMoreComplainant[' + complainantIndex +
            '][belongsTo]"  value="complainant" class="form-control"' +
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
            'class="form-control firstnameR" placeholder="First Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreRespondent[' + respondentIndex + '][middlename]"' +
            'class="form-control middlenameR" placeholder="Middle Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">' +
            '<input type="text" name="addMoreRespondent[' + respondentIndex + '][lastname]"' +
            'class="form-control lastnameR" placeholder="Last Name">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<select class="form-select forMobile" name="addMoreRespondent[' + respondentIndex +
            '][sex]" id="floatingSelect"' +
            'aria-label="Floating label select example" style="width:101px;">' +
            '<option value="" selected hidden>Sex</option>' +
            '<option value="Male">Male</option>' +
            '<option value="Female">Female</option>' +
            '</select>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"' +
            'name="addMoreRespondent[' + respondentIndex +
            '][age]" class="form-control ageGrid forMobile"' +
            'placeholder="Age" style="width:72px;">' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"> <input type="text"' +
            'name="addMoreRespondent[' + respondentIndex + '][address]" class="form-control"' +
            'placeholder="Address">' +
            '<input type="text"' +
            'name="addMoreRespondent[' + respondentIndex +
            '][belongsTo]"  value="respondent" class="form-control"' +
            'placeholder="Address" hidden>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1">' +
            '<button type="button" title="Remove" class="btn btn-danger btn-sm remove-data">-</button>' +
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
            '<select class="form-select forMobile" name="addMoreWitness[' + witnessIndex +
            '][sex]" id="floatingSelect"' +
            'aria-label="Floating label select example" style="width:101px;">' +
            '<option value="" selected hidden>Sex</option>' +
            '<option value="Male">Male</option>' +
            '<option value="Female">Female</option>' +
            '</select>' +
            '</div>' +
            '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-1"> <input type="text"' +
            'name="addMoreWitness[' + witnessIndex + '][age]" class="form-control ageGrid forMobile"' +
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
            '<button type="button" title="Remove" class="btn btn-danger btn-sm remove-data">-</button>' +
            '</div>' +
            '</div>');
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    //remove added field //general remove function
    $(document).on('click', '.remove-data', function() {
        $(this).closest("div.row").remove();
    });

    $(document).on('click', '.open-notif', function() {
        // if ('{!! Auth::user()->designation !!}' == "Reviewer") {
        //     //read message
        //     var token = $("meta[name='csrf-token']").attr("content");
        //     $.ajax({
        //         url: "{{ url('readAdmin') }}" + '/' + $(this).attr("data-id"),
        //         type: 'PUT',
        //         data: {
        //             "complaint_id": $(this).attr("data-id"),
        //             "_token": token,
        //         },
        //         success: function(data) {
        //             console.log(data);
        //         }
        //     })

        //     //redirecto to the complaint
        //     let url = "{{ route('complaints.edit', ':id') }}";
        //     url = url.replace(':id', $(this).attr("data-id"));
        //     document.location.href = url;
        // } else {
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "{{ url('read') }}" + '/' + this.id,
            type: 'PUT',
            data: {
                "notifno": this.id,
                "_token": token,
            },
            success: function(data) {
                console.log(data);
            }
        })
        //redirect to the complaint details
        $.ajax({
            url: "{{ url('complaint_id') }}",
            type: 'GET',
            data: {
                'notifno': this.id
            },
            success: function(data) {
                console.log(data);
                let url = "{{ route('complaints.edit', ':id') }}";
                url = url.replace(':id', data[0].complaint_id);
                document.location.href = url;
            }
        })
        // }
    });
</script>
