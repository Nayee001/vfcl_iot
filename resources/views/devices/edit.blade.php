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
                            <form id="form-device-edit" method="post">
                                @csrf
                                {{ method_field('PUT') }}
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="form-label">Device Name {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::hidden('id', $deviceData['id'], [
                                            'id' => 'id',
                                        ]) !!}
                                        {!! Form::text('name', $deviceData['name'], [
                                            'placeholder' => 'Device Name',
                                            'id' => 'name',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="device_type" class="form-label">Device Type
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('device_type', $device_type, $deviceData['device_type'], [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Device Type',
                                            'id' => 'device_type',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Select Owner
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('owner', $managers, $deviceData['owner'], [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Owner',
                                            'id' => 'owner',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="health" class="form-label">Health {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('health', $health, $deviceData['health'], [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select health',
                                            'id' => 'health',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="phoneNumber">Status {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('status', $status, $deviceData['status'], [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Status',
                                            'id' => 'status',
                                        ]) !!}

                                    </div>
                                    <div class="mb-3 col-md-6 form-password-toggle">
                                        <label class="form-label" for="password">API KEY</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text" title="Generate Api key"><a
                                                    href="javascript:void(0);" title="Generate Random Password"
                                                    id="generateApi"
                                                    class="btn rounded-pill btn-icon-generate_password btn-outline-primary __web-inspector-hide-shortcut__"><span
                                                        class="tf-icons bx bx-refresh"></span></a></span>
                                            {!! Form::text('api_key', $deviceData['api_key'], [
                                                'placeholder' => 'API KEY',
                                                'id' => 'api_key',
                                                'class' => 'form-control',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="phoneNumber">IP Address
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('ip_address', $deviceData['ip_address'], [
                                            'placeholder' => 'ip_address',
                                            'id' => 'ip_address',
                                            'class' => 'form-control',
                                        ]) !!}

                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="phoneNumber">IMEI Number
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('imei', $deviceData['imei'], [
                                            'placeholder' => 'Device imei',
                                            'id' => 'imei',
                                            'class' => 'form-control',
                                        ]) !!}

                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="description" class="form-label">Description
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::textarea('description', $value = $deviceData['description'], [
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
        $('#form-device-edit').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('submit').attr('disabled', true);
            var formData = new FormData($('#form-device-edit')[0]);
            $.ajax({
                method: 'POST',
                url: '{{ route('devices.update', $deviceData->id) }}',
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
                            window.location.href = "{{ route('devices.index') }}";
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

        // Function to generate a random API key
        function generateApiKey() {
            var apiKey = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < 64; i++) { // Length of API Key is 32 characters
                apiKey += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return apiKey;
        }

        // Bind the API key generation to the click event of the button
        $('#generateApi').click(function() {
            var newApiKey = generateApiKey();
            $('#api_key').val(newApiKey);
        });
    </script>
@endsection
