@extends('layouts.app')
<!-- Content -->
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Role Management /</span> Role List</h4>

            </div>
            <div class="mt-3">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRole">
                    Create Role
                </button>
                <!-- Modal -->
                <div class="modal fade" id="createRole" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Create Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="form-roles-create" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="nameBasic" class="form-label">Name</label>
                                            {!! Form::text('name', null, ['placeholder' => 'Role Name', 'id' => 'name', 'class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    @foreach ($permission as $value)
                                        <label>{{ Form::checkbox('permission[]', $value->id, false, ['id' => 'permission', 'class' => 'name']) }}
                                            {{ $value->name }}</label>
                                        <br />
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card">
            <h5 class="card-header">Role Management</h5>

            <div class="table-responsive text-nowrap">
                <div class="container">
                    <table class="table roles-datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Role Name</th>
                                <th>Permissions</th>
                                <th>Guard</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            {{-- @foreach ($roles as $item)
                                <tr>
                                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                        <strong>{{ $item->name }}</strong>
                                    </td>
                                    <td>
                                        @empty($item->permissions)
                                            <div class="row">
                                                No Permissions Given
                                            </div>
                                        @else
                                            <div class="demo-inline-spacing">
                                                @foreach ($item->permissions as $permission)
                                                    <p>
                                                        <span class="badge bg-label-primary me-1">{{ $permission->name }}</span>
                                                    </p>
                                                @endforeach
                                            </div>
                                        @endempty
                                    </td>
                                    <td><span class="badge rounded-pill bg-label-secondary">{{ $item->guard_name }}</span></td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    onclick="getEditForm('{{ route('roles.edit', $item->id) }}')"
                                                    id="{{ $item->id }}" href="javascript:void(0);"><i
                                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                                <a class="dropdown-item delete-role" href="javascript:void(0);"
                                                    id="{{ $item->id }}"><i class="bx bx-trash-alt me-1"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach --}}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModel" data-backdrop="static">
    </div>

    <!-- / Content -->
@endsection
@section('script')
    <script>
        $(function() {
            var table = $('.roles-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('roles-ajax-datatables') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '20px'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        width: '20px'
                    },
                    {
                        data: 'guard',
                        name: 'guard',
                        orderable: false,
                        searchable: false,
                        width: '20px'
                    },
                    {
                        data: 'permissions',
                        name: 'permissions',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#form-roles-create').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#form-roles-create')[0]);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('roles.store') }}',
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

        $(document).on('click', '.delete-role', function(e) {
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
                text: "You won't be able to revert this role!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'DELETE',
                        url: "{{ url('roles') }}" + "/" + id,
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
