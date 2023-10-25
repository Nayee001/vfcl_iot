@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Device Management /</span> Create New Device</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="form-create-device" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="form-label">Device Name {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('name', null, ['placeholder' => 'Device Name', 'id' => 'name', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="device_type" class="form-label">Device Type
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('device_type', $device_type, null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Device Type',
                                            'id' => 'device_type',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Select Manager
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('owner', $managers, null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Manager',
                                            'id' => 'owner',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="health" class="form-label">Health {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('health', $health, 'Good', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select health',
                                            'id' => 'health',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                            <label class="form-label" for="phoneNumber">Status {!! dynamicRedAsterisk() !!}</label>
                                            {!! Form::select('status', $status, 'Active', [
                                                'class' => 'form-control',
                                                'placeholder' => 'Select Status',
                                                'id' => 'status',
                                            ]) !!}

                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="description" class="form-label">Description
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::textarea('description', $value = null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Device Description',
                                            'id' => 'description',
                                            'rows' => 1,
                                        ]) !!}
                                        <div id="floatingInputHelp" class="form-text">
                                            Description limited to 200 characters !
                                          </div>

                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" id="submit"
                                        class="submit btn btn-primary me-2">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /Form Device Create -->
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
        $('#form-create-device').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('submit').attr('disabled', true);
            var formData = new FormData($('#form-create-device')[0]);
            $.ajax({
                method: 'POST',
                url: '{{ route('devices.store') }}',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(resp) {
                    if (resp.code == '{{ __('statuscode.CODE200') }}') {
                        toastr.success(resp.Message);
                        setTimeout(function() {
                            window.location.href = "{{ route('devices.index') }}";
                        }, 1000);
                    } else {
                        toastr.error(resp.Message);
                    }
                },
                error: function(data) {
                    $(".submit").attr("disabled", false);
                    $("label.error").remove();
                    $(".error").removeClass('error');
                    var errors = data.responseJSON;
                    $.each(errors.errors, function(key, value) {
                        var ele = "#" + key;
                        $(ele).addClass('error');
                        if (!$(ele + " + label.error").length) {
                            $('<label class="error">' + value + '</label>').insertAfter(ele);
                        }
                    });
                }

            });
        });
    </script>
@endsection
