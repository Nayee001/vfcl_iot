@extends('layouts.app')
<!-- Content -->
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Device Management /</span> Dashboard</h4>
            </div>
            <div class="mt-3">
            </div>
        </div>
        <div class="card">

        </div>
    </div>
    <div class="modal fade" id="assignDevice" data-backdrop="static">
    </div>
@endsection
{{-- @include('devices.device-js') --}}
