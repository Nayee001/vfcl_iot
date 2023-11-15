@extends('layouts.app')
<!-- Content -->
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Device Management /</span> Device Type List
                </h4>
            </div>
            <div class="mt-3">
                <!-- Button trigger modal -->
                @can('device-type-create')
                <a onclick="getDeviceTypeForm()" class="btn btn-primary" href="javascript:void(0)">
                    Create Device Type </a>
                @endcan
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">Device Type Management</h5>
            <div class="table-responsive text-nowrap">
                <div class="container">
                    <table class="table device-type-ajax-datatables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Device Type</th>
                                <th>Descriptiion</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeviceTypeModel" data-backdrop="static">
    </div>
@endsection
@include('device-type.device-type-js')
