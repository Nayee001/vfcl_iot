@extends('layouts.app')
@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Roles And Persmissions /</span> Edit</h4>

            <div class="row">
                <div class="col-md-12">

                    <div class="card mb-4">
                        <h5 class="card-header">Edit Role and Permission</h5>
                        <hr class="my-0" />
                        <div class="card-body">
                            <form id="form-roles-edit" method="post">
                                @csrf
                                {{ method_field('PUT') }}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-3 col-md-4">
                                            <label for="nameBasic" class="form-label">Name</label>
                                            {!! Form::text('name', $role['name'], ['placeholder' => 'Name', 'id' => 'name', 'class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        @php $counter = 0 @endphp
                                        @foreach ($permission as $value)
                                            <div class="col-md-3">
                                                <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['id' => 'permission', 'class' => 'form-check-input']) }}
                                                    {{ $value->name }}</label>
                                            </div>
                                            @php $counter++ @endphp
                                            @if ($counter % 4 == 0)
                                    </div>
                                    <div class="row">
                                        @endif
                                        @endforeach
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /Account -->
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#form-roles-edit').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#form-roles-edit')[0]);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('roles.update', $role->id) }}',
                    data: formData,
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
                                window.location.href = "{{ route('roles.index') }}";
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
    </script>
@endsection
