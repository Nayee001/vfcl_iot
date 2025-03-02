@php
    $layout = isSuperAdmin()
        ? 'layouts.app'
        : 'layouts.customer-app';
@endphp
@extends($layout)
@section('content')
    <style>
        .step-container {
            border-left: 5px solid #007bff;
            padding-left: 20px;
        }

        .step-header .step-number {
            font-size: 1.5rem;
        }

        .img-fluid {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .img-fluid:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-wrapper {
            padding: 10px;
        }

        figure {
            text-align: center;
            margin: 10px 0;
        }

        figcaption {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .next-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row flex-xl-nowrap">
            <div class="col-12 col-xl-12 container-p-y">
                <!-- Hero Section -->
                <div class="hero bg-gradient mb-4 rounded-3 text-center">
                    <h1 class="display-4">Quick Start with your  Device 🚀</h1>
                    <p class="lead">Follow these simple steps to set up your  and get started!</p>
                    <a href="mailto:support@vfcl.com" class="btn btn-outline-primary mt-3">Contact Support</a>
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
                                <h4 class="mb-0">Unbox the </h4>
                            </div>
                            <p>After unboxing the , inspect for any damages. If any damage is found, immediately contact <a href="mailto:support@vfcl.com">support@vfcl.com</a>.</p>
                            <!-- Images for Step 1 with Figure Labels -->
                            <div class="row">
                                <div class="col-md-6 image-wrapper">
                                    <figure>
                                        <img src="{{ asset('assets/img/device/device-front.png') }}" alt=" Front View"
                                             class="img-fluid">
                                        <figcaption>Figure 1: Front view of the Device</figcaption>
                                    </figure>
                                </div>
                                <div class="col-md-6 image-wrapper">
                                    <figure>
                                        <img src="{{ asset('assets/img/device/device-back.png') }}" alt=" Back View"
                                             class="img-fluid">
                                        <figcaption>Figure 2: Back view of the Device</figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2:  Ports Overview -->
                        <div class="step-container mb-5 p-4 shadow-sm bg-white rounded">
                            <div class="step-header d-flex align-items-center mb-3">
                                <div class="step-number me-3 rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">2</div>
                                <h4 class="mb-0"> Ports Overview</h4>
                            </div>
                            <p>The Device has various ports, each designed for different purposes. Here's a breakdown:</p>
                            <ul>
                                <li><strong>USB:</strong> The Device 4 has two USB 3.0 ports and two USB 2.0 ports. Older models like the  1 Model B+ and  2 have four USB 2.0 ports, while the  Zero has a micro USB OTG port.</li>
                                <li><strong>HDMI:</strong> The Device 4 has two micro-HDMI ports, supporting dual 4K displays. The  Zero 2 W features a mini-HDMI port for single-display output.</li>
                                <li><strong>Ethernet:</strong> All models include an RJ45 Ethernet jack for wired network connections using CAT5/6 cables.</li>
                            </ul>
                        </div>

                        <!-- Step 3: Connecting the  to Power and Ports -->
                        <div class="step-container mb-5 p-4 shadow-sm bg-white rounded">
                            <div class="step-header d-flex align-items-center mb-3">
                                <div class="step-number me-3 rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">3</div>
                                <h4 class="mb-0">Connect to Power and Ports</h4>
                            </div>
                            <ul>
                                <li><strong>Contents in the box:</strong> , power adapter, and plug cable.</li>
                                <li>Plug the power adapter into the <code>POWER PORT</code> on the .</li>
                                <li>Connect your keyboard and mouse to the <code>USB</code> ports.</li>
                            </ul>
                            <!-- Images for Step 3 with Figure Labels -->
                            <div class="row">
                                <div class="col-md-6 image-wrapper">
                                    <figure>
                                        <img src="{{ asset('assets/img/device/device-side.png') }}" alt=" Ports"
                                             class="img-fluid">
                                        <figcaption>Figure 3: Side view showing  ports</figcaption>
                                    </figure>
                                </div>
                                <div class="col-md-6 image-wrapper">
                                    <figure>
                                        <img src="{{ asset('assets/img/device/device-side2.png') }}" alt=" Side View"
                                             class="img-fluid">
                                        <figcaption>Figure 4: Side view showing second set of ports</figcaption>
                                    </figure>
                                </div>
                            </div>
                            <ul class="mt-3">
                                <li><strong>Ethernet Connection:</strong> For a wired connection, plug the Ethernet cable into the <code>ETHERNET</code> port.</li>
                                <li><strong>Power On:</strong> Press the power button and wait at least 1 minute for the device to boot up completely.</li>
                                <li><strong><code>(Optional)NOTE:</code></strong> Dont worry about ethernet cable if dont have a ethernet connection, follow other steps to boot the device and press <code>NEXT</code> button to device authentication and <code>Wireless Connection</code>.</li>

                            </ul>
                        </div>

                        <!-- Step 4: Connecting the 4.3-inch Capacitive Touch Display -->
                        <div class="step-container mb-5 p-4 shadow-sm bg-white rounded">
                            <div class="step-header d-flex align-items-center mb-3">
                                <div class="step-number me-3 rounded-circle bg-dark text-white d-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px;">4</div>
                                <h4 class="mb-0">After Boot Device</h4>
                            </div>
                            <ul>
                                <li><strong>Press The <code>NEXT</code> button to see Device Authentication </strong> </li>
                            </ul>
                            <!-- Image for Step 4 with Figure Label -->
                            <div class="text-center mt-3">
                                <figure>
                                    <img style="width: 450px;" src="{{ asset('assets/img/device/welcome.png') }}" alt="4.3-inch Capacitive Touch Display"
                                         class="img-fluid">
                                    <figcaption>Figure 5:  Welcome to the vFCL Device</figcaption>
                                </figure>
                            </div>
                            <ul>
                                <li><strong><code>Device Booted Successfully</code></strong></li>
                            </ul>
                        </div>

                        <!-- Additional Tips Section -->
                        <div class="alert alert-danger mt-4">
                            <strong>Tip:</strong> Ensure that the  is placed on a stable and well-ventilated surface to prevent overheating.
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Next Button -->
        <a href="{{route('authorizeManual')}}" class="btn btn-primary next-button">
            Next
        </a>
    </div>
@endsection
