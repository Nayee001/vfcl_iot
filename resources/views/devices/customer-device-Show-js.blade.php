@section('script')
    <style>
        /* Custom styles (same as before) */
        .device-card h5 {
            font-weight: 700;
            /* Bold title */
            color: #17a2b8;
            /* Bright teal color */
        }

        .device-card small {
            color: #6c757d;
            /* Muted text */
        }

        .progress-bar {
            border-radius: 5px;
            /* Rounded progress bar */
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-check-input {
            width: 1.5em;
            height: 1.5em;
        }

        .btn-custom {
            font-weight: 600;
            border-radius: 5px;
            background-color: #ffc107;
            color: #212529;
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background-color: #e0a800;
        }
    </style>

    <script>
        function loadDeviceData(deviceId) {
            $.ajax({
                url: `/devices/data/${deviceId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        // Determine if the "Authorize Device" button should be displayed
                        const verifyButton = (response.device_assigned.status === 'Not Responded' || response
                                .device_assigned.status === 'Reject') ?
                            `<button class="btn btn-warning btn-sm mt-3" onclick="verifyDevice(${response.id})">
                        <i class="fas fa-check-circle"></i> Authorize Device
                     </button>` :
                            '';

                        // Define the checklist items with their statuses
                        const checklistItems = [{
                                name: 'Login Status',
                                icon: 'fas fa-sign-in-alt',
                                status: response.device_assigned.login_to_device === 1
                            },
                            {
                                name: 'MAC Address Verification',
                                icon: 'fas fa-network-wired',
                                status: response.device_assigned.send_mac === 1
                            },
                            {
                                name: 'API Key Verification',
                                icon: 'fas fa-key',
                                status: response.device_assigned.send_apikey === 1
                            },
                            {
                                name: 'Software Update',
                                icon: 'fas fa-sync-alt',
                                status: response.device_assigned.connection_status === 'Authorized'
                            },
                            {
                                name: 'Secure Connection',
                                icon: 'fas fa-shield-alt',
                                status: response.device_assigned.connection_status === 'Authorized'
                            }
                        ];

                        // Calculate progress
                        const checkedCount = checklistItems.filter(item => item.status).length;
                        const progress = Math.round((checkedCount / checklistItems.length) * 100);

                        // Generate the checklist HTML
                        const checklistHTML = checklistItems.map(item => `
                    <li class="list-group-item d-flex align-items-center">
                        <i class="${item.icon} me-3 ${item.status ? 'text-success' : 'text-secondary'}" style="font-size: 1.5em;"></i>
                        <span class="flex-grow-1">${item.name}</span>
                        <i class="fas ${item.status ? 'fa-check-circle text-success' : 'fa-times-circle text-danger'}"></i>
                    </li>
                `).join('');

                        // Build the device info HTML
                        const deviceInfoHTML = `
                    <div class="device-details">
                        <!-- Device Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="text-primary mb-0">${response.name}</h4>
                                <small class="text-muted">Status: ${response.device_assigned.connection_status}</small>
                            </div>
                            <div>
                                <span class="badge rounded-pill bg-${response.device_assigned.connection_status === 'Authorized' ? 'success' : 'danger'}">
                                    ${response.device_assigned.connection_status}
                                </span>
                            </div>
                        </div>

                        <!-- Checklist -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Device Authentication Checklist</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                ${checklistHTML}
                            </ul>
                        </div>

                        <!-- Device Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-box bg-light p-3">
                                    <h6><i class="fas fa-key me-2"></i>API Key</h6>
                                    <p class="mb-0">${response.short_apikey}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light p-3">
                                    <h6><i class="fas fa-network-wired me-2"></i>MAC Address</h6>
                                    <p class="mb-0">${response.mac_address}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <h6>Device Authentication Progress</h6>
                            <div class="progress mt-2" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                                    style="width: ${progress}%;" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">
                                    ${progress}%
                                </div>
                            </div>
                        </div>

                        <!-- Verify Button -->
                        ${verifyButton}
                    </div>
                `;

                        $('#deviceDetails').html(deviceInfoHTML);
                    } else {
                        console.error('No device data found.');
                        $('#deviceDetails').html('<p class="text-danger">No device data found.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching device data:', error);
                    $('#deviceDetails').html(
                        '<p class="text-danger">Error fetching device data. Please try again later.</p>');
                }
            });
        }


        function generateCheckboxHTML(label, iconClass, isChecked) {
            return `
                <li>
                    <i class="${iconClass}"></i>
                    <span>${label}</span>
                    <input class="form-check-input ms-2" type="checkbox" ${isChecked ? 'checked' : ''} disabled>
                </li>
            `;
        }

        function verifyDevice(deviceId) {
            fetch(`/verify-device-model/${deviceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        showVerificationModal(data, deviceId);
                    }
                })
                .catch(error => {
                    console.error('Error during verification:', error);
                    alert(`Failed to verify device ID ${deviceId}`);
                });
        }

        function showVerificationModal(data, deviceId) {
            const contentHtml = `
                <p>Device Name: <b>${data.name}</b></p>
                <p>Status: ${data.status}</p>
            `;

            const verifyAgainButton = document.createElement('button');
            verifyAgainButton.type = 'button';
            verifyAgainButton.className = 'btn btn-warning';
            verifyAgainButton.textContent = 'Authorize';
            verifyAgainButton.onclick = function() {
                sendToDevice(deviceId);
            };

            document.getElementById('modalContent').innerHTML = contentHtml;
            document.getElementById('modalContent').appendChild(verifyAgainButton);
            $('#verificationModal').modal('show');
        }

        $(document).ready(function() {
            const deviceId = {{ $deviceData->id }};
            loadDeviceData(deviceId);

            setInterval(function() {
                loadDeviceData(deviceId);
            }, 30000);
        });
        window.sendToDevice = function(deviceId) {
            $('#verificationModal').modal('hide');

            const timer = 10000;
            let timerInterval;
            Swal.fire({
                title: 'Device Activation',
                html: `Please wait for <b></b> seconds.`,
                timer: timer,
                timerProgressBar: true,
                didOpen: () => {
                    const b = Swal.getHtmlContainer().querySelector('b');
                    timerInterval = setInterval(() => {
                        b.textContent = Math.round(Swal.getTimerLeft() / 1000);
                    }, 100);
                },
                didClose: () => {
                    clearInterval(timerInterval);
                }
            });

            console.log("sendToDevice device with ID:", deviceId);
            fetch(`{{ url('send-device-mqtt') }}/${deviceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        Swal.fire({
                            title: "Device Authorized",
                            text: "Please check the device; it is now ready for use.",
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                            $('#verificationModal').modal('hide');
                        });
                    }
                })
                .catch(error => {
                    console.error('Error during verification:', error);
                    alert(`Failed to accept device ID ${deviceId}`);
                });
        };
    </script>
@endsection
