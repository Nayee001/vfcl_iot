@section('script')
    <script>
        $(function() {
            var table = $('.users-show-hierarchy-ajax-datatables').DataTable({
                processing: true,
                serverSide: true,

                ajax: "{{ route('users-show-hierarchy-ajax-datatables', $user->id) }}",
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
                        data: 'creater',
                        name: 'creater'
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
    </script>
@endsection
