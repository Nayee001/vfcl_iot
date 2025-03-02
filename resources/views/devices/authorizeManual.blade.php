@php
    $layout = isSuperAdmin()
        ? 'layouts.app'
        : 'layouts.customer-app';
@endphp
@extends($layout)
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Preloader Section -->
        {{-- <div id="preloader">
            <div class="text-center">
                <div class="spinner-border" role="status"></div>
                <p class="mt-3">Getting your devices... <br>
                    Please wait for a moment</p>
            </div>
        </div> --}}
        <div class="row flex-xl-nowrap">
            <div class="col-12 col-xl-12 container-p-y">
                <!-- Hero Section -->
                <div class="hero bg-gradient mb-4 rounded-3 text-center">
                    <h1 class="display-4">Device Authorization 🎉</h1>
                    <p class="lead">Easily authorize your device by following these simple steps and begin using its
                        features right away.</p>
                    <a href="mailto:support@vfcl.com" class="btn btn-outline-primary mt-3">Need Help? Contact Support</a>
                </div>
                <hr class="my-4">
                <!-- Step by Step Instructions -->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10">
                        <!-- Step 1: Unbox the Device -->
                        <div class="step-container mb-5 p-4 shadow-sm bg-white rounded">
                            <div class="step-header d-flex align-items-center mb-3">
                                <div class="step-number me-3 rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">1</div>
                                <h4 class="mb-0">You've Successfully Booted the Device</h4>
                            </div>
                            <ul>
                                <li>After booting, you will see the vFCL logo and a Welcome page.</li>
                                <li>Touch anywhere on the screen to proceed.</li>
                            </ul>
                            <div class="row ">
                                <div class="text-center mt-3">
                                    <figure>
                                        <img style="width: 450px;" src="{{ asset('assets/img/device/welcome.png') }}"
                                            alt="4.3-inch Capacitive Touch Display" class="img-fluid">
                                        <figcaption>Figure 1: Welcome to the vFCL Device</figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <!-- Step 2: Instructions Page -->
                        <div class="step-container mb-5 p-4 shadow-sm bg-white rounded">
                            <div class="step-header d-flex align-items-center mb-3">
                                <div class="step-number me-3 rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">2</div>
                                <h4 class="mb-0">Instructions Page</h4>
                            </div>
                            <ul>
                                <li>After clicking on the <code>NEXT</code> button, you will see this instructions page.
                                </li>
                            </ul>
                            <div class="row ">
                                <div class="text-center mt-3">
                                    <figure>
                                        <img style="width: 450px;" src="{{ asset('assets/img/device/instruction.png') }}"
                                            alt="Instructions Page" class="img-fluid">
                                        <figcaption>Figure 2: Instructions Page</figcaption>
                                    </figure>
                                </div>
                            </div>
                            <ul>
                                <li>Click on the <code>NEXT</code> button to check your internet connection.</li>
                            </ul>
                        </div>

                        <!-- Step 3: Internet - WiFi (optional) -->
                        <div class="step-container mb-5 p-4 shadow-sm bg-white rounded">
                            <div class="step-header d-flex align-items-center mb-3">
                                <div class="step-number me-3 rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">3</div>
                                <h4 class="mb-0">Internet - WiFi <code>(optional)</code></h4>
                            </div>
                            <p>If you have already connected to the internet via Ethernet or WiFi, you can skip this
                                section.</p>
                            <ul>
                                <li><strong>Connection:</strong> Follow the device instructions to connect via WiFi if not
                                    already connected.</li>
                            </ul>
                        </div>

                        <!-- Step 4: Device Authorization Instructions -->
                        <div class="step-container mb-5 p-4 shadow-sm bg-white rounded">
                            <div class="step-header d-flex align-items-center mb-3">
                                <div class="step-number me-3 rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">4</div>
                                <h4 class="mb-0">Device Authorization</h4>
                            </div>
                            <ul>
                                <li><strong>Step 1:</strong> Goto Step 5 to select your Device and Log in to the DEVICE
                                    using your web credentials.</li>
                                <li><strong>Step 2:</strong> Enter your <code>MAC Address</code> and <code>API Key</code>
                                    into the device, and wait for the response !</li>
                                <li><strong>Step 3:</strong> Check your email and refresh this page for the device
                                    authentication message.</li>
                                <li><strong>Step 4:</strong> Authorize your device through the vFCL web platform by clicking
                                    the <strong>Auth</strong> button.</li>
                                <li><strong>Step 5:</strong> Wait for the security connections to be established.</li>
                                <li><strong>Step 6:</strong> Device authorization is now complete!</li>
                            </ul>
                        </div>

                        <!-- Step 5: Select Your Device -->
                        <div class="step-container mb-5 p-4 shadow-sm bg-white rounded">
                            <div class="step-header d-flex align-items-center mb-3">
                                <div class="step-number me-3 rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">5</div>
                                <h4 class="mb-0">Go To My devices</h4>
                            </div>
                            <a href="{{ route('home') }}" class="btn btn-danger">
                                <i class='bx bx-devices'></i> Devices
                            </a>
                            {{-- <ul>
                                <li><strong>Select the Device:</strong> Choose your device from the list of Assinged devices and procced with device Authentication.</li>
                            </ul>
                            <div class="" id="top-message"></div>
                            <div class="row g-4" id="myDevices">
                                <!-- Dynamic device cards will be inserted here by JS -->
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('devices.customer-device-js')
