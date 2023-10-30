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
                                <img src="{{ asset('assets/img/illustrations/iot.png') }}" alt="iot"
                                    class="d-block rounded iot" height="100" width="100" id="uploadedAvatar">
                                <div class="button-wrapper">
                                    <div class="card-title mb-0">
                                        <h5 class="m-0 me-2">{{ $deviceData->name }}</h5>
                                        <br>
                                        <small class=""><b>Device Type :</b>
                                            {{ $deviceData->deviceType->device_type }}</small> <br>
                                        <small class=""><b>Description :</b> {{ $deviceData->description }}</small>
                                        <br>
                                        <small><b>Created Date :</b> {{ $deviceData->created_at->format('F j, Y') }}</small>

                                    </div>
                                </div>

                                <div class="ml-auto ">
                                    <div class="button-wrapper">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2">API KEY: <a data-bs-toggle="tooltip" data-bs-offset="0,4"
                                                    data-bs-placement="right" data-bs-html="true" title=""
                                                    data-bs-original-title="<i class='bx bx-copy-alt'></i> <span>Copy on Clipboard</span>"
                                                    href="javascript:void()" id="copied"> {{ $deviceData->api_key }}</a>
                                            </h5>
                                            <br>
                                            <small class=""><b>Status :</b>
                                                {{ $deviceData->status }}</small> <br>
                                            <small class=""><b>Health Status :</b>
                                                {{ $deviceData->health }}</small>
                                            <br>
                                            <small><b>Manager :</b>
                                                <a href="{{ route('users.show', $deviceData->deviceOwner->id) }}">{{ $deviceData->deviceOwner->fname }}
                                                    {{ $deviceData->deviceOwner->lname }}</a> </small> <br>
                                            @if ($deviceData->deviceAssigned)
                                                <small><b>Assingee :</b><a
                                                        href="{{ route('users.show', $deviceData->deviceAssigned->assignee->id) }}">{{ $deviceData->deviceAssigned->assignee->fname }}
                                                        {{ $deviceData->deviceAssigned->assignee->lname }}</a> </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="image-container"
                                style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                <img src="{{ asset('assets/img/illustrations/no-devices.jpg') }}" alt="No Devices"
                                    width="500" class="no-device img-fluid"
                                    data-app-dark-img="illustrations/page-misc-error-dark.png"
                                    data-app-light-img="illustrations/page-misc-error-light.png" />
                            </div>
                            <div class="text-container" style="text-align: center;">
                                {{__('messages.no_msg')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('users.user-js')
