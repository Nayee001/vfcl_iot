@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Greeting & Stats Section -->
        <div class="row g-4 mb-4">
            <!-- Greeting Card -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100 bg-primary text-white overflow-hidden">
                    <div class="card-body position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="text-white mb-1" id="greeting-message">
                                    Good Day, {{ Auth::user()->fname }}! 🌞
                                </h2>
                                <p class="mb-4 opacity-75">Manage Your Devices & Customers</p>
                                <div class="text-white" id="current-time">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock me-2 fs-5"></i>
                                        <div>
                                            <span class="time-display fs-4 fw-medium">Loading...</span>
                                            <span class="date-display ms-2 d-block small">MM/DD/YYYY</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img src="../assets/img/illustrations/trophy.png" width="80" class="align-self-end"
                                alt="achievement">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="col-12 col-lg-8">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="mb-1">vFCL Platform Statistics</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-4 py-3">
                            <!-- Total Devices -->
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-lg bg-primary bg-opacity-10 p-3 rounded-3">
                                        <i class='bx bxs-devices fs-4 text-primary'></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Total Devices</p>
                                        <h4 class="mb-0">{{ $getDeviceTotalCount }}</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Devices -->
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-lg bg-success bg-opacity-10 p-3 rounded-3">
                                        <i class='bx bx-check-circle fs-4 text-success'></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Active</p>
                                        <h4 class="mb-0">{{ $getTotalActiveDevice }}</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Devices -->
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-lg bg-warning bg-opacity-10 p-3 rounded-3">
                                        <i class='bx bx-time-five fs-4 text-warning'></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Pending</p>
                                        <h4 class="mb-0">{{ count($unAuthnewDevices) }}</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Customers -->
                            <div class="col-6 col-md-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-lg bg-info bg-opacity-10 p-3 rounded-3">
                                        <i class='bx bxs-user-circle' ></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Customers</p>
                                        <h4 class="mb-0">{{ $userCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="row g-4">
            <!-- Faults Chart -->
            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="mb-0">Fault Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <div>
                                <p class="text-muted mb-1">Total Faults</p>
                                <h2 class="mb-0"><span id="totalFaults">0</span></h2>
                            </div>

                        </div>
                        <div class="chart-container position-relative">
                            <canvas id="faultChart" style="height: 250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="mb-0">User Hierarchy</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <div>                            <table class="table table-hover users-show-hierarchy-ajax-datatables">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Devices</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devices Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <h5 class="mb-0">All Devices</h5>
                    </div>
                    <div class="card-body">
                        <div id="top-message" class="mb-3"></div>
                        <div class="row g-4" id="myDevices"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.modals')
@endsection

@include('dashboard.manager-dashboard-js')
