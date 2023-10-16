@section('script')
    <script>
        // $(function() {
        // var table = $('.roles-datatable').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: "{{ route('roles-ajax-datatables') }}",
        //     columns: [{
        //             data: 'id',
        //             name: 'id'
        //         },
        //         {
        //             data: 'name',
        //             name: 'name'
        //         },
        //         {
        //             data: 'guard',
        //             name: 'guard',
        //             orderable: false,
        //             searchable: false
        //         },
        //         {
        //             data: 'actions',
        //             name: 'actions',
        //             orderable: false,
        //             searchable: false
        //         },
        //     ]
        // });

        // });

        // $(document).on('click', '.delete-role', function(e) {
        //     var id = this.id;
        //     swalWithBootstrapButtons.fire({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this role!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Yes, delete it!',
        //         cancelButtonText: 'No, cancel!',
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 method: 'DELETE',
        //                 url: "{{ url('roles') }}" + "/" + id,
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 cache: false,
        //                 processData: false,
        //                 contentType: false,
        //                 success: function(resp) {
        //                     if (resp.code == '{{ __('statuscode.CODE200') }}') {
        //                         toastr.success(resp.Message);
        //                         setTimeout(function() {
        //                             location.reload();
        //                         }, 1900);
        //                     } else {
        //                         toastr.error(resp.Message);
        //                     }
        //                 },
        //             });

        //         } else if (
        //             result.dismiss === Swal.DismissReason.cancel
        //         ) {}
        //     })
        // });

        function getDeviceTypeForm() {
            $.ajax({
                url: '{{ route('devices-type.create') }}',
                method: 'GET',
                success: function(res) {
                    $("#createDeviceTypeModel").html(res);
                    $("#createDeviceTypeModel").modal('show');
                }
            });
        }
    </script>
@endsection
