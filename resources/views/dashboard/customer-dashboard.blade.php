@extends('layouts.customer-app')
<style>
    .pulse {
        animation: pulse-animation 1.5s infinite;
    }

    /* Blinking animation */
    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .blink-button {
        animation: blink 1.5s infinite;
        text-decoration: none;
        color: #fff;
    }

    @keyframes pulse-animation {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.05);
            opacity: 0.7;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Add some more padding for the card header */
    .card-header {
        font-weight: bold;
        background-color: #f8f9fa;
        border-bottom: 2px solid #007bff;
    }

    /* Style the card values */
    .card-value {
        font-size: 1.75rem;
        color: #333;
    }

    /* Add shadows and padding for the cards */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    /* Icons for a visual appeal */
    .icon-container {
        margin-bottom: 10px;
    }

    /* Responsive Design for small devices */
    @media (max-width: 576px) {
        .card-title {
            font-size: 0.875rem;
        }

        .card-value {
            font-size: 1.25rem;
        }
    }
</style>
@section('content')
    <div class="container-xxl flex-grow-1">
        @if ($unAuthnewDevices->isNotEmpty())
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Customer /</span> Dashboard
            </h4>
            <h5 class="card-title text-primary">Hey, {{ Auth::user()->fname }}
                {{ Auth::user()->lname }} Welcome to {{ env('APP_SHORT_NAME') }} 🥳</h5>

            <p class="mb-4 insturctions-steps">
                Click Here to get Device Authentication steps: <a href="{{ route('devices.index') }}"
                    class="auth-steps">Device
                    Authentication</a>
            </p>

            <div class="row gy-4">
                <div class="col-lg-12 mb-4 order-0">
                    <div class="d-flex align-items-end row">
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-6 mb-4">
                                <!-- Device Messages Card -->
                                <div class="card shadow-sm device-msg-card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="icon-container">
                                                <!-- Using FontAwesome for better icons -->
                                                <i class="fas fa-comments fa-2x"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1">Device Messages</span>
                                        <h3 class="card-title font-weight-bold mb-2">0</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-12 col-6 mb-4">
                                <!-- Total Faults Card -->
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="icon-container">
                                                <!-- Using FontAwesome for better icons -->
                                                <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1">Total Faults</span>
                                        <h3 class="card-title text-danger font-weight-bold mb-2">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12 col-6 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <!-- Section for Total Devices -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <span class="text-muted">Total Devices</span>
                                                <h3 class="card-title text-primary text-nowrap mb-0">0</h3>
                                            </div>
                                            <div>
                                                <!-- Icon for Total Devices (Optional, adjust as needed) -->
                                                <i class="fas fa-server fa-2x text-info"></i>
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Section for Active Devices -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted">Total Active Devices</span>
                                                <h3 class="card-title text-success text-nowrap mb-0">0</h3>
                                            </div>
                                            <div>
                                                <!-- Icon for Active Devices, indicates an issue or bug -->
                                                <i class="fas fa-bug fa-2x text-danger"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12 col-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0">
                                                <!-- Using FontAwesome location icon -->
                                                <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                                            </div>

                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" id="cardOpt6"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">

                                                    <a class="dropdown-item" href="{{ route('users.index') }}"><i
                                                            class='bx bx-list-ol'></i>
                                                        View All </a>
                                                    <a class="dropdown-item" href="{{ route('users.create') }}"> <i
                                                            class='bx bx-plus'></i>Create New </a>
                                                </div>
                                            </div>
                                        </div>
                                        <span>Locations</span>
                                        <h2 class="mb-0 me-2">{{ $locationCount }}</h2>
                                        <small class="text-success">Total</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8 col-lg-8 order-1 order-md-3 order-lg-2 mb-4">
                                <div class="card shadow-sm">
                                    <div class="row row-bordered g-0">
                                        <!-- Section Header -->
                                        <div class="col-md-12">
                                            <h5 class="card-header m-0 me-2 pb-3 text-primary">Power Consumption Overview:
                                                Total
                                                and Active Power</h5>
                                        </div>

                                        <!-- Small Cards Section -->
                                        <div class="row px-3 py-3">
                                            <!-- Total Power (PQ) -->
                                            <div class="col-md-3 col-sm-6 mb-4">
                                                <div class="card bg-light text-dark border-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <div class="icon-container mb-2">
                                                            <i class="fas fa-bolt fa-2x text-warning"></i>
                                                        </div>
                                                        <h6 class="card-title">Total Power (PQ)</h6>
                                                        <h3 class="card-value font-weight-bold" id="total-power">-- W</h3>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Active Power (P) -->
                                            <div class="col-md-3 col-sm-6 mb-4">
                                                <div class="card bg-light text-dark border-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <div class="icon-container mb-2">
                                                            <i class="fas fa-plug fa-2x text-success"></i>
                                                        </div>
                                                        <h6 class="card-title">Active Power (P)</h6>
                                                        <h3 class="card-value font-weight-bold" id="active-power">-- W</h3>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Reactive Power (Q) -->
                                            <div class="col-md-3 col-sm-6 mb-4">
                                                <div class="card bg-light text-dark border-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <div class="icon-container mb-2">
                                                            <i class="fas fa-wave-square fa-2x text-danger"></i>
                                                        </div>
                                                        <h6 class="card-title">Reactive Power (Q)</h6>
                                                        <h3 class="card-value font-weight-bold" id="reactive-power">-- VAR
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Voltage RMS (Vabc) -->
                                            <div class="col-md-3 col-sm-6 mb-4">
                                                <div class="card bg-light text-dark border-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <div class="icon-container mb-2">
                                                            <i class="fas fa-tachometer-alt fa-2x text-primary"></i>
                                                        </div>
                                                        <h6 class="card-title">Voltage RMS (Vabc)</h6>
                                                        <h3 class="card-value font-weight-bold" id="voltage-rms">-- V</h3>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Current RMS (Iabc) -->
                                            <div class="col-md-3 col-sm-6 mb-4">
                                                <div class="card bg-light text-dark border-0 shadow-sm">
                                                    <div class="card-body text-center">
                                                        <div class="icon-container mb-2">
                                                            <i class="fas fa-battery-half fa-2x text-info"></i>
                                                        </div>
                                                        <h6 class="card-title">Current RMS (Iabc)</h6>
                                                        <h3 class="card-value font-weight-bold" id="current-rms">-- A</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 order-2 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title m-0 me-2">Device and Data Integration</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <!-- Device Connection -->
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-link me-3 fa-lg text-info"></i>
                                                <!-- Device connection icon -->
                                                <span class="fw-bold">Device Connection:</span>
                                                <span class="ms-auto">Connected</span>
                                            </li>
                                            <!-- Device Updates -->
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-sync-alt me-3 fa-lg text-warning"></i>
                                                <!-- Device updates icon -->
                                                <span class="fw-bold">Device Updates:</span>
                                                <span class="ms-auto">Latest</span>
                                            </li>
                                            <!-- Last Sync -->
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-clock me-3 fa-lg text-success"></i>
                                                <!-- Device last sync icon -->
                                                <span class="fw-bold">Last Sync:</span>
                                                <span class="ms-auto">2 hours ago</span>
                                            </li>
                                            <!-- Device Encryption -->
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-lock me-3 fa-lg text-danger"></i>
                                                <!-- Device encryption icon -->
                                                <span class="fw-bold">Encryption:</span>
                                                <span class="ms-auto">True</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Order Statistics -->
                            <div class="col-8 col-lg-8 order-1 order-md-3 order-lg-2 mb-4">
                                <div class="card">
                                    <div class="row row-bordered g-0">
                                        <!-- Left Section for Line Chart -->
                                        <div class="col-md-12">
                                            <h5 class="card-header m-0 me-2 pb-3">Electric Waves</h5>
                                            <div id="device-fault-line-chart" class="px-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xl-4 order-1 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2">Device Fault Statastics</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <div id="device-fault-status-shown" class="no-device-msg">
                                                <img src="../assets/img/icons/unicons/error.png" alt=""
                                                    class="rounded no-device-img" />
                                                <b class="mt-3">No Device Selected</b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row flex-xl-nowrap">
                <div class="DocSearch-content col-12 container-p-y">
                    <h2 class="mb-6 doc-page-title">Hey {{ Auth::user()->fname }} {{ Auth::user()->lname }}, welcome to
                        {{ env('APP_SHORT_NAME') }} 🚀</h2>
                    <p class="lead">
                        We're excited to have you onboard. Dive into your personalized IoT dashboard and start exploring the
                        smart features and real-time insights we have to offer. Let's make the future smarter together! 🚀
                    </p>
                    <p class="lead">Click On the Device <code>Device Quick Start Manual</code> to bootup the device.</p>
                    <hr class="my-12">

                    <div class="row">
                        <div class="col-md-4 mb-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="badge bg-label-danger p-4 rounded mb-4">
                                        <i class="bx bx-rocket fs-3"></i>
                                    </div>
                                    <h4>Device Quick Start Manual</h4>
                                    <p>Get your IoT device up and running with the vFCL Web Platform. Simply click "Start
                                        Now" to begin setting up your device and unlock powerful insights and control.</p>

                                    <p class="fw-bold mb-0"><a class="stretched-link"
                                            href="{{ route('quickStart') }}">Start now
                                            <i class="bx bx-chevron-right"></i></a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="badge bg-label-warning p-4 rounded mb-4">
                                        <i class="bx bx-slider-alt fs-3"></i>
                                    </div>
                                    <h4>Device Authentication Manual 🤩</h4>
                                    <p>Authenticate your IoT device effortlessly by logging in and inserting your API keys.
                                        No complex configurations required, just quick and secure setup.</p>
                                    <p class="fw-bold mb-0"><a class="stretched-link"
                                            href="{{ route('devices.index') }}">Start
                                            Authentication <i class="bx bx-chevron-right"></i></a></p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row flex-xl-nowrap">
                <div class="DocSearch-content col-12 container-p-y">
                    <h2 class="mb-6 doc-page-title">My Devices</h2>
                    <div class="row" id="myDevices">
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="container-xxl flex-grow-1">
        @if ($unAuthnewDevices->isNotEmpty())
            <div class="row">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="card">
                            <h5 class="card-header">Devices Data</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table dashboard-devices-ajax-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Device Name</th>
                                            <th>Device Status</th>
                                            <th>Health Status</th>
                                            <th>Fault Status</th>
                                            <th>Timestamps</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- First Modal HTML -->
        <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="mt-3">Device Activation Steps:</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ol class="text-start">
                            <li>Plug in the device.</li>
                            <li>Login with your Web Command Center Username and Password.</li>
                            <li>Copy the MAC address from the device.</li>
                            <li>Enter the API KEY on your device.</li>
                            <li>Wait for a moment and refresh the command center. Check your email for an authentication
                                request from the device.</li>
                            <li>Accept the Auth Request from the command center for the device.</li>
                            <li>Device Authenticated.</li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="deviceStep2" data-bs-toggle="modal" data-bs-dismiss="modal"
                            class="btn rounded-pill btn-icon btn-primary">
                            <i class='bx bx-chevron-right'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Modal HTML -->
        <div class="modal fade" id="modalToggle2" aria-labelledby="modalToggle2Label" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalToggle2Label">Device Activation Steps</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ol class="text-start mb-4">
                            <li>Log in to your vFCL Command Center account with your email: <br>
                                <b class="username"><i class='bx bx-user'></i> {{ Auth::user()->email }}</b>
                            </li>
                            <li>Enter your vFCL web password <i class='bx bx-low-vision'></i></li>
                        </ol>
                        <hr>
                        <ol class="text-start mb-4">
                            <li>Verify that the MAC address matches your device. If there is a discrepancy, please
                                contact support at <a href="mailto:support@vFCL.com">support@vFCL.com</a>.</li>
                            <li>Paste the API Key into your device and wait for the confirmation response.</li>
                        </ol>
                        <div id="secondModalContent">
                            <!-- Additional content can be dynamically inserted here -->
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" id="prevModal" data-bs-toggle="modal" data-bs-dismiss="modal"
                            class="btn rounded-pill btn-icon btn-primary">
                            <i class='bx bx-chevron-left'></i>
                        </button>
                        <button type="button" id="deviceStep3" data-bs-toggle="modal" data-bs-dismiss="modal"
                            class="btn rounded-pill btn-icon btn-primary">
                            <i class='bx bx-chevron-right'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Third Modal HTML -->
        <div class="modal fade" id="modalToggle3" aria-labelledby="modalToggle3Label" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="modalToggle3Label">Device Activation Step 3</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-center">Please enter your API KEY into the device and wait for confirmation.
                            This may take a few moments.</p>
                        <p class="text-center text-danger"><strong>Please do not turn off the device during this
                                process.</strong></p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" id="prevModal2" data-bs-toggle="modal" data-bs-dismiss="modal"
                            class="btn rounded-pill btn-icon btn-primary">
                            <i class='bx bx-chevron-left'></i>
                        </button>
                        <button type="button" id="deviceStep4" data-bs-toggle="modal" data-bs-dismiss="modal"
                            class="btn rounded-pill btn-icon btn-primary">
                            <i class='bx bx-chevron-right'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Fourth Modal HTML -->
        <div class="modal fade" id="modalToggle4" aria-labelledby="modalToggle4Label" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalToggle4Label">Device Activation Complete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <p style="font-size: 41px;">🥳</p>
                        </div>
                        <p class="text-center">Congratulations! Your device has been successfully authenticated and is
                            now ready for use. Enjoy your new device experience.</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" id="prevModal3" data-bs-toggle="modal" data-bs-dismiss="modal"
                            class="btn rounded-pill btn-icon btn-primary">
                            <i class='bx bx-chevron-left'></i>
                        </button>
                        <button type="button" class="btn rounded-pill btn-icon btn-success" data-bs-dismiss="modal">
                            <i class='bx bx-check'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="terms-conditions" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalScrollableTitle">vFCL Platform Terms and Conditions and Privacy
                            Policies</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <p>
                            Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                            in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                        </p>
                        <p>
                            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                            lacus vel augue laoreet rutrum faucibus dolor auctor.
                        </p>
                        <p>
                            Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                            scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                            auctor fringilla.
                        </p>
                        <form method="post" id="terms-and-conditions-form">
                            @csrf
                            <div class="form-check">
                                <input class="form-check-input mt-2" type="checkbox" name="terms_and_conditions"
                                    id="terms_and_conditions">
                                <label class="form-check-label" for="terms_and_conditions"> I Agree to the terms and
                                    conditions
                                </label>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="password-change-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-users-change-password" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="fname" class="form-label">Old Password
                                        {!! dynamicRedAsterisk() !!}</label>
                                    <div class="input-group input-group-merge">
                                        {!! Form::password('oldpassword', [
                                            'placeholder' => 'Old Password',
                                            'id' => 'oldpassword',
                                            'class' => 'form-control',
                                        ]) !!}
                                        <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                                class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <div class="form-password-toggle">
                                        <label class="form-label" for="password">Password
                                            {!! dynamicRedAsterisk() !!}</label>
                                        <div class="input-group input-group-merge">
                                            {!! Form::password('password', ['placeholder' => 'Password', 'id' => 'password', 'class' => 'form-control']) !!}
                                            <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                                    class="bx bx-hide"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <div class="form-password-toggle">
                                        <label class="form-label" for="confirm-password">Confirm Password
                                            {!! dynamicRedAsterisk() !!}</label>
                                        <div class="input-group input-group-merge">
                                            {!! Form::password('confirm-password', [
                                                'placeholder' => 'Confirm Password',
                                                'id' => 'confirm_password',
                                                'class' => 'form-control',
                                            ]) !!}
                                            <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                                    class="bx bx-hide"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" id="submit" class="submit btn btn-primary me-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <h3>Verify This Device!!</h3>
                            <p>Please confirm that you want to verify this device to ensure proper functionality and data
                                visualization. This process is necessary to unlock full device capabilities and ensure
                                security
                                compliance.</p>
                            <p id="modalContent"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    @include('dashboard.customer-dashboard-js')
