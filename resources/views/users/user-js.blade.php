@section('script')
    <script>
        $(function() {
            var table = $('.users-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users-ajax-datatables') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '20px'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        width: '20px'
                    },
                    {
                        data: 'fname',
                        name: 'fname',
                        width: '20px'
                    },
                    {
                        data: 'lname',
                        name: 'lname',
                        width: '20px'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        width: '50px    '
                    },
                    {
                        data: 'role',
                        name: 'role',
                        width: '50px'
                    },
                    {
                        data: 'devices',
                        name: 'devices',
                        width: '50px'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        width: '50px'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $('#form-users-create').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#form-users-create')[0]);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('users.store') }}',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        if (resp.code == '{{ __('statuscode.CODE200') }}') {
                            toastr.success(resp.Message);
                            setTimeout(function() {
                                location.reload();
                            }, 1900);
                        } else {
                            toastr.error(resp.Message);
                        }
                    },
                    error: function(data) {
                        $(".submit").attr("disabled", false);
                        var errors = data.responseJSON;
                        $.each(errors.errors, function(key, value) {
                            var ele = "#" + key;
                            $(ele).addClass('error');
                            $('<label class="error">' + value + '</label>').insertAfter(
                                ele);
                        });
                    }
                });
            });
        });

        $(document).on('click', '.delete-user', function(e) {
            var id = this.id;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You Are Deactivating this user from there activities !!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Deactivate user!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'DELETE',
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
                                }, 1900);
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

        $(document).on('click', '.restore-user', function(e) {
            var id = this.id;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

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
                        method: 'DELETE',
                        url: "{{ url('users') }}" + "/" + id,
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
                                    location.reload();
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

        function getEditForm(url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(res) {
                    $("#editModel").html(res);
                    $("#editModel").modal('show');
                }
            });
        }
    </script>
@endsection
