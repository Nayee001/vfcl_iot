@layouts()@extends('layouts.app')
@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Account Settings / </span> Api Connections
            </h4>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('account-settings')}}"><i class="bx bx-user me-1"></i>
                                Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-link-alt me-1"></i>
                                Api Connections</a>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <h5 class="card-header">Api Connections</h5>
                                <div class="card-body">
                                    <p>Manage your Api Connections From here !!</p>
                                    <!-- Social Accounts -->
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <i class="bx bx-link-alt me-1 custom-icon"></i>
                                        </div>
                                        <div class="flex-grow-1 row">
                                            <div class="col-8 col-sm-7 mb-sm-0 mb-2">
                                                <h6 class="mb-0">MQTT API</h6>
                                                <small class="text-muted">Not Connected</small>
                                            </div>
                                            <div class="col-4 col-sm-5 text-end">
                                                <button type="button" class="btn btn-icon btn-outline-primary">
                                                    <i class="bx bx-edit-alt me-1"></i>
                                                </button>
                                                <button type="button" class="btn btn-icon btn-outline-danger">
                                                    <i class="bx bx-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content wrapper -->
@endsection
