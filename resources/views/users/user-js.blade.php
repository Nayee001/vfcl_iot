@section('script')
    <script>
        $(function() {
            var table = $('.users-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users-ajax-datatables') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
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
                        data:'creater',
                        name:'creater'
                    },
                    {
                        data: 'devices',
                        name: 'devices'
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
            $('submit').attr('disabled', true);
            var userCreateForm = new FormData($('#form-create-users')[0]);
            $.ajax({
                method: 'POST',
                url: '{{ route('users.store') }}',
                data: userCreateForm,
                cache: false,
                processData: false,
                contentType: false,
                success: function(resp) {
                    if (resp.code == '{{ __('statuscode.CODE200') }}') {
                        toastr.success(resp.Message);
                        setTimeout(function() {
                            location.href = '{{ url('users') }}';
                        }, 1900);
                    } else {
                        toastr.error(resp.Message);
                    }
                },
                error: function(data) {
                    $(".submit").attr("disabled", false);
                    $('.error').remove();
                    var errors = data.responseJSON;
                    $.each(errors.errors, function(key, value) {
                        var ele = "#" + key;
                        $(ele).removeClass('errors');
                        $(ele).addClass('errors');
                        var parentInputGroup = $(ele).closest('.input-group-merge');

                        if (parentInputGroup.length > 0) {
                            $('<label class="error">' + value + '</label>').insertAfter(
                                parentInputGroup);
                        } else {
                            $('<label class="error">' + value + '</label>').insertAfter(ele);
                        }

                    });
                }
            });
        });

        // Delete Users (Tempaery Delete)
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
    </script>
@endsection
