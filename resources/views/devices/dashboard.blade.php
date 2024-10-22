@extends('layouts.customer-app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mt-1">
            <h4 class="fw-bold mb-3">
                <span class="text-muted fw-light">Customer / Dashboard /</span> Device
            </h4>
            <div>
                <a href="{{ route('devices.index') }}" class="btn btn-primary">
                    My Devices <i class="fas fa-microchip"></i>
                </a>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-3 d-flex align-items-center">
                <i class='fx-2 bx bxs-devices me-2'></i>
                <span id="deviceName" class="text-primary">Device Name</span>

                <!-- Small text for statuses -->
                <small class="ms-3 d-flex flex-wrap">
                    <!-- Device Health -->
                    <span class="badge bg-success me-2" id="deviceHealth"></span>
                    <!-- MQTT Connection Status -->
                    <span class="badge bg-primary me-2" id="mqttStatus"></span>
                    <!-- Encryption Status -->
                    <span class="badge bg-secondary me-2" id="encryptionStatus"></span>
                    <!-- Last Sync Time -->
                    <span class="text-muted me-2" id="lastSync"></span>
                    <!-- Data Communication Status -->
                    <span class="text-muted" id="dataCommunication"></span>
                </small>
            </h4>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-9">

                <!-- Spending Chart -->
                <div class="card p-4 mb-3 shadow-sm">
                    <div id="currentChart" class="chart-container"></div>
                    <div id="voltageChart" class="chart-container"></div>
                    <div id="powerChart" class="chart-container"></div>
                </div>
                <div class="row px-3 py-3">
                    <!-- Total Power (PQ) -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="card bg-light text-dark border-0 shadow-sm" style="padding: 10px;">
                            <div class="card-body text-center p-2">
                                <div class="icon-container mb-1">
                                    <i class="fas fa-bolt fa-lg text-warning"></i>
                                </div>
                                <h6 class="card-title" style="font-size: 0.85rem;">Total Power (PQ)</h6>
                                <h4 class="card-value font-weight-bold" id="total-power" style="font-size: 1.25rem;">-- W
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Active Power (P) -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="card bg-light text-dark border-0 shadow-sm" style="padding: 10px;">
                            <div class="card-body text-center p-2">
                                <div class="icon-container mb-1">
                                    <i class="fas fa-plug fa-lg text-success"></i>
                                </div>
                                <h6 class="card-title" style="font-size: 0.85rem;">Active Power (P)</h6>
                                <h4 class="card-value font-weight-bold" id="active-power" style="font-size: 1.25rem;">-- W
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Reactive Power (Q) -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="card bg-light text-dark border-0 shadow-sm" style="padding: 10px;">
                            <div class="card-body text-center p-2">
                                <div class="icon-container mb-1">
                                    <i class="fas fa-wave-square fa-lg text-danger"></i>
                                </div>
                                <h6 class="card-title" style="font-size: 0.85rem;">Reactive Power (Q)</h6>
                                <h4 class="card-value font-weight-bold" id="reactive-power" style="font-size: 1.25rem;">--
                                    VAR</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Voltage RMS (Vabc) -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="card bg-light text-dark border-0 shadow-sm" style="padding: 10px;">
                            <div class="card-body text-center p-2">
                                <div class="icon-container mb-1">
                                    <i class="fas fa-tachometer-alt fa-lg text-primary"></i>
                                </div>
                                <h6 class="card-title" style="font-size: 0.85rem;">Voltage RMS (Vabc)</h6>
                                <h4 class="card-value font-weight-bold" id="voltage-rms" style="font-size: 1.25rem;">-- V
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Current RMS (Iabc) -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-3">
                        <div class="card bg-light text-dark border-0 shadow-sm" style="padding: 10px;">
                            <div class="card-body text-center p-2">
                                <div class="icon-container mb-1">
                                    <i class="fas fa-battery-half fa-lg text-info"></i>
                                </div>
                                <h6 class="card-title" style="font-size: 0.85rem;">Current RMS (Iabc)</h6>
                                <h4 class="card-value font-weight-bold" id="current-rms" style="font-size: 1.25rem;">-- A
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Weather and Devices -->
            <div class="col-lg-3">
                <!-- At Home Now -->
                <div class="card p-4 mb-3 shadow-sm">
                    <h5 class="fw-bold text-primary mb-4 d-flex justify-content-center align-items-center">
                        ⚠️ Fault Detection
                    </h5>
                    <div id="device-fault-status-shown"></div> <!-- Dynamic content for fault detection -->
                </div>

                <!-- Location -->
                <div class="card p-4 mb-3 shadow-sm text-center"
                    style="border-left: 5px solid #0d6efd; border-radius: 8px;">
                    <h5 class="fw-bold text-primary mb-4 d-flex justify-content-center align-items-center">
                        <i class="bx bx-map me-2"></i> Device Location
                    </h5>

                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <!-- Location Name with Home Emoji -->
                        <div>
                            <h6 class="text-muted mb-1">Location Type:</h6>
                            <p class="fs-3 fw-bold text-dark" id="locationName">
                                Home
                            </p>
                        </div>

                        <!-- Address -->
                        <div>
                            <h6 class="text-muted mb-1">Address:</h6>
                            <p class="fs-6 text-dark mb-0" id="locationAddress">
                                Loading address...
                            </p>
                        </div>
                    </div>
                </div>


                <!-- Popular Devices -->
                <div class="card p-4 shadow-sm">
                    <h5 class="text-secondary">Device Control Panel</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Data Communication</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="fridgeSwitch" checked>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Daily Sync</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="vacuumSwitch">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Updates</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="kettleSwitch" checked>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('devices.customer-device-dashboard-js')
