@section('script')
    <script>
        $(function() {
            var table = $('.devices-ajax-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('devices-ajax-datatable') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description',
                        orderable: false,
                        searchable: false
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
                        data: 'createdBy',
                        name: 'createdBy'
                    },
                    {
                        data:'createdtime',
                        name:'createdtime'
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

        function getDeviceTypeForm() {
            $.ajax({
                url: '{{ route('devices-type.create') }}',
                method: 'GET',
                success: function(res) {
                    $("#DeviceTypeModel").html(res);
                    $("#DeviceTypeModel").modal('show');
                }
            });
        }

        function getDeviceTypeEditForm(url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(res) {
                    $("#DeviceTypeModel").html(res);
                    $("#DeviceTypeModel").modal('show');
                }
            });
        }
    </script>
@endsection
