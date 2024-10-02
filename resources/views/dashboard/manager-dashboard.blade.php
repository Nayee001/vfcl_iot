@extends('layouts.app')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Welcome Section -->
    <div class="row align-items-center mb-4">
        <!-- Left Column: Welcome Message -->
        <div class="col-12 col-md-8">
            <h4 class="fw-bold mb-3">
                <span class="text-muted fw-light">Manager /</span> Dashboard
            </h4>
            <h5 class="card-title text-primary">
                Welcome to {{ env('APP_SHORT_NAME') }}, {{ Auth::user()->fname }} {{ Auth::user()->lname }}! 🎉
            </h5>
            <p class="instructions-steps">
                Manage your devices and users effectively using this dashboard.
            </p>
        </div>
        <!-- Right Column: Device Dropdown -->
        <div class="col-12 col-md-4">
            <div class="card border-0">
                <div class="card-body">
                    <label for="device-select" class="form-label">Select Device:</label>
                    <select id="device-select" class="form-control select2">
                        <option value="">Choose a device</option>
                        {{-- @foreach ($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                        @endforeach --}}
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row gy-4">
        <!-- Total Users Card -->
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="card-title d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Total Users</h5>
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                    <div class="mt-4">
                        <h2 class="font-weight-bold mb-0">{{ $userCount }}</h2>
                        <small class="text-muted">Users registered</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Devices Card -->
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="card-title d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Total Devices</h5>
                        <i class="fas fa-server fa-2x text-primary"></i>
                    </div>
                    <div class="mt-4">
                        <h2 class="font-weight-bold mb-0">{{ 2 }}</h2>
                        <small class="text-muted">Devices registered</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Devices Card -->
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="card-title d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Active Devices</h5>
                        <i class="fas fa-plug fa-2x text-success"></i>
                    </div>
                    <div class="mt-4">
                        <h2 class="font-weight-bold mb-0">{{ 1 }}</h2>
                        <small class="text-muted">Currently active</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Auth Devices Card -->
        <div class="col-12 col-sm-6 col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="card-title d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Pending Auth</h5>
                        <i class="fas fa-key fa-2x text-warning"></i>
                    </div>
                    <div class="mt-4">
                        <h2 class="font-weight-bold mb-0">{{ 0 }}</h2>
                        <small class="text-muted">Devices awaiting authentication</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Data Section -->
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="mb-3">Device Data Visualization</h5>
            <div class="card shadow-sm">
                <div class="card-body">
                        <canvas id="waveChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Users and Assigned Devices Table -->
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="mb-3">Users and Assigned Devices</h5>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Assigned Devices</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Demo Data -->
                            <tr>
                                <td>Akshay Test</td>
                                <td>akshaynayee1@gmail.com</td>
                                <td>
                                    Sample Button Device (Active)<br>
                                    Device Test 2 (Inactive)
                                </td>
                                <td>
                                    <a href="#" class="btn btn-info btn-sm">View</a>
                                    <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                    <a href="#" class="btn btn-primary btn-sm">Assign Device</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Test Customer</td>
                                <td>test@example.com</td>
                                <td>
                                    No Assinged Device
                                </td>
                                <td>
                                    <a href="#" class="btn btn-info btn-sm">View</a>
                                    <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                    <a href="#" class="btn btn-primary btn-sm">Assign Device</a>
                                </td>
                            </tr>
                            <!-- Add more demo users as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Device Authentication Table -->
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="mb-3">Device Authentication</h5>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Device Name</th>
                                <th>API KEY</th>
                                <th>Status</th>
                                <th>Customer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Demo Data -->
                            <tr>
                                <td>Sample Button Device</td>
                                <td>6bfa9c</td>
                                <td>Authorized</td>
                                <td>AkshayTest</td>
                                <td>
                                    {{-- <button class="btn btn-success btn-sm">Authenticate</button> --}}
                                    <a href="#" class="btn btn-info btn-sm">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Device Test 2</td>
                                <td>2569ABC</td>
                                <td>Not Assinged</td>
                                <td>AkshayTest</td>
                                <td>
                                    <button class="btn btn-success btn-sm">Authenticate</button>
                                    <a href="#" class="btn btn-info btn-sm">View</a>
                                </td>
                            </tr>
                            <!-- Add more demo devices as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@include('dashboard.admin-dashboard-js')

@section('scripts')
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include your custom dashboard.js -->
@endsection
