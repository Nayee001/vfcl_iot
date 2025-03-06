@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Welcome Section -->
        <div class="row align-items-center mb-4">
            <div class="col-12 col-md-8">
                <h4 class="fw-bold mb-3">
                    <span class="text-muted fw-light">Manager /</span> Dashboard
                </h4>
                <h5 class="card-title text-primary">
                    Welcome to {{ env('APP_SHORT_NAME') }}, {{ Auth::user()->fname }} {{ Auth::user()->lname }}! 🎉
                </h5>
                <p class="instructions-steps">Manage your devices and customers effectively.</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between">
                <button class="btn btn-primary" onclick="openAssignDeviceModal()">
                    <i class="fas fa-plus-circle"></i> Assign Device
                </button>
                <button class="btn btn-warning" onclick="viewPendingAuth()">
                    <i class="fas fa-key"></i> Pending Auth Requests
                </button>
                <button class="btn btn-info" onclick="showLiveChart()">
                    <i class="fas fa-chart-line"></i> Alert Monitoring
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row gy-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Total Devices</h5>
                        <h2 id="total-devices">{{ $getDeviceTotalCount }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Active Devices</h5>
                        <h2 id="active-devices">{{ $getTotalActiveDevice }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Pending Auth</h5>
                        <h2 id="pending-auth">{{ count($unAuthnewDevices) }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Customers</h5>
                        <h2 id="customer-count">{{ $userCount }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Table -->
        <div class="row mt-4">
            <div class="col-12">
                <h5>Assigned Devices</h5>
                <div id="top-message"></div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Device Name</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Last Sync</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="deviceTableBody">
                        <!-- Devices will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include Modals -->
    @include('dashboard.modals')
@endsection

@section('script')
    <script src="{{ asset('js/manager-dashboard.js') }}"></script>
@endsection
