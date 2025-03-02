@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">

            <div class="col-lg-6 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Welcome, {{ Auth::user()->fname }}
                                    {{ Auth::user()->lname }} 🎉</h5>
                                <p class="mb-4">
                                    Devices Health Status: <span class="fw-bold">72</span> data points received today. View
                                    all data on the dashboard.
                                </p>
                                <button title="Unread notifications" type="button"
                                    class="btn rounded-pill btn-icon btn-outline-primary position-relative notification-button">
                                    <span class="tf-icons bx bx-bell"></span>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge"
                                        title="Unread notifications">45</span>
                                </button>

                                <button title="Device Alerts" type="button"
                                    class="btn rounded-pill btn-icon btn-outline-danger position-relative notification-button">
                                    <i class='bx bxs-alarm-exclamation'></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success notification-badge"
                                        title="Device Alerts">5</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140"
                                    alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 order-1">
                <div class="row">
                    <div class="col-lg- col-md-12 col-6 mb-4">
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
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="../assets/img/icons/unicons/customers.png" alt="Credit Card"
                                            class="rounded" />
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
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
                                <span>Customers</span>
                                <h2 class="mb-0 me-2">{{ $userCount }}</h2>
                                <small class="text-success">Total</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="../assets/img/icons/unicons/map.png" alt="Credit Card" class="rounded" />
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
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
                                <span>Locations</span>
                                <h2 class="mb-0 me-2">{{ $locationCount }}</h2>
                                <small class="text-success">Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Total Revenue -->
            <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-8">
                            <h5 class="card-header m-0 me-2 pb-3">Device SignWave</h5>
                            <div id="device-fault-line-chart" class="px-2"></div>
                        </div>
                        <div class="col-md-4">
                            <div id="device-fault-status-shown" class="no-device-msg">
                                <img src="../assets/img/icons/unicons/error.png" alt="Credit Card" class="rounded no-device-img" />
                                <b class="mt-3">No Device Selected</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Revenue -->
            <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Device Type Statistics</h5>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h6 class="display-3 mb-0 item-">{{ $getDeviceTotalCount }}</h6>
                                <small>Total Devices</small>
                            </div>
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h6 class="display-3 mb-0 item-" id="dataCount">Loading ...</h6>
                                <small>Total Devices</small>
                            </div>
                        </div>
                        <div class="scroller">
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
            </div>
        </div>
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card">
                    <h5 class="card-header">Devices</h5>
                    {{-- <small class="card-header">Note: Device will apears here only when data/messages arises !</small> --}}
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
@endsection

@include('dashboard.admin-dashboard-js')
