@extends('layouts.customer-app')
@section('content')
    <div class="container-xl flex-grow-1">
        <!-- Top Section: Welcome Message and Device Manual Button -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-1">
            <div>
                <h2 class="fw-bold">👋 Hey, {{ Auth::user()->fname }}! Welcome to vFCL IoT Device 🎉</h2>
                <p style="font-size: 18px; color:#333;">
                    Please ensure your device is connected to the internet before proceeding.<br>
                    Do not close this page until the process is complete.
                </p>
            </div>
            <div>
                <a href="{{ route('quickStart') }}" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i> Device Manual
                </a>
            </div>
        </div>
        <hr class="my-4">
        <!-- Main Content Area -->
        <div class="row mb-4">
            <!-- Left Column: Device Info and Status -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div id="deviceDetails" class="text-muted">
                            <p><em>Your device information will appear here...</em></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Steps for Device Authentication -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <h5 class="card-header bg-primary text-white fw-bold">🔐 Device Authentication Steps</h5>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @php
                                $steps = [
                                    ['text' => 'Log in to the DEVICE', 'icon' => 'bi-check-circle-fill', 'color' => 'text-success'],
                                    ['text' => 'Enter MAC Address & API Key', 'icon' => 'bi-wifi', 'color' => 'text-warning'],
                                    ['text' => 'Check your Email', 'icon' => 'bi-envelope-open-fill', 'color' => 'text-warning'],
                                    ['text' => 'Authorize Your Device', 'icon' => 'bi-check-circle', 'color' => 'text-warning'],
                                    ['text' => 'Establish Security Connections', 'icon' => 'bi-shield-lock-fill', 'color' => 'text-warning'],
                                    ['text' => 'Authorization Complete!', 'icon' => 'bi-check-circle-fill', 'color' => 'text-success'],
                                ];
                            @endphp
                            @foreach ($steps as $index => $step)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><strong>Step {{ $index + 1 }}:</strong> {{ $step['text'] }}</span>
                                    <i class="bi {{ $step['icon'] }} {{ $step['color'] }}" style="font-size: 1.5em;"></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for device verification -->
        <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title mx-auto">Device Verification</h5>
                        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h3 class="mb-3">Verify This Device!</h3>
                        <p>Please confirm that you want to verify this device to ensure proper functionality and data visualization.</p>
                        <p id="modalContent"></p>
                        <button class="btn btn-primary w-100 mt-4">Verify Device</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Include necessary JS and CSS files -->
@push('styles')
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
@endpush

@include('devices.customer-device-Show-js')
