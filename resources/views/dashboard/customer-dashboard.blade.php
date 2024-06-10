@extends('layouts.app')
@section('content')
    @if ($unAuthnewDevices->isNotEmpty())
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row gy-4">
                <div class="col-lg-8 mb-4 order-0">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                            <div class="col-sm-7">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Welcome, {{ Auth::user()->fname }}
                                        {{ Auth::user()->lname }} 🎉</h5>
                                    <p class="mb-4">
                                        Devices Health Status: <span class="fw-bold">72</span> data points received today.
                                        View
                                        all data on the dashboard.
                                    </p>
                                    <a href="{{ route('devices.index') }}" title="Unread notifications" role="button"
                                        class="btn rounded-pill btn-icon btn-outline-primary position-relative notification-button">
                                        <i class="fa-solid fa-microchip"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge"
                                            title="Unread notifications">{{ $notifications }}</span>
                                    </a>
                                    <a href="#" title="Device Alerts" type="button"
                                        class="btn rounded-pill btn-icon btn-outline-danger position-relative notification-button">
                                        <i class='bx bxs-alarm-exclamation'></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success notification-badge"
                                            title="Device Alerts">0</span>
                                    </a>
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
                </div>
                <div class="col-lg-4 col-md-4 order-1">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/map.png" alt="Credit Card"
                                                class="rounded" />
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">

                                                <a class="dropdown-item" href="{{ route('users.index') }}"><i
                                                        class='bx bx-list-ol'></i>
                                                    View All </a>
                                                <a class="dropdown-item" href="{{ route('users.create') }}"> <i
                                                        class='bx bx-plus'></i>Create New </a>
                                            </div>
                                        </div>
                                    </div>
                                    <span>Locations</span>
                                    <h2 class="mb-0 me-2">{{ $locationCount }}</h2>
                                    <small class="text-success">Total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/devices.png" alt="Credit Card"
                                                class="rounded" />
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">

                                                <a class="dropdown-item" href="{{ route('users.index') }}"><i
                                                        class='bx bx-list-ol'></i>
                                                    View All </a>
                                                <a class="dropdown-item" href="{{ route('users.create') }}"> <i
                                                        class='bx bx-plus'></i>Create New </a>
                                            </div>
                                        </div>
                                    </div>
                                    <span>Devices</span>
                                    <h2 class="mb-0 me-2">{{ $locationCount }}</h2>
                                    <small class="text-success">Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <!-- Total Revenue -->
                <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
                    <div class="card">
                        <div class="row row-bordered g-0">
                            <div class="col-md-8">
                                <h5 class="card-header m-0 me-2 pb-3">Device SignWave</h5>
                                <div id="device-fault-line-chart" class="px-2"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="device-fault-status-shown" class="no-device-msg">
                                    <img src="../assets/img/icons/unicons/error.png" alt="Credit Card"
                                        class="rounded no-device-img" />
                                    <b class="mt-3">No Device Selected</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="card">
                            <h5 class="card-header">Devices Data</h5>
                            <small class="message-device-dashboard">Note: Device will apears here only when data/messages
                                arises !</small>
                            <div class="table-responsive text-nowrap">
                                <table class="table dashboard-devices-ajax-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Device Name</th>
                                            <th>Device Status</th>
                                            <th>Health Status</th>
                                            <th>Fault Status</th>
                                            <th>Timestamps</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card text-center">
                                <div class="card-header">
                                    <a href="{{ route('home') }}" class="app-brand-link d-flex justify-content-center">
                                        <span class="app-brand-logo demo">
                                            <svg width="25" viewBox="0 0 25 42" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <defs>
                                                    <path
                                                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                                        id="path-1"></path>
                                                    <path
                                                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                                        id="path-3"></path>
                                                    <path
                                                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                                        id="path-4"></path>
                                                    <path
                                                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                                        id="path-5"></path>
                                                </defs>
                                                <g id="g-app-brand" stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                                                            <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                                <mask id="mask-2" fill="white">
                                                                    <use xlink:href="#path-1"></use>
                                                                </mask>
                                                                <use fill="#696cff" xlink:href="#path-1"></use>
                                                                <g id="Path-3" mask="url(#mask-2)">
                                                                    <use fill="#696cff" xlink:href="#path-3"></use>
                                                                    <use fill-opacity="0.2" fill="#FFFFFF"
                                                                        xlink:href="#path-3"></use>
                                                                </g>
                                                                <g id="Path-4" mask="url(#mask-2)">
                                                                    <use fill="#696cff" xlink:href="#path-4"></use>
                                                                    <use fill-opacity="0.2" fill="#FFFFFF"
                                                                        xlink:href="#path-4"></use>
                                                                </g>
                                                            </g>
                                                            <g id="Triangle"
                                                                transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                                                <use fill="#696cff" xlink:href="#path-5"></use>
                                                                <use fill-opacity="0.2" fill="#FFFFFF"
                                                                    xlink:href="#path-5"></use>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                    <h5 class="mt-3">Welcome to vFCL</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">To get started, please activate your new device by following the
                                        steps below.</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalToggle">Activate Device</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- First Modal HTML -->
            <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <h5 class="mt-3">Device Activation Step:</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ol class="text-start">
                                <li>Plug in the device.</li>
                                <li>Login with your Web Command Center Username and Password.</li>
                                <li>Copy the MAC address from the device.</li>
                                <li>Enter the API KEY on your device.</li>
                                <li>Wait for a moment and refresh the command center. Check your email for an authentication
                                    request from the device.</li>
                                <li>Accept the Auth Request from the command center for the device.</li>
                                <li>Device Authenticated.</li>
                            </ol>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="deviceStep2" data-bs-toggle="modal" data-bs-dismiss="modal"
                                class="btn rounded-pill btn-icon btn-primary">
                                <i class='bx bx-chevron-right'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Modal HTML -->
            <div class="modal fade" id="modalToggle2" aria-labelledby="modalToggle2Label" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalToggle2Label"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ol class="text-start mb-4">
                                <li>Ensure the MAC address matches your device. If not, contact support at <a
                                        href="mailto:support@vFCL.com">support@vFCL.com</a>.</li>
                                <li>Copy the API Key into your device and wait for a response.</li>
                            </ol>
                            <div id="secondModalContent">
                                <!-- Device content will be loaded here -->
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" id="prevModal" data-bs-toggle="modal" data-bs-dismiss="modal"
                                class="btn rounded-pill btn-icon btn-primary">
                                <i class='bx bx-chevron-left'></i>
                            </button>
                            <button type="button" id="deviceStep3" data-bs-toggle="modal" data-bs-dismiss="modal"
                                class="btn rounded-pill btn-icon btn-primary">
                                <i class='bx bx-chevron-right'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Third Modal HTML -->
            <div class="modal fade" id="modalToggle3" aria-labelledby="modalToggle3Label" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="modalToggle3Label">Device Activation Step 3</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-center">Please enter your API KEY into the device and wait for confirmation.
                                This may take a few moments.</p>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" id="prevModal2" data-bs-toggle="modal" data-bs-dismiss="modal"
                                class="btn rounded-pill btn-icon btn-primary">
                                <i class='bx bx-chevron-left'></i>
                            </button>
                            <button type="button" id="deviceStep4" data-bs-toggle="modal" data-bs-dismiss="modal"
                                class="btn rounded-pill btn-icon btn-primary">
                                <i class='bx bx-chevron-right'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fourth Modal HTML -->
            <div class="modal fade" id="modalToggle4" aria-labelledby="modalToggle4Label" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalToggle4Label">Device Activation Complete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <p style="font-size: 41px;">🥳</p>
                            </div>
                            <p class="text-center">Congratulations! Your device has been successfully authenticated and is
                                now ready for use. Enjoy your new device experience.</p>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" id="prevModal3" data-bs-toggle="modal" data-bs-dismiss="modal"
                                class="btn rounded-pill btn-icon btn-primary">
                                <i class='bx bx-chevron-left'></i>
                            </button>
                            <button type="button" class="btn rounded-pill btn-icon btn-secondary"
                                data-bs-dismiss="modal">
                                <i class='bx bx-check'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    @endif
    <div class="modal fade" id="terms-conditions" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalScrollableTitle">IOT-Web Terms and Conditions and Privacy
                        Policies</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                    <p>
                        Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis
                        in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                    </p>
                    <p>
                        Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis
                        lacus vel augue laoreet rutrum faucibus dolor auctor.
                    </p>
                    <p>
                        Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel
                        scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus
                        auctor fringilla.
                    </p>
                </div>
                <div class="modal-footer">
                    <form method="post" id="terms-and-conditions-form">
                        @csrf
                        <div class="form-check">
                            <input class="form-check-input mt-2" type="checkbox" name="terms_and_conditions"
                                id="terms_and_conditions">
                            <label class="form-check-label" for="terms_and_conditions"> I Agree to the terms and
                                conditions
                            </label>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="password-change-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-users-change-password" method="post">
                        @csrf
                        <div class="row">
                            <div class="mb-3">
                                <label for="fname" class="form-label">Old Password {!! dynamicRedAsterisk() !!}</label>
                                <div class="input-group input-group-merge">
                                    {!! Form::password('oldpassword', [
                                        'placeholder' => 'Old Password',
                                        'id' => 'oldpassword',
                                        'class' => 'form-control',
                                    ]) !!}
                                    <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                            class="bx bx-hide"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="password">Password {!! dynamicRedAsterisk() !!}</label>
                                    <div class="input-group input-group-merge">
                                        {!! Form::password('password', ['placeholder' => 'Password', 'id' => 'password', 'class' => 'form-control']) !!}
                                        <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                                class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <div class="form-password-toggle">
                                    <label class="form-label" for="confirm-password">Confirm Password
                                        {!! dynamicRedAsterisk() !!}</label>
                                    <div class="input-group input-group-merge">
                                        {!! Form::password('confirm-password', [
                                            'placeholder' => 'Confirm Password',
                                            'id' => 'confirm_password',
                                            'class' => 'form-control',
                                        ]) !!}
                                        <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                                class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" id="submit" class="submit btn btn-primary me-2">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('dashboard.customer-dashboard-js')
