@php
    $layout = isSuperAdmin()
        ? 'layouts.app'
        : 'layouts.customer-app';
@endphp
@extends($layout)
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mt-1">
            <h4 class="fw-bold mb-3">
                <span class="text-muted fw-light">Customer / Dashboard /</span> Device Overview
            </h4>
            <a href="{{ route('devices.index') }}" class="btn btn-primary">
                My Devices <i class="fas fa-microchip ms-2"></i>
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold d-flex align-items-center">
                <i class="bx bxs-devices fx-2 me-2 text-primary"></i>
                <span id="deviceName" class="text-primary">Device Name</span>
                <small class="ms-3 d-flex flex-wrap">
                    <span class="badge bg-success me-2" id="deviceHealth" title="Device Health"></span>
                    <span class="badge bg-primary me-2" id="mqttStatus" title="MQTT Status"></span>
                    <span class="badge bg-secondary me-2" id="encryptionStatus" title="Encryption Status"></span>
                    <span class="text-muted me-2" id="lastSync" title="Last Sync Time"></span>
                    <span class="text-muted" id="dataCommunication" title="Communication Status"></span>
                </small>
            </h4>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-9">
                <div class="card shadow-sm p-4 mb-4">
                    <h5 class="text-secondary fw-bold mb-3">Wave Metrics</h5>
                    <div id="currentChart" class="mb-3"></div>
                    <div id="voltageChart" class="mb-3"></div>
                    <div id="powerChart" class="mb-3"></div>
                    <div id="metrics"></div>
                </div>

                <!-- HTML for metric boxes -->
                <div class="row g-3">
                    @foreach ([['fas fa-bolt', 'Total Power (PQ)', 'total-power', ' W', 'text-warning'], ['fas fa-plug', 'Active Power (P)', 'active-power', ' W', 'text-success'], ['fas fa-wave-square', 'Reactive Power (Q)', 'reactive-power', ' VAR', 'text-danger'], ['fas fa-tachometer-alt', 'Voltage RMS (Vabc)', 'voltage-rms', ' V', 'text-primary'], ['fas fa-battery-half', 'Current RMS (Iabc)', 'current-rms', ' A', 'text-info']] as $metric)
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="card bg-light border-0 shadow-sm text-center p-3">
                                <div class="icon-container mb-2">
                                    <i class="{{ $metric[0] }} fa-lg {{ $metric[4] }}"></i>
                                </div>
                                <h6 class="card-title" style="font-size: 0.85rem; color:black;">{{ $metric[1] }}</h6>
                                <h4 class="font-weight-bold" id="{{ $metric[2] }}" style="font-size: 1.25rem;">
                                    --{{ $metric[3] }}
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card p-4 mb-3 shadow-sm">
                    <h5 class="text-primary fw-bold text-center mb-4">
                        ⚠️ Fault Detection
                    </h5>
                    <div id="device-fault-status-shown"></div>
                </div>

                <div class="card p-4 mb-3 shadow-sm text-center">
                    <h5 class="fw-bold text-primary mb-4">
                        <i class="bx bx-map me-2"></i> Device Location
                    </h5>
                    <div>
                        <h6 class="text-muted mb-1">Location Type:</h6>
                        <p class="fs-3 fw-bold" id="locationName">Home</p>
                        <h6 class="text-muted mb-1">Address:</h6>
                        <p class="fs-6 text-dark mb-0" id="locationAddress">Loading address...</p>
                    </div>
                </div>

                <div class="card p-4 shadow-sm">
                    <h5 class="text-secondary fw-bold mb-3">Device Control Panel</h5>
                    @foreach ([['Data Communication', 'fridgeSwitch', true], ['Daily Sync', 'vacuumSwitch', false], ['Updates', 'kettleSwitch', true]] as $control)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>{{ $control[0] }}</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="{{ $control[1] }}"
                                    {{ $control[2] ? 'checked' : '' }}>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@include('devices.customer-device-dashboard-js')
