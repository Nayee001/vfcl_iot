<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editRole">Edit Device Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="form-device-type-edit" method="post">
            @csrf
            {{ method_field('PUT') }}
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="name" class="form-label">Device Type</label>
                        {!! Form::text('device_type', $device_type['device_type'], [
                            'placeholder' => 'Device Type',
                            'id' => 'device_type',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="name" class="form-label">Description</label>
                        {!! Form::textarea('description', $device_type['description'], [
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
    $('#form-device-type-edit').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('submit').attr('disabled', true);
        var formData = new FormData($('#form-device-type-edit')[0]);
        $.ajax({
            method: 'POST',
            url: '{{ route('devices-type.update', $device_type->id) }}',
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
                    $(ele).removeClass('errors');
                    $(ele).addClass('error');
                    $('<label class="error">' + value + '</label>').insertAfter(
                        ele);
                });
            }
        });
    });
</script>
