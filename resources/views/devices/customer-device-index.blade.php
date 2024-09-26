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
            <!-- Device cards will be injected here by the JavaScript code -->
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
@endsection

@include('devices.customer-device-js')
