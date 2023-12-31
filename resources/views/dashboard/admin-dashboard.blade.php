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
                                    width="95" height="160">
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
                            @foreach ($deviceTypesWithDeviceCount as $key => $value)
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            <div class="avatar-initial {{ getRandomBackgroundColor() }} rounded shadow">
                                                <i class='bx bx-devices'></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <div class="small mb-1">{{ $key }}</div>
                                            <h5 class="mb-0">{{ $value }}</h5> <small>Devices</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Active Device</h5>
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
                    </div>
                    <div class="card-body pt-lg-4 mt-lg-1 device-fault-status-shown" id="device-fault-status-shown">
                        Please Select a Device
                    </div>

                </div>
            </div>
            <div class="col-xl-6 col-md-6 ">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Devices Statistic</h5>
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
                        <div class="d-flex justify-content-between align-items-end mb-3" style="position: relative;">
                            <div class="mb-2">
                                <h5 class="display-3 mb-0 item-">{{ $deviceCount }}</h5>
                                <small>Total Devices</small>
                            </div>
                            <div class="mb-2">
                                <h5 class="display-3 mb-0" id="dataCount">Loading...</h5>
                                <small>Total Data Recieved</small>
                            </div>
                            <div class="resize-triggers">
                                <div class="expand-trigger">
                                    <div style="width: 409px; height: 100px;"></div>
                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                        <div id="deviceDataContainer">
                            <div class="d-flex align-items-center border-top py-3">
                                Loding ...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@include('dashboard.admin-dashboard-js')
