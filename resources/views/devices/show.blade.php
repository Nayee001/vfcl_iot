@extends('layouts.customer-app')

@section('content')
    <style>
        {
            margin-right: 15px;
        }

        .form-check-label {
            font-size: 14px;
            color: #333;
        }
    </style>
    {{-- @dd($deviceData) --}}
    <div class="container-xl flex-grow-1">
        <!-- Welcome Message -->
        <div class="text-center mb-4 mt-1">
            <h2>Hey, {{ Auth::user()->fname }}! Welcome to vFCL IoT Device 🎉</h2>
            <p style="font-size: 18px; color:black;">
                Please ensure your device is connected to the internet before proceeding. <br> Do not close this page until
                the
                process is complete.
            </p>
        </div>

        <!-- Main Content Area -->
        <div class="row mb-4">
            <!-- Left Column: Device Info and Status -->
            <div class="col-md-8 mb-4">
                <div class="">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <!-- Device Information -->
                            <div class="col-md-6 d-flex align-items-center">
                                {{-- <img src="{{ asset('assets/img/illustrations/iot.png') }}" alt="iot"
                                    class="d-block rounded-circle" height="80" width="80"> --}}
                                <div class="ms-3">
                                    <h5 class="m-0 text-primary">{{ $deviceData->name }}</h5>
                                    <small class="text-muted">{{ $deviceData['deviceAssigned']->connection_status }}</small>
                                </div>
                            </div>

                            <!-- Device Status Legends -->
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <div class="card-title mb-0">
                                    <div class="mt-3">
                                        <ul class="list-unstyled d-flex flex-wrap gap-3">
                                            <li class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                    {{ $deviceData['deviceAssigned']->login_to_device == 1 ? 'checked' : '' }}
                                                    name="status" id="loginStatus" disabled value="loginStatus">

                                                <label class="form-check-label" for="loginStatus">
                                                    Login Status
                                                </label>
                                            </li>
                                            <li class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="status"
                                                    id="macAddressVerification" disabled value="macAddressVerification">
                                                <label class="form-check-label" for="macAddressVerification">
                                                    Mac Address Verification
                                                </label>
                                            </li>
                                            <li class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="status"
                                                    id="apiKeyVerification" disabled value="apiKeyVerification">
                                                <label class="form-check-label" for="apiKeyVerification">
                                                    API Key Verification
                                                </label>
                                            </li>
                                            <li class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="status"
                                                    id="deviceSoftwareUpdate" disabled value="deviceSoftwareUpdate">
                                                <label class="form-check-label" for="deviceSoftwareUpdate">
                                                    Device Software Update
                                                </label>
                                            </li>
                                            <li class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="status"
                                                    id="securityConnection" disabled value="securityConnection">
                                                <label class="form-check-label" for="securityConnection">
                                                    Security Connection
                                                </label>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <!-- Device API Key and MAC Address -->
                            <div class="col-md-12 text-start mt-3">
                                <div class="mb-2">
                                    <a href="javascript:void(0)" class="text-primary" style="font-size: 14px;">
                                        <i class='bx bx-copy-alt'></i> {{ $deviceData->short_apikey }}
                                    </a>
                                </div>
                                <div>
                                    <a href="javascript:void(0)" class="text-success" style="font-size: 14px;">
                                        <i class='bx bx-copy-alt'></i> {{ $deviceData->mac_address }}
                                    </a>
                                </div>
                            </div>
                            <!-- Device Auth Progress (Optional) -->
                            <div class="col-md-12 mt-4">
                                <h6>Device Authentication Progress</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%;"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Steps for Device Authentication -->
            <div class="col-md-4">
                <div class="card">
                    <h5 class="card-header">Follow these steps: </h5>
                    <div class="card-body">
                        <ol class="list-group list-group-numbered">
                            <li class="">
                                Log in to the vFCL platform using your web credentials.
                            </li>
                            <li class="">
                                Enter your MAC Address and API Key into the device.
                            </li>
                            <li class="">
                                Wait for the device to respond for authorization.
                            </li>
                            <li class="">
                                Check your email and refresh this page for the device authentication message.
                            </li>
                            <li class="">
                                Authorize your device through the vFCL web platform by clicking the Auth button.
                            </li>
                            <li class="">
                                Wait for the security connections to establish.
                            </li>
                            <li class="">
                                Device authorization is now complete!
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('devices.customer-device-Show-js')
