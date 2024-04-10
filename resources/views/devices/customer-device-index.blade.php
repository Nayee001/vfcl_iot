@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Device Management
            </h4>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4 mb-3" id="#devices">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <h6 class="card-subtitle text-muted">Support card subtitle</h6>
                    </div>
                    <img class="img-fluid" src="../assets/img/elements/13.jpg" alt="Card image cap" />
                    <div class="card-body">
                        <p class="card-text">Bear claw sesame snaps gummies chocolate.</p>
                        <a href="javascript:void(0);" class="card-link">Card link</a>
                        <a href="javascript:void(0);" class="card-link">Another link</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('devices.customer-device-js')
