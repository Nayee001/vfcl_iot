<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="assignDevice"> <i class="bx bx-chip"></i> Assign Device to User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="assign-device-to-user" method="post">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="device_id" class="form-label">Select Your Device {!!dynamicRedAsterisk()!!}</label>
                        {!! Form::select('device_id', $allDevices, $deviceData->id,['placeholder' => 'Select Device', 'id' => 'device_id', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="assign_to" class="form-label">Select Your Customer {!!dynamicRedAsterisk()!!}</label>
                        {!! Form::select('assign_to', $customers, null,['placeholder' => 'Select Customer', 'id' => 'assign_to', 'class' => 'form-control']) !!}
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
    $(function() {
        $('#assign-device-to-user').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('submit').attr('disabled', true);
            var formData = new FormData($('#assign-device-to-user')[0]);
            $.ajax({
                method: 'POST',
                url: '{{ route('assign.device') }}',
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
