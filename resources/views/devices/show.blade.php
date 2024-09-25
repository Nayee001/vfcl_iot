@extends('layouts.customer-app')
@section('content')
    <div class="container-xl flex-grow-1">
        <!-- Welcome Message -->
        <div class="text-center mb-4 mt-1">
            <h2 class="fw-bold">👋 Hey, {{ Auth::user()->fname }}! Welcome to vFCL IoT Device 🎉</h2>
            <p style="font-size: 18px; color:#333;">
                Please ensure your device is connected to the internet before proceeding. <br> Do not close this page until the process is complete.
            </p>
        </div>
        <hr class="my-4">
        <!-- Main Content Area -->
        <div class="row mb-4">
            <!-- Left Column: Device Info and Status -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="card-title text-primary mb-4">Device Details</h4>
                        <div id="deviceDetails" class="text-muted">
                            <p><em>Your device information will appear here...</em></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Steps for Device Authentication -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <h5 class="card-header bg-primary text-white fw-bold">🔐 Device Authentication Steps</h5>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Step 1:</strong> Log in to the DEVICE</span>
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5em;"></i>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Step 2:</strong> Enter MAC Address & API Key</span>
                                <i class="bi bi-wifi" style="font-size: 1.5em; color: #f0ad4e;"></i>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Step 3:</strong> Check your Email</span>
                                <i class="bi bi-envelope-open-fill" style="font-size: 1.5em; color: #f0ad4e;"></i>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Step 4:</strong> Authorize Your Device</span>
                                <i class="bi bi-check-circle" style="font-size: 1.5em; color: #f0ad4e;"></i>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Step 5:</strong> Establish Security Connections</span>
                                <i class="bi bi-shield-lock-fill" style="font-size: 1.5em; color: #f0ad4e;"></i>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>Step 6:</strong> Authorization Complete!</span>
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5em;"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Modal for device verification -->
            <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Device Verification</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <h3>Verify This Device!</h3>
                            <p>Please confirm that you want to verify this device to ensure proper functionality and data visualization.</p>
                            <p id="modalContent"></p>
                            <button class="btn btn-primary w-100 mt-4">Verify Device</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('devices.customer-device-Show-js')
