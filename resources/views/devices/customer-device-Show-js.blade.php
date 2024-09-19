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
                        const verifyButton = (response.device_assigned.status === 'Not Responded' || response
                                .device_assigned.status === 'Reject') ?
                            `<button class="btn btn-custom btn-sm mt-3" onclick="verifyDevice(${response.id})">
                                <i class="fas fa-check-circle"></i> Authorize Device
                             </button>` :
                            '';

                        const checkboxes = {
                            loginStatus: response.device_assigned.login_to_device === 1,
                            macAddressVerification: response.device_assigned.send_mac === 1,
                            apiKeyVerification: response.device_assigned.send_mac === 1,
                            deviceSoftwareUpdate: response.device_assigned.connection_status ==="Authorized",
                            securityConnection: response.device_assigned.connection_status === "Authorized"
                        };

                        let checkedCount = 0;
                        for (let key in checkboxes) {
                            if (checkboxes[key]) checkedCount++;
                        }

                        let progress = (checkedCount / Object.keys(checkboxes).length) * 100;

                        const deviceInfoHTML = `
                            <div class="device-details">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="text-primary">${response.name}</h5>
                                        <small>${response.device_assigned.connection_status}</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-${response.device_assigned.connection_status === 'Authorized' ? 'success' : 'danger'}">
                                            ${response.device_assigned.connection_status}
                                        </span>
                                    </div>
                                </div>

                                <ul>
                                    ${generateCheckboxHTML('Login Status', 'fas fa-user-check', checkboxes.loginStatus)}
                                    ${generateCheckboxHTML('MAC Verification', 'fas fa-network-wired', checkboxes.macAddressVerification)}
                                    ${generateCheckboxHTML('API Key', 'fas fa-key', checkboxes.apiKeyVerification)}
                                    ${generateCheckboxHTML('Software Update', 'fas fa-sync-alt', checkboxes.deviceSoftwareUpdate)}
                                    ${generateCheckboxHTML('Secure Connection', 'fas fa-shield-alt', checkboxes.securityConnection)}
                                </ul>

                                <div class="mt-3">
                                    <p><strong>API Key:</strong> ${response.short_apikey}</p>
                                    <p><strong>MAC Address:</strong> ${response.mac_address}</p>
                                </div>

                                <div class="mt-3">
                                    <h6>Device Authentication Progress</h6>
                                    <div class="progress mt-2">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="deviceAuthProgress"
                                            role="progressbar" style="width: ${progress}%;" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">
                                            ${progress}%
                                        </div>
                                    </div>
                                </div>

                                <!-- Insert verify button if necessary -->
                                ${verifyButton}
                            </div>
                        `;

                        $('#deviceDetails').html(deviceInfoHTML);
                    } else {
                        console.error('No device data found.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching device data:', error);
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
