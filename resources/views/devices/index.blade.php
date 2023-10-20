@extends('layouts.app')
<!-- Content -->
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Device Management /</span> Device List</h4>
            </div>
            <div class="mt-3">
                <!-- Button trigger modal -->
                @can('device-create')
                    <a href="{{ route('devices.create') }}" class="btn btn-primary">Create Device</a>
                @endcan
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">Devices</h5>

            <div class="table-responsive text-nowrap">
                <div class="container">
                    <div class="tab-pane fade show active" id="navs-pills-justified-profile" role="tabpanel">
                        <div class="image-container">
                            <img src="{{ asset('assets/img/illustrations/no-devices.jpg') }}" alt="No Devices"
                                width="500" class="no-device img-fluid"
                                data-app-dark-img="illustrations/page-misc-error-dark.png"
                                data-app-light-img="illustrations/page-misc-error-light.png" />
                        </div>
                        <div class="text-container">
                            No Devices Added for this user
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
