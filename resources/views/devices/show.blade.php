@extends('layouts.customer-app')

@section('content')
    {{-- <style>
        {
            margin-right: 15px;
        }

        .form-check-label {
            font-size: 14px;
            color: #333;
        }
    </style> --}}
    {{-- @dd($deviceData->deviceAssigned->login_to_device) --}}
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
                        <div id="deviceDetails"></div>
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
                <!-- Modal for device verification -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>Verify This Device!!</h3>
                    <p>Please confirm that you want to verify this device to ensure proper functionality and data
                        visualization.</p>
                    <p id="modalContent"></p>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
@endsection

@include('devices.customer-device-Show-js')
