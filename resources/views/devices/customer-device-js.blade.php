@section('script')
    <script>
        $(document).ready(function() {
            loadDevices();

            // Load devices function
            function loadDevices() {
                $.ajax({
                    url: '/customer/devices/data',
                    type: 'GET',
                    dataType: 'json',
                    success: function(devices) {
                        let contentHtml = '';
                        let topMessageHtml = '';
                        let deviceNames = [];
                        if (devices.length > 0) {
                            devices.forEach(device => {
                                if (device.device_assigned.status === 'Not Responded' || device
                                    .device_assigned.status === 'Reject') {
                                    deviceNames.push(device.name);
                                }
                                const verifyButton = (device.device_assigned.status ===
                                        'Not Responded' || device.device_assigned.status ===
                                        'Reject') ?
                                    `<button class="btn btn-warning btn-sm" onclick="verifyDevice(${device.id})">Accept</button>` :
                                    '';
                                contentHtml += generateDeviceCard(device, verifyButton);
                            });
                            if (deviceNames.length > 0) {
                                topMessageHtml = `
                                <div class="alert alert-warning" role="alert">
                                  <b style="color: red;"> ${deviceNames.join(', ')} ;  need to be accepted from the command center</b>
                                </div>
                            `;
                            }
                        } else {
                            contentHtml = noDevicesAssignedMessage();
                        }
                        $('#devices').html(contentHtml);
                        $('#top-message').html(topMessageHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: ", error);
                        $('#devices').html(fetchDevicesErrorMessage());
                    }
                });
            }

            function generateDeviceCard(device, verifyButton) {
                console.log(device);
                const statusIconColor = device.status == 'Active' ? 'green' : '#dc3545';
                const isPending = device.device_assigned.status === 'Not Responded' || device.device_assigned
                    .status === 'Reject';
                const deviceStatusText = device.device_assigned.status === 'Accept' ?
                    `Accepted by ${device.device_assigned.assignee.fname}` :
                    device.device_assigned.status;

                const notLoggedInMessage = `
        <div class="alert alert-danger" role="alert">
            This device is not logged in or plugged in.
        </div>
    `;

                const needsAcceptanceMessage = `
        <div class="alert alert-warning" role="alert">
            This device needs to be accepted from the command center.
        </div>
    `;

                return `
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card custom-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">
                            <i class="bi bi-laptop"></i> ${device.name}
                        </h5>
                        <h6 class="card-subtitle mb-2">
                            <i class="bi bi-circle-fill" style="color: ${statusIconColor};"></i> ${device.status}
                        </h6>
                    </div>
                    <p class="card-text"><strong>Description:</strong> ${device.description}</p>
                    <p class="card-text"><strong>Location:</strong> ${device.device_assigned.device_location.location_name || 'i Hack'}</p>
                    <p class="card-text"><strong>Last Sync:</strong> ${device.lastActive || '22 May 2024'}</p>
                    <p class="card-text"><strong>Connection Status:</strong> ${device.device_assigned.connection_status || 'Active'}</p>
                    ${device.device_assigned.login_to_device == false || device.device_assigned.login_to_device == 0 ? `
                            ${notLoggedInMessage}
                        ` : isPending ? ``
                            ${needsAcceptanceMessage}
                            ${verifyButton}
                        ` : ''}
                </div>
                <div class="card-footer">
                    <small class="text-muted">API KEY: <span class="text-secondary">${device.short_apikey}</span></small>
                    ${device.device_assigned.status === 'Accept' ? `
                            <p class="card-text mt-2 mb-0 text-success">${deviceStatusText}</p>
                        ` : ''}
                    ${device.device_assigned.login_to_device == true || device.device_assigned.login_to_device == 1 ? `
                            <button class="btn btn-primary mt-3" onclick="viewGraph('${device.id}')">See Graph</button>
                        ` : ''}
                </div>
            </div>
        </div>
    `;
            }

            function viewGraph(deviceId) {
                // Add your logic to view the graph for the device with the given deviceId
                console.log('Viewing graph for device:', deviceId);
                // You can redirect to another page, open a modal, or anything else you need
            }


            // No devices assigned message HTML
            function noDevicesAssignedMessage() {
                return `
                <div class="col">
                    <div class="alert alert-info" role="alert">
                        There are no devices assigned to you!
                    </div>
                </div>
                `;
            }

            // Fetch devices error message HTML
            function fetchDevicesErrorMessage() {
                return `
                <div class="col">
                    <div class="alert alert-danger" role="alert">
                        An error occurred while fetching the devices.
                    </div>
                </div>
                `;
            }

            // Verify device function
            window.verifyDevice = function(deviceId) {
                fetch(`{{ url('verify-device-model') }}/${deviceId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            showVerificationModal(data, deviceId);
                        }
                    })
                    .catch(error => {
                        console.log('Error during verification:', error);
                        alert(`Failed to verify device ID ${deviceId}`);
                    });
            };

            // Show verification modal
            function showVerificationModal(data, deviceId) {
                const contentHtml = `
                Device Name: <b>${data.name}</b> <br>
                Status: ${data.status}<br>
            `;

                // Create Verify Again Button dynamically
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

            // Send to device function
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
                        console.log('Error during verification:', error);
                        alert(`Failed to accept device ID ${deviceId}`);
                    });
            };
        });
    </script>
@endsection
