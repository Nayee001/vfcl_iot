@section('script')
    <script>
        $(function() {
            var table = $('.devices-ajax-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('devices-ajax-datatable') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, // Index column

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'health',
                        name: 'health'
                    },
                    {
                        data: 'deviceStatus',
                        data: 'deviceStatus'
                    },
                    {
                        data: 'ownedBy',
                        name: 'ownedBy'
                    },

                    {
                        data: 'assignee',
                        name: 'assignee',
                    },
                    {
                        data: 'location',
                        name: 'location',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'createdtime',
                        name: 'createdtime'
                    },
                    {
                        data: 'apikey',
                        name: 'apikey',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        $(document).on('click', '.delete-device', function(e) {
            var id = this.id;
            swalWithBootstrapButtons.fire({
                title: 'Are you certain you want to proceed?',
                text: "Please note that this action is irreversible!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it !',
                cancelButtonText: 'No, cancel !',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'DELETE',
                        url: "{{ url('devices') }}" + "/" + id,
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

        $(document).on('click', '.unassign-device', function(e) {
            var id = this.id;
            swalWithBootstrapButtons.fire({
                title: 'Confirm Device Unassignment',
                text: "Unassigning this device will remove it from its current user or location and this action cannot be undone. Are you sure you wish to continue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes !',
                cancelButtonText: 'No !',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'DELETE',
                        url: "{{ url('unassign-device') }}" + "/" + id,
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


        function assignDeviceToUsers(url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(res) {
                    $("#assignDevice").html(res);
                    $("#assignDevice").modal('show');
                }
            });
        }

        function getApiKey(url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(res) {
                    $("#assignDevice").html(res);
                    $("#assignDevice").modal('show');
                }
            });
        }
    </script>
@endsection
