@extends('layouts.customer-app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold mb-3">
            <span class="text-muted fw-light">Customer /</span> Dashboard
        </h4>
        <!-- Hero Section -->
        <div class="hero bg-gradient mb-4 rounded-3 text-center">
            <h1 class="display-4">My Deivces 🚀</h1>
        </div>
        <hr class="my-4">
        <div id="top-message"></div>

        <!-- Devices Container -->
        <div class="row" id="devices">

        </div>

        <!-- Modal for device verification -->
        <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title mx-auto">Device Verification</h5>
                        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-2"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" id="modalContent">
                        <!-- Content will be set dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Activate Device Modal -->
    <div class="modal fade" id="activateDeviceModal" tabindex="-1" aria-labelledby="activateDeviceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content shadow-lg">
                <div class="modal-header text-white">
                    <h5 class="modal-title mx-auto" id="activateDeviceModalLabel">Device Not Authorized Yet !</h5>
                    <button type="button" class="btn-close btn-close-white position-absolute end-0 me-2"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Content will be set dynamically -->
                </div>
                <div class="modal-footer justify-content-center">
                    <!-- Optional: Include a button to start the activation process -->
                    <button type="button" class="btn btn-primary" onclick="redirectToDeviceShow()">Authorize Now</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('devices.customer-device-js')
