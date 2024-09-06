@section('script')
    <style>
        /* Custom styles (same as before) */
        .device-card h5 {
            font-weight: 700; /* Bold title */
            color: #17a2b8; /* Bright teal color */
        }

        .device-card small {
            color: #6c757d; /* Muted text */
        }

        .progress-bar {
            border-radius: 5px; /* Rounded progress bar */
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
        // Function to dynamically load device data from the server and update the DOM, including verification button and progress bar
        function loadDeviceData(deviceId) {
            $.ajax({
                url: `/devices/data/${deviceId}`, // API route to get device data by ID
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        const verifyButton = (response.device_assigned.status === 'Not Responded' || response.device_assigned.status === 'Reject') ?
                            `<button class="btn btn-custom btn-sm mt-3" onclick="verifyDevice(${response.id})">
                                <i class="fas fa-check-circle"></i> Accept
                             </button>` :
                            ''; // Conditionally define the verify button

                        const checkboxes = {
                            loginStatus: response.device_assigned.login_to_device === 1,
                            macAddressVerification: response.device_assigned.send_mac === 1,
                            apiKeyVerification: response.device_assigned.send_mac === 1,
                            deviceSoftwareUpdate: response.device_assigned.connection_status === "Authorized",
                            securityConnection: response.device_assigned.connection_status === "Authorized"
                        };

                        let checkedCount = 0;
                        for (let key in checkboxes) {
                            if (checkboxes[key]) checkedCount++;
                        }

                        let progress = (checkedCount / Object.keys(checkboxes).length) * 100;

                        const deviceInfoHTML = `
                            <div class="device-card">
                                <div class="device-section d-flex align-items-center justify-content-between">
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

                                <div class="device-section">
                                    <ul class="list-unstyled d-flex flex-wrap gap-3">
                                        <li class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="loginStatus" ${checkboxes.loginStatus ? 'checked' : ''} disabled>
                                            <label class="form-check-label" for="loginStatus">
                                                <i class="fas fa-user-check"></i> Login Status
                                            </label>
                                        </li>
                                        <li class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="macAddressVerification" ${checkboxes.macAddressVerification ? 'checked' : ''} disabled>
                                            <label class="form-check-label" for="macAddressVerification">
                                                <i class="fas fa-network-wired"></i> MAC Verification
                                            </label>
                                        </li>
                                        <li class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="apiKeyVerification" ${checkboxes.apiKeyVerification ? 'checked' : ''} disabled>
                                            <label class="form-check-label" for="apiKeyVerification">
                                                <i class="fas fa-key"></i> API Key
                                            </label>
                                        </li>
                                        <li class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="deviceSoftwareUpdate" ${checkboxes.deviceSoftwareUpdate ? 'checked' : ''} disabled>
                                            <label class="form-check-label" for="deviceSoftwareUpdate">
                                                <i class="fas fa-sync-alt"></i> Software Update
                                            </label>
                                        </li>
                                        <li class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="securityConnection" ${checkboxes.securityConnection ? 'checked' : ''} disabled>
                                            <label class="form-check-label" for="securityConnection">
                                                <i class="fas fa-shield-alt"></i> Secure Connection
                                            </label>
                                        </li>
                                    </ul>
                                </div>

                                <div class="device-section">
                                    <p><strong>API Key: </strong>${response.short_apikey}</p>
                                    <p><strong>MAC Address: </strong>${response.mac_address}</p>
                                </div>

                                <div class="device-section">
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

        // Define the verifyDevice function in the global scope
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

        // Function to show the verification modal (example from previous code)
        function showVerificationModal(data, deviceId) {
            const contentHtml = `
                <p>Device Name: <b>${data.name}</b></p>
                <p>Status: ${data.status}</p>
            `;

            const verifyAgainButton = document.createElement('button');
            verifyAgainButton.type = 'button';
            verifyAgainButton.className = 'btn btn-warning';
            verifyAgainButton.textContent = 'Accept';
            verifyAgainButton.onclick = function() {
                sendToDevice(deviceId);
            };

            document.getElementById('modalContent').innerHTML = contentHtml;
            document.getElementById('modalContent').appendChild(verifyAgainButton);
            $('#verificationModal').modal('show');
        }

        // Call the function on document ready or when necessary
        $(document).ready(function() {
            const deviceId = {{ $deviceData->id }}; // Replace with dynamic device ID
            loadDeviceData(deviceId);

            // Optionally refresh the data every 30 seconds for real-time updates
            setInterval(function() {
                loadDeviceData(deviceId); // Reload device data every 30 seconds
            }, 30000); // 30 seconds interval
        });
    </script>
@endsection
