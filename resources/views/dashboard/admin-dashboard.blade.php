@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4">
            <!-- Ratings -->
            <div class="col-xl-3 col-sm-6 mt-5">
                <div class="card">
                    <div class="row">
                        <div class="col-8">
                            <div class="card-body">
                                <div class="card-info">
                                    <h5 class="mb-4 pb-1 text-nowrap"><b>Managers</b></h5>
                                    <div class="d-flex align-items-end mb-3">
                                        <h2 class="mb-0 me-2">{{ $managerCount }}</h2>
                                        <small class="text-success">Total</small>
                                    </div>
                                    <a class="widget-lable" href=""><i class='bx bx-list-ol'></i> View All </a>
                                    <a class="widget-lable" href=""> <i class='bx bx-plus'></i>Create New </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h-100 position-relative">
                                <img src="{{ asset('assets/img/illustrations/manager.png') }}" alt="Ratings"
                                    class="position-absolute card-img-position scaleX-n1-rtl bottom-0 w-auto end-0 me-3 me-xl-0 me-xxl-3 pe-1"
                                    width="95" height="170">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Ratings -->
            <!-- Sessions -->
            <div class="col-xl-3 col-sm-6 mt-5">
                <div class="card">
                    <div class="row">
                        <div class="col-8">
                            <div class="card-body">
                                <div class="card-info">
                                    <h5 class="mb-4 pb-1 text-nowrap"><b>Customers</b></h5>
                                    <div class="d-flex align-items-end mb-3">
                                        <h2 class="mb-0 me-2">{{ $userCount }}</h2>
                                        <small class="text-success">Total</small>
                                    </div>
                                    <a class="widget-lable" href=""><i class='bx bx-list-ol'></i> View All </a>
                                    <a class="widget-lable" href=""> <i class='bx bx-plus'></i>Create New </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h-100 position-relative">
                                <img src="{{ asset('assets/img/illustrations/users.png') }}" alt="Ratings"
                                    class="position-absolute card-img-position scaleX-n1-rtl bottom-0 w-auto end-0 me-3 me-xl-0 me-xxl-3 pe-1"
                                    width="95" height="170">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 align-self-end">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Device Types </h5>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-xl-2">
                            @foreach($deviceTypesWithDeviceCount as $key => $value)
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <div class="avatar-initial {{ getRandomBackgroundColor() }} rounded shadow">
                                            <i class='bx bx-devices'></i>
                                        </div>
                                    </div>
                                    <div class="ms-3">
                                        <div class="small mb-1">{{$key}}</div>
                                        <h5 class="mb-0">{{$value}}</h5> <small>Devices</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            {{-- <div class="col-lg-6 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Hey, Akshay 🎉</h5>
                                @if (Auth::user()->status == App\Models\User::USER_STATUS['NEWUSER'] || Auth::user()->status == App\Models\User::USER_STATUS['FIRSTTIMEPASSWORDCHANGED'])
                                    <p class="mb-4" id="greetings">
                                    </p>
                                    @if (Auth::user()->status != App\Models\User::USER_STATUS['FIRSTTIMEPASSWORDCHANGED'])
                                        <a href="{{ route('change-password', Auth::user()->id) }}"
                                            class="btn btn-sm btn-outline-primary">Change your Password
                                        </a>
                                    @else
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#terms-conditions"
                                            class="btn btn-sm btn-outline-primary">Agree terms
                                            and Conditions
                                        </a>
                                    @endif
                                @else
                                <h6 class="card-title text-primary" id="greetings"></h6>

                                @endif
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
            </div> --}}

        @if (Auth::user()->status == App\Models\User::USER_STATUS['ACTIVE'])
            {{-- <p>Admin Dashboard</p> --}}
            {{-- <div class="col-lg-6 col-md-4 order-1">
                    <div class="row">
                        @hasanyrole('Manager|Super Admin')
                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-info rounded-circle p-3">
                                            <i class='bx bxs-user-detail'></i>
                                        </span>
                                        <div class="dropdown">
                                            <button class="btn btn-link p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="fw-bold d-block mb-1">All Managers</span>
                                        <h3 class="card-title mb-0">4</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <span class="badge bg-info rounded-circle p-3">
                                                <i class='bx bx-user'></i>
                                            </span>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="fw-semibold d-block mb-1">Users</span>
                                        <h3 class="card-title mb-2">{{ $userCount }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endhasanyrole
                        <div class="col-lg-4 col-md-6 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <span class="badge bg-primary rounded-circle p-3">
                                            <i class='bx bx-devices'></i>
                                        </span>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <span>Devices</span>
                                    <h3 class="card-title text-nowrap mb-1">{{ $deviceCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            <!-- Total Revenue -->
            {{-- <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                    <div class="card">
                        <div class="row row-bordered g-0">
                            <div class="col-md-8">
                                <h5 class="card-header m-0 me-2 pb-3">Devices Data</h5>
                                <div id="totalRevenueChart" class="px-2"></div>

                            </div>
                            <div class="col-md-4">
                                <div class="card-body">
                                    <div class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                                id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Today
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                                <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                                <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                                <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="growthChart"></div>
                                <div class="text-center fw-semibold pt-3 mb-2">Device Data</div>

                                <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">

                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            <!--/ Total Revenue -->
            {{-- <div class="col-md-6 col-lg-4 order-2 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">My Devices</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                    <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($deviceList)
                                <ul class="p-0 m-0">
                                    @php
                                        $staticImages = ['assets/img/illustrations/iot.png', 'assets/img/illustrations/iot2.png', 'assets/img/illustrations/iot3.png'];
                                    @endphp
                                    @foreach ($deviceList as $devices)
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <img src="{{ asset($staticImages[array_rand($staticImages)]) }}"
                                                    alt="iot" class="rounded" height="100" width="100">
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $devices->name }}</h6>
                                                    <small
                                                        class="text-muted d-block mb-1">{{ $devices->deviceType->device_type }}</small>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    <a href="{{route('devices.show',$devices->id)}}">View Details</a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="tab-pane fade show active" id="navs-pills-justified-profile" role="tabpanel">
                                    <div class="image-container">
                                        <img src="{{ asset('assets/img/illustrations/no-devices.jpg') }}" alt="No Devices"
                                            width="500" class="no-device img-fluid"
                                            data-app-dark-img="illustrations/page-misc-error-dark.png"
                                            data-app-light-img="illustrations/page-misc-error-light.png" />
                                    </div>
                                    <div class="text-container">
                                        No Devices Added Right Now
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div> --}}
        @endif
    </div>

    </div>
@endsection

@include('dashboard.admin-dashboard-js')
