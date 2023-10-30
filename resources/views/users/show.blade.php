@extends('layouts.app')
@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="{{ asset('assets/img/illustrations/user-avatar.jpg') }}" alt="user-avatar"
                                    class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                                <div class="button-wrapper">
                                    <div class="card-title mb-0">
                                        <h5 class="m-0 me-2">{{ $user->fname }} {{ $user->lname }}</h5>
                                        <small class="text-muted">{{ $user->title }}</small> -
                                        <small class="text-muted">{{ $user->roles[0]->name }}</small>
                                    </div>
                                    <small class="text-muted">Joined on: {{ $user->created_at->format('F j, Y') }}</small>
                                </div>
                                <div class="ml-auto small-widget">
                                    <!-- Use ml-auto to move the right section to the right side -->
                                    <div class="d-flex justify-content-end">
                                        <!-- Devices -->
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary rounded-circle p-3">
                                                <i class='bx bx-devices'></i>
                                            </span>
                                            <div class="ms-2">
                                                <small class="text-muted">Devices</small>
                                                <h5 class="m-0">{{ $deviceCount }}</h5>
                                            </div>
                                        </div>

                                        <!-- Users -->
                                        @hasanyrole('Manager|Super Admin')
                                            <div class="d-flex align-items-center ml-1 user-widget">
                                                <span class="badge bg-info rounded-circle p-3">
                                                    <i class='bx bx-user'></i>
                                                </span>
                                                <div class="ms-2">
                                                    <small class="text-muted">Users</small>
                                                    <h5 class="m-0">{{ $userCount }}</h5>
                                                </div>
                                            </div>
                                        @else
                                        @endhasanyrole

                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-0" />
                        <div class="card-body">
                            <div class="col-xl-12">
                                <div class="nav-align-top mb-4">
                                    @hasanyrole('Manager|Super Admin')
                                        <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                                            <li class="nav-item">
                                                <button type="button" class="nav-link active" role="tab"
                                                    data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home"
                                                    aria-controls="navs-pills-justified-home" aria-selected="true">
                                                    <i class="tf-icons bx bx-user"></i> Users
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                                    data-bs-target="#navs-pills-justified-profile"
                                                    aria-controls="navs-pills-justified-profile" aria-selected="false">
                                                    <i class="tf-icons bx bx-devices"></i> Devices
                                                </button>
                                            </li>
                                        </ul>
                                    @endhasanyrole
                                    <div class="tab-content">
                                        <div class="tab-pane fade @hasanyrole('Manager|Super Admin')show active @endhasanyrole"
                                            id="navs-pills-justified-home" role="tabpanel">
                                            <div class="table-responsive text-nowrap">
                                                <table class="table users-show-hierarchy-ajax-datatables">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Title</th>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th>Email</th>
                                                            <th>Role</th>
                                                            <th>Creater</th>
                                                            <th>Devices</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-border-bottom-0">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @hasanyrole('Customer')show active @endhasanyrole"
                                            id="navs-pills-justified-profile" role="tabpanel">
                                            <div class="image-container">
                                                <img src="{{ asset('assets/img/illustrations/no-devices.jpg') }}"
                                                    alt="No Devices" width="500" class="no-device img-fluid"
                                                    data-app-dark-img="illustrations/page-misc-error-dark.png"
                                                    data-app-light-img="illustrations/page-misc-error-light.png" />
                                            </div>
                                            <div class="text-container">
                                                {{ __('messages.no_msg') }}
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
@endsection

@include('users.user-show-js')
