<div class="modal-dialog" role="document">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editRole">Edit Role</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-roles-edit" method="post">
            @csrf
            {{ method_field('PUT') }}
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="nameBasic" class="form-label">Name</label>
                        {!! Form::text('name', $role['name'], ['placeholder' => 'Name', 'id' => 'name', 'class' => 'form-control']) !!}
                    </div>
                </div>
                @foreach ($permission as $value)
                    <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['id' => 'permission', 'class' => 'name']) }}
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
</script>
