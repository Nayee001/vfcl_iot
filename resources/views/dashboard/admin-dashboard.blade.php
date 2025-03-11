@extends('layouts.app')

@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-lg-8 mb-4 order-0">
                    <div class="card">
                        <div class="d-flex align-items-center justify-content-between p-4">
                            <!-- ✅ Left Side: Welcome Message & Device Status -->
                            <div class="col-md-9">
                                <div class="card border-0" style="background: rgba(255,255,255,0.9);">
                                    <div class="d-flex align-items-center justify-content-between p-4 position-relative">
                                        <!-- Left Side: Greeting Section -->
                                        <div class="col-md-5">
                                            <div class="card-body position-relative z-2">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="bg-soft-primary rounded-circle p-2 me-3">
                                                        <i class="fas fa-user-check text-primary fa-lg"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="card-title text-primary mb-1" id="greeting-message">
                                                            Good Day, {{ Auth::user()->fname }}! 🌞
                                                        </h4>
                                                        <div class="text-secondary" id="current-time">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-clock me-2 fs-5"></i>
                                                                <div>
                                                                    <span
                                                                        class="time-display fs-4 fw-medium">Loading...</span>
                                                                    <span
                                                                        class="date-display ms-2 text-muted d-block small">MM/DD/YYYY</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Side: Status Widgets -->
                                        <div class="col-md-7">
                                            <div class="d-flex justify-content-end gap-3">
                                                <!-- Active Devices Card -->
                                                <div class="status-card bg-soft-primary hover-lift">
                                                    <div class="p-3 text-center position-relative">
                                                        <div class="icon-badge bg-soft-primary">
                                                            {{-- <i class="fas fa-microchip text-primary"></i> --}}
                                                            <i class='bx bxs-microchip text-primary' ></i>
                                                        </div>
                                                        <h6 class="mb-1 text-primary mt-2">Active Devices</h6>
                                                        <span class="fw-bold fs-4 text-dark"
                                                            id="active-devices">{{ $getTotalActiveDevice }}</span>
                                                        <div class="progress mt-2 bg-light" style="height: 2px;">
                                                            <div class="progress-bar bg-primary" style="width: 75%"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Faulty Devices Card -->
                                                <div class="status-card bg-soft-warning hover-lift">
                                                    <div class="p-3 text-center position-relative">
                                                        <div class="icon-badge bg-soft-warning">
                                                            {{-- <i class="fas fa-exclamation-triangle text-warning"></i> --}}
                                                            <i class='bx bx-question-mark text-warning'></i>
                                                        </div>
                                                        <h6 class="mb-1 text-warning mt-2">Pending Devices</h6>
                                                        <span class="fw-bold fs-4 text-dark"
                                                            id="faulty-devices">{{ $unAuth }}</span>
                                                        <div class="progress mt-2 bg-light" style="height: 2px;">
                                                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Critical Alerts Card -->
                                                <div class="status-card bg-soft-danger hover-lift">
                                                    <div class="p-3 text-center position-relative">
                                                        <div class="icon-badge bg-soft-danger">
                                                            {{-- <i class="fas fa-bell text-danger"></i> --}}
                                                            <i class='bx bxs-bell-ring text-danger'></i>
                                                        </div>
                                                        <h6 class="mb-1 text-danger mt-2">Alerts Devices</h6>
                                                        <span class="fw-bold fs-4 text-dark" id="critical-alerts">{{$deviceDataCount}}</span>
                                                        <div class="progress mt-2 bg-light" style="height: 2px;">
                                                            <div class="progress-bar bg-danger" style="width: 90%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <style>
                                .hover-lift {
                                    transition: all 0.25s ease;
                                    border-radius: 8px;
                                    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
                                }

                                .hover-lift:hover {
                                    transform: translateY(-3px);
                                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                                }

                                .icon-badge {
                                    width: 36px;
                                    height: 36px;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    position: absolute;
                                    top: -18px;
                                    left: 50%;
                                    transform: translateX(-50%);
                                }

                                .bg-soft-primary {
                                    background-color: rgba(13, 110, 253, 0.08);
                                }

                                .bg-soft-warning {
                                    background-color: rgba(255, 193, 7, 0.08);
                                }

                                .bg-soft-danger {
                                    background-color: rgba(220, 53, 69, 0.08);
                                }

                                .btn-soft-primary {
                                    background-color: rgba(13, 110, 253, 0.1);
                                    border-color: transparent;
                                    color: #0d6efd;
                                }

                                .fs-4 {
                                    font-size: 1.5rem;
                                }

                                .fs-5 {
                                    font-size: 1.25rem;
                                }
                            </style>

                            <!-- ✅ Right Side: Illustration (Kept Your Original Image) -->
                            <div class="col-md-3 text-center">
                                <div class="card-body pb-0 px-0 px-md-4">
                                    <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140"
                                        alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                        data-app-light-img="illustrations/man-with-laptop-light.png" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4 col-md-4 order-1">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/manager.png" alt="chart success"
                                                class="rounded" />
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
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
                                    <span>Managers</span>
                                    <h2 class="mb-0 me-2">{{ $managerCount }}</h2>
                                    <small class="text-success">Total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/customers.png" alt="Credit Card"
                                                class="rounded" />
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
                                    <span>Customers</span>
                                    <h2 class="mb-0 me-2">{{ $userCount }}</h2>
                                    <small class="text-success">Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Revenue -->
                <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                    <div class="card">
                        <div class="row row-bordered g-0">
                            <div class="col-md-12">
                                <h5 class="card-header m-0 me-2 pb-3">Device Hourly Data</h5>
                                <div id="currentChart" class="px-2"></div>
                                <!-- Updated ID for Current Waveform Chart -->
                            </div>

                        </div>
                    </div>

                </div>
                <!--/ Total Revenue -->
                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                    <div class="row">
                        <div class="col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/map.png" alt="Credit Card"
                                                class="rounded" />
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
                        <div class="col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/devices.png" alt="Credit Card" />
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="cardOpt6"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">

                                                <a class="dropdown-item" href="{{ route('devices.index') }}"><i
                                                        class='bx bx-list-ol'></i>
                                                    View All </a>
                                                <a class="dropdown-item" href="{{ route('devices.create') }}"> <i
                                                        class='bx bx-plus'></i>Create New </a>
                                            </div>
                                        </div>
                                    </div>
                                    <span>Total Devices</span>
                                    <h2 class="mb-0 me-2">{{ $locationCount }}</h2>
                                    <small class="text-success">Total</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-4">
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
                </div>
            </div>
            <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-4 col-lg-4 col-xl-4 order-0 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                            <div class="card-title mb-1">
                                <h5 class="m-0 me-2">Fault Statistics</h5>
                                <small class="text-muted">Device Type Faults</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <h2 class="mb-2">0</h2>
                                    <span>Total Faults</span>
                                </div>
                                <div id="deviceStatChart"></div>
                            </div>
                            <ul class="p-0 m-0">
                                @foreach ($deviceTypesWithDeviceCount as $key => $value)
                                    <li class="d-flex mb-4 pb-1">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <div class="avatar-initial {{ getRandomBackgroundColor() }} rounded shadow">
                                                <i class='bx bx-devices'></i>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-auto">
                                                <h6 class="mb-0">{{ $key }}</h6>
                                                <small class="text-muted">{{ $value }}</small>
                                            </div>
                                            <div class="user-progress">
                                                <small class="fw-semibold">{{ $value }} Devices</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/ fault Statistics -->



                <!-- Transactions -->
                <div class="col-md-6 col-lg-8  order-2 mb-4">
                    <div class="card h-auto">
                        <h5 class="card-header m-0 me-2 pb-3">Alert Devices</h5>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table dashboard-devices-ajax-datatable w-100">
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

                <!--/ Transactions -->
            </div>
        </div>

    </div>
    <!-- Content wrapper -->
@endsection

@include('dashboard.admin-dashboard-js')
