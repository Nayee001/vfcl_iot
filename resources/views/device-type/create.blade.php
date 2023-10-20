<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editRole">Create Device Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-device-type-create" method="post">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="name" class="form-label">Device Type {!!dynamicRedAsterisk()!!}</label>
                        {!! Form::text('device_type', null, [
                            'placeholder' => 'Device Type',
                            'id' => 'device_type',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="name" class="form-label">Description {!!dynamicRedAsterisk()!!}</label>
                        {!! Form::textarea('description', null, [
                            'placeholder' => 'Description',
                            'id' => 'description',
                            'class' => 'form-control',
                            'cols' => 10,
                            'rows' => 3,
                            'maxlength' => '50',
                        ]) !!}
                    </div>
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
    $('#form-device-type-create').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('submit').attr('disabled', true);
        var formData = new FormData($('#form-device-type-create')[0]);
        $.ajax({
            method: 'POST',
            url: '{{ route('devices-type.store') }}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(resp) {
                if (resp.code == '{{ __('statuscode.CODE200') }}') {
                    toastr.success(resp.Message);
                    setTimeout(function() {
                        window.location.reload(true);
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
                    $(ele).removeClass('error');
                    $(ele).addClass('error');
                    $('<label class="error">' + value + '</label>').insertAfter(
                        ele);
                });
            }
        });
    });
</script>
