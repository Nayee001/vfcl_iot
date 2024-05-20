@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Device Management
            </h4>
        </div>
        <div class="row g-4" id="devices">
            <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.jpg') }}" alt="No Devices" width="500"
                class="no-device img-fluid" />
            <p>No Device Assined to you !</p>
        </div>
    </div>

    <!-- Modal -->

    <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h3>Verify This Device!!</h3>
                        <p>Please confirm that you want to verify this device to ensure proper functionality and data
                            visualization. This process is necessary to unlock full device capabilities and ensure security
                            compliance.</p>
                        <p id="modalContent"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('devices.customer-device-js')
