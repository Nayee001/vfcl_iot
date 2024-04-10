@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Device Management
            </h4>
        </div>
        <div class="row g-4" id="devices">
            <img src="{{ asset('assets/img/illustrations/girl-doing-yoga-light.jpg') }}" alt="No Devices"
            width="500" class="no-device img-fluid"/>
            <p>No Device Assined to you !</p>
        </div>
    </div>
@endsection
@include('devices.customer-device-js')
