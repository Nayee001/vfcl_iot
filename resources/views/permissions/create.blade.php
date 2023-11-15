<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editRole">Create New Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-permission-create" method="post">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="name" class="form-label">Permission Name</label>
                        {!! Form::text('name', null, ['placeholder' => 'eg: User', 'id' => 'name', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label><input id="permission" class="form-check-input" name="isNotSingle" type="checkbox">
                            Create With Group ?</label>
                    </div>
                </div>
                <div class="form-text" id="other-info">When you check the checkbox, It will Automatically create other
                    needed permission using
                    this name. <br>
                    Example: users-list | users-create | users-edit | users-delete.
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
</div>

<script>
    $(function() {
        $('#form-permission-create').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('submit').attr('disabled', true);
            var formData = new FormData($('#form-permission-create')[0]);
            $.ajax({
                method: 'POST',
                url: '{{ route('permissions.store') }}',
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
</script>
