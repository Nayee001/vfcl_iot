@extends('layouts.customer-app')
@section('content')
    <div class="container-xxl flex-grow-1">

        @if ($showNewUserModel == false)
            <div class="row align-items-center mb-4">
                <!-- Left Column: Welcome Message -->
                <div class="card bg-transparent shadow-none my-6 border-0">
                    <div class="card-body row p-0 pb-6 g-6">
                        <div class="col-12 col-lg-8 card-separator">
                            <h5 class="mb-2">Welcome back,<span class="h4"> {{ Auth::user()->fname }}
                                    {{ Auth::user()->lname }} 🎉</span></h5>
                            <div class="col-12 col-lg-5">
                                <p>We're delighted to have you on board. Dive into your personalized dashboard to explore
                                    the exciting
                                    features we've crafted just for you.</p>
                            </div>
                            <div class="d-flex justify-content-between flex-wrap gap-4 me-12">
                                <div class="d-flex align-items-center gap-4 me-6 me-sm-0">
                                    <div class="avatar avatar-lg">
                                        <div class="avatar-initial bg-label-info rounded">
                                            <div class="text-info">
                                                <svg width="38" height="38" viewBox="0 0 38 38" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g id="Laptop">
                                                        <path id="Vector" opacity="0.2"
                                                            d="M5.9375 26.125V10.6875C5.9375 10.0576 6.18772 9.45352 6.63312 9.00812C7.07852 8.56272 7.68261 8.3125 8.3125 8.3125H29.6875C30.3174 8.3125 30.9215 8.56272 31.3669 9.00812C31.8123 9.45352 32.0625 10.0576 32.0625 10.6875V26.125H5.9375Z"
                                                            fill="currentColor"></path>
                                                        <path id="Vector_2"
                                                            d="M5.9375 26.125V10.6875C5.9375 10.0576 6.18772 9.45352 6.63312 9.00812C7.07852 8.56272 7.68261 8.3125 8.3125 8.3125H29.6875C30.3174 8.3125 30.9215 8.56272 31.3669 9.00812C31.8123 9.45352 32.0625 10.0576 32.0625 10.6875V26.125M21.375 13.0625H16.625M3.5625 26.125H34.4375V28.5C34.4375 29.1299 34.1873 29.734 33.7419 30.1794C33.2965 30.6248 32.6924 30.875 32.0625 30.875H5.9375C5.30761 30.875 4.70352 30.6248 4.25812 30.1794C3.81272 29.734 3.5625 29.1299 3.5625 28.5V26.125Z"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content-right">
                                        <p class="mb-0 fw-medium">Total Devices</p>
                                        <h2 class="font-weight-bold mb-0">{{ $getDeviceTotalCount }}</h2>
                                        <small class="text-muted">Devices registered</small>
                                        <div class="progress mt-3" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ ($getTotalActiveDevice / max($getDeviceTotalCount, 1)) * 100 }}%;"
                                                aria-valuenow="{{ $getTotalActiveDevice }}" aria-valuemin="0"
                                                aria-valuemax="{{ $getDeviceTotalCount }}"></div>
                                        </div>
                                        <small class="text-muted">{{ $getTotalActiveDevice }} Active Devices</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-4">
                                    <div class="avatar avatar-lg">
                                        <div class="avatar-initial bg-label-success rounded">
                                            <div class="text-success">
                                                <i class="fas fa-heartbeat fa-1x"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content-right">
                                        <p class="mb-0 fw-medium">Device System Health</p>
                                        <h4 class="text-success mb-0">82%</h4>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-4">
                                    <div class="avatar avatar-lg">
                                        <div class="avatar-initial bg-label-warning rounded">
                                            <div class="text-warning">
                                                <svg width="38" height="38" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 2C8.13 2 5 5.13 5 9C5 13.25 9.5 19.25 11.24 21.44C11.64 21.95 12.36 21.95 12.76 21.44C14.5 19.25 19 13.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z"
                                                        fill="currentColor" />
                                                </svg>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="content-right">
                                        <p class="mb-0 fw-medium">Locations</p>
                                        <h4 class="text-warning mb-0">{{ $locationCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 ps-md-4 ps-lg-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div>
                                        <h5 class="mb-1">Total Faults</h5>
                                        {{-- <p class="mb-9">Weekly report</p> --}}
                                    </div>
                                    <div class="time-spending-chart">
                                        <h4 class="mb-2">
                                            <span id="totalFaults">0</span>
                                        </h4>
                                    </div>
                                </div>
                                <div id="faultChartContainer" style="min-height: 157px;">
                                    <canvas id="faultChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gy-4">
                    <!-- Power Consumption Overview -->
                    <div class="col-12 col-lg-8 mb-4">
                        <div class="card shadow-sm">
                            <div class="row row-bordered g-0">
                                <!-- Section Header -->
                                <div class="col-12">
                                    <h5 class="card-header m-0 me-2 pb-3 text-primary">
                                        Power Consumption Overview: Total and Active Power
                                    </h5>
                                </div>

                                <!-- Small Cards Section -->
                                <div class="row px-3 py-3">
                                    <!-- Total Power (PQ) -->
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
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
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
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
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card bg-light text-dark border-0 shadow-sm">
                                            <div class="card-body text-center">
                                                <div class="icon-container mb-2">
                                                    <i class="fas fa-wave-square fa-2x text-danger"></i>
                                                </div>
                                                <h6 class="card-title">Reactive Power (Q)</h6>
                                                <h3 class="card-value font-weight-bold" id="reactive-power">-- VAR</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Voltage RMS (Vabc) -->
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
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
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
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
                                <!-- End of Small Cards Section -->
                            </div>
                        </div>
                    </div>

                    <!-- Device and Data Integration -->
                    <div class="col-12 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Device and Data Integration</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <!-- Device Connection -->
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-link me-3 fa-lg text-info"></i>
                                        <span class="fw-bold">Device Connection:</span>
                                        <span class="ms-auto">Connected</span>
                                    </li>
                                    <!-- Device Updates -->
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-sync-alt me-3 fa-lg text-warning"></i>
                                        <span class="fw-bold">Device Updates:</span>
                                        <span class="ms-auto">Latest</span>
                                    </li>
                                    <!-- Last Sync -->
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-clock me-3 fa-lg text-success"></i>
                                        <span class="fw-bold">Last Sync:</span>
                                        <span class="ms-auto">2 hours ago</span>
                                    </li>
                                    <!-- Device Encryption -->
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-lock me-3 fa-lg text-danger"></i>
                                        <span class="fw-bold">Encryption:</span>
                                        <span class="ms-auto">True</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Electric Waves and Device Fault Statistics -->
                    <div class="col-12 col-lg-8 mb-4">
                        <div class="card">
                            <div class="row row-bordered g-0">
                                <!-- Electric Waves Section -->
                                <div class="col-12">
                                    <h5 class="card-header m-0 me-2 pb-3">Electric Waves</h5>
                                    <div id="chart" class="px-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Device Fault Statistics -->
                    <div class="col-12 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2">Device Fault Statistics</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-12">
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
            @else
                <!-- Content for when $unAuthnewDevices is empty -->
                <div class="row">
                    <div class="col-12 container-p-y">
                        <h2 class="mb-6 doc-page-title">
                            Hey {{ Auth::user()->fname }} {{ Auth::user()->lname }}, welcome to
                            {{ env('APP_SHORT_NAME') }} 🚀
                        </h2>
                        <p class="lead">
                            We're excited to have you onboard. Dive into your personalized IoT dashboard and start exploring
                            the
                            smart features and real-time insights we have to offer. Let's make the future smarter together!
                            🚀
                        </p>
                        <p class="lead">
                            Click on the <code>Device Quick Start Manual</code> to boot up the device.
                        </p>
                        <hr class="my-4">

                        <div class="row">
                            <!-- Device Quick Start Manual -->
                            <div class="col-12 col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="badge bg-label-danger p-4 rounded mb-4">
                                            <i class="bx bx-rocket fs-3"></i>
                                        </div>
                                        <h4>Device Quick Start Manual</h4>
                                        <p>
                                            Get your IoT device up and running with the vFCL Web Platform. Simply click
                                            "Start
                                            Now" to begin setting up your device and unlock powerful insights and control.
                                        </p>
                                        <p class="fw-bold mb-0">
                                            <a class="stretched-link" href="{{ route('quickStart') }}">
                                                Start now <i class="bx bx-chevron-right"></i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Device Authentication Manual -->
                            <div class="col-12 col-md-6 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="badge bg-label-warning p-4 rounded mb-4">
                                            <i class="bx bx-slider-alt fs-3"></i>
                                        </div>
                                        <h4>Device Authentication Manual 🤩</h4>
                                        <p>
                                            Authenticate your IoT device effortlessly by logging in and inserting your API
                                            keys.
                                            No complex configurations required, just quick and secure setup.
                                        </p>
                                        <p class="fw-bold mb-0">
                                            <a class="stretched-link" href="{{ route('devices.index') }}">
                                                Start Authentication <i class="bx bx-chevron-right"></i>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h2 class="mb-6 doc-page-title">My Devices</h2>
                        <div class="row" id="myDevices">
                            <!-- Devices will be loaded here -->
                        </div>
                    </div>
                </div>
        @endif
    </div>


    <div class="container-xxl flex-grow-1">
        <div class="row">
            <div class="col-12">
                <h1>My Devices</h1>
                <!-- Warning Message -->
                <div id="top-message"></div>

                <!-- Device Cards Container -->
                <div class="row" id="myDevices">
                    <!-- Device cards will be injected here -->
                </div>

            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="terms-conditions" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold" id="modalScrollableTitle">
                        Terms and Conditions & Privacy Policies
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
                                    <!-- Content truncated for brevity -->
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
                                    <!-- Content truncated for brevity -->
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
    <!-- End of Terms and Conditions Modal -->

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
                            <small class="form-text text-muted">Use at least 8 characters with a mix of letters, numbers,
                                and symbols.</small>
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
                            <button type="submit" id="submit" class="btn btn-primary btn-lg shadow-sm rounded-pill">
                                <i class="bx bx-save"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Password Change Modal -->

    <!-- Verification Modal -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3>Verify This Device!!</h3>
                    <p>
                        Please confirm that you want to verify this device to ensure proper functionality and data
                        visualization. This process is necessary to unlock full device capabilities and ensure security
                        compliance.
                    </p>
                    <p id="modalContent"></p>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Verification Modal -->

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
                    <button type="button" class="btn btn-primary" onclick="redirectToDeviceShow()">Authorize
                        Now</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('dashboard.customer-dashboard-js')
