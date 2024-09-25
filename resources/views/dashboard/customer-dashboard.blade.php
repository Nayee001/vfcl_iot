@extends('layouts.customer-app')
<style>

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
                                            <h5 class="card-header m-0 me-2 pb-3 text-primary">Power Consumption
                                                Overview:
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
                                                        <h3 class="card-value font-weight-bold" id="total-power">-- W
                                                        </h3>
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
                                                        <h3 class="card-value font-weight-bold" id="active-power">-- W
                                                        </h3>
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
                                                        <h3 class="card-value font-weight-bold" id="reactive-power">--
                                                            VAR
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
                                                        <h3 class="card-value font-weight-bold" id="voltage-rms">-- V
                                                        </h3>
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
                                                        <h3 class="card-value font-weight-bold" id="current-rms">-- A
                                                        </h3>
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
                        We're excited to have you onboard. Dive into your personalized IoT dashboard and start exploring
                        the
                        smart features and real-time insights we have to offer. Let's make the future smarter together!
                        🚀
                    </p>
                    <p class="lead">Click On the Device <code>Device Quick Start Manual</code> to bootup the device.
                    </p>
                    <hr class="my-12">

                    <div class="row">
                        <div class="col-md-4 mb-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="badge bg-label-danger p-4 rounded mb-4">
                                        <i class="bx bx-rocket fs-3"></i>
                                    </div>
                                    <h4>Device Quick Start Manual</h4>
                                    <p>Get your IoT device up and running with the vFCL Web Platform. Simply click
                                        "Start
                                        Now" to begin setting up your device and unlock powerful insights and control.
                                    </p>

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
                                    <p>Authenticate your IoT device effortlessly by logging in and inserting your API
                                        keys.
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
        <!-- Professional Modal Structure -->
        <div class="modal fade" id="terms-conditions" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title fw-bold" id="modalScrollableTitle">Terms and Conditions & Privacy Policies
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="accordion" id="accordionExample">
                            <!-- Section 1 -->
                            <div class="accordion-item border-0 mb-3">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button bg-light text-dark fw-bold collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                        Terms of Use
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p>Cras mattis consectetur purus sit amet fermentum...</p>
                                        <!-- Shortened text for better readability -->
                                    </div>
                                </div>
                            </div>
                            <!-- Section 2 -->
                            <div class="accordion-item border-0 mb-3">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button bg-light text-dark fw-bold collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        Privacy Policies
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p>Aenean lacinia bibendum nulla sed consectetur...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agreement Section -->
                        <div class="terms-agreement mt-4">
                            <form method="post" id="terms-and-conditions-form">
                                @csrf
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="terms_and_conditions"
                                        id="terms_and_conditions">
                                    <label class="form-check-label ms-2" for="terms_and_conditions">
                                        I agree to the Terms and Conditions
                                    </label>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Change Modal -->
        <div class="modal fade" id="password-change-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header text-white">
                        <h5 class="modal-title">🔑 Change Password</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-users-change-password" method="post">
                            @csrf
                            <!-- Old Password -->
                            <div class="mb-3">
                                <label for="oldpassword" class="form-label">Old Password {!! dynamicRedAsterisk() !!}</label>
                                <div class="input-group input-group-merge position-relative">
                                    {!! Form::password('oldpassword', [
                                        'placeholder' => 'Enter Old Password',
                                        'id' => 'oldpassword',
                                        'class' => 'form-control rounded-pill shadow-sm',
                                    ]) !!}
                                    <span class="input-group-text bg-white rounded-end cursor-pointer"
                                        id="toggle-old-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Ensure your old password is correct.</small>
                            </div>
                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password {!! dynamicRedAsterisk() !!}</label>
                                <div class="input-group input-group-merge position-relative">
                                    {!! Form::password('password', [
                                        'placeholder' => 'Enter New Password',
                                        'id' => 'password',
                                        'class' => 'form-control rounded-pill shadow-sm',
                                    ]) !!}
                                    <span class="input-group-text bg-white rounded-end cursor-pointer"
                                        id="toggle-new-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Use at least 8 characters with a mix of letters,
                                    numbers, and symbols.</small>
                            </div>
                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password
                                    {!! dynamicRedAsterisk() !!}</label>
                                <div class="input-group input-group-merge position-relative">
                                    {!! Form::password('confirm-password', [
                                        'placeholder' => 'Confirm New Password',
                                        'id' => 'confirm_password',
                                        'class' => 'form-control rounded-pill shadow-sm',
                                    ]) !!}
                                    <span class="input-group-text bg-white rounded-end cursor-pointer"
                                        id="toggle-confirm-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Make sure the passwords match.</small>
                            </div>
                            <!-- Submit Button -->
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" id="submit"
                                    class="btn btn-primary btn-lg shadow-sm rounded-pill">
                                    <i class="bx bx-save"></i> Submit
                                </button>
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
