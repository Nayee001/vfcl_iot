@section('script')
    <script>
        $(function() {
            var table = $('.users-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users-ajax-datatables') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, // Index column
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'fname',
                        name: 'fname'
                    },
                    {
                        data: 'lname',
                        name: 'lname'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'creater',
                        name: 'creater'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false
                    },
                ]
            });
        });
        $('#form-create-users').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#submit').attr('disabled', true);
            $('#fullscreen-loader').show(); // Show the fullscreen loader

            var userCreateForm = new FormData($('#form-create-users')[0]);
            $.ajax({
                method: 'POST',
                url: '{{ route('users.store') }}',
                data: userCreateForm,
                cache: false,
                processData: false,
                contentType: false,
                success: function(resp) {
                    setTimeout(function() { // Wait for 2 seconds before showing the message
                        $('#fullscreen-loader').hide(); // Hide the fullscreen loader
                        if (resp.code == '{{ __('statuscode.CODE200') }}') {
                            toastr.success(resp.Message);
                            setTimeout(function() {
                                location.href = '{{ url('users') }}';
                            }, 1900);
                        } else {
                            toastr.error(resp.Message);
                        }
                        $('#submit').attr('disabled', false);
                    }, 2000);
                },
                error: function(data) {
                    setTimeout(function() { // Wait for 2 seconds before showing error messages
                        $('#fullscreen-loader').hide(); // Hide the fullscreen loader
                        $('#submit').attr('disabled', false);
                        $('.error').remove();
                        var errors = data.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var ele = "#" + key;
                            $(ele).removeClass('errors').addClass('errors');
                            var parentInputGroup = $(ele).closest('.input-group-merge');
                            if (parentInputGroup.length > 0) {
                                $('<label class="error">' + value + '</label>')
                                    .insertAfter(parentInputGroup);
                            } else {
                                $('<label class="error">' + value + '</label>')
                                    .insertAfter(ele);
                            }
                        });
                    }, 2000);
                }
            });
        });



        // Delete Users (Tamporory Delete)
        $(document).on('click', '.delete-user', function(e) {
            var id = this.id;
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You sure want to delete this user !!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete user!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'delete',
                        url: "{{ url('users') }}" + "/" + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(resp) {
                            if (resp.code == '{{ __('statuscode.CODE200') }}') {
                                toastr.success(resp.Message);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                toastr.error(resp.Message);
                            }
                        },
                    });

                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {}
            })
        });
        // deleted User Restore
        $(document).on('click', '.restore-user', function(e) {
            var id = this.id;
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You Are Re-Activating this user !!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Re-Activate user!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'get',
                        url: "{{ url('users-restore') }}" + "/" + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        processData: false,
                        contentType: false,
                        success: function(resp) {
                            if (resp.code == '{{ __('statuscode.CODE200') }}') {
                                swalWithBootstrapButtons.fire(
                                    'Activated !',
                                    'Now this use can login and perform activities.',
                                    'success'
                                )
                                setTimeout(function() {
                                    location.href = '{{ url('users') }}';
                                }, 2000);
                            } else {
                                toastr.error(resp.Message);
                            }
                        },
                    });

                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {}
            })
        });


        // to Genarate Random Password
        function generateRandomPassword(length) {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";

            let password = "";
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset.charAt(randomIndex);
            }
            return password;
        }
        var controlError = document.getElementById("generatePassword");
        if (controlError) {
            document.getElementById("generatePassword").addEventListener("click", function() {
                const randomPassword = generateRandomPassword(8);
                document.getElementById("password").value = randomPassword;
                document.getElementById("confirm_password").value = randomPassword;
            });
        }

        $(document).ready(function() {

            var i = 1;
            var length;
            $("#add").click(function() {
                i++;
                $('#dynamic_field').append('<tr id="row' + i +
                    '"><td class="no-border"><input type="text" name="location_name[]" placeholder="eg; Zurn Science Center" class="form-control name_list"/></td><td class="no-border"><button type="button" name="remove" id="' +
                    i +
                    '" class="btn rounded-pill btn-icon btn-danger btn_remove"><i class="bx bx-minus"></i></button></td></tr>'
                );
            });
            $(document).on('click', '.btn_remove', function() {

                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });
        });
    </script>
@endsection
