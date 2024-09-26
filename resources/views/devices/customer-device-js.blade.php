@section('script')
    <script>
        $(document).ready(function() {
            // Global deviceMap to store device information
            let deviceMap = {};

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

                        // Clear the deviceMap before populating
                        deviceMap = {};

                        if (devices.length > 0) {
                            devices.forEach(device => {
                                // Add device to deviceMap
                                deviceMap[device.id] = device;

                                // Check if the device needs authorization
                                if (device.device_assigned.status === 'Not Responded' || device
                                    .device_assigned.status === 'Reject') {
                                    deviceNames.push(device.name);
                                }

                                // Generate each device card using deviceCardTemplate
                                contentHtml += deviceCardTemplate(device);
                            });

                            // Add a warning message for devices that need authorization
                            if (deviceNames.length > 0) {
                                topMessageHtml = `
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Attention!</strong> The following devices need authorization: ${deviceNames.join(', ')}.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            `;
                            }
                        } else {
                            // No devices found
                            contentHtml = noDevicesAssignedMessage();
                        }

                        // Populate the device cards and any top messages on the page
                        $('#devices').html(contentHtml);
                        $('#top-message').html(topMessageHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: ", error);
                        $('#devices').html(fetchDevicesErrorMessage());
                    }
                });
            }

            // Function to create the HTML for each device card
            function deviceCardTemplate(device) {
                const isAuthorized = device.device_assigned.connection_status === 'Authorized';
                const isPending = device.device_assigned.status === 'Not Responded' || device.device_assigned
                    .status === 'Reject';
                const isOnline = device.device_assigned
                .login_to_device; // Assuming this property indicates if the device is online

                // Add the device to the deviceMap for later reference
                deviceMap[device.id] = device;

                // Determine card classes based on device status
                const cardClass = isAuthorized ? 'border-success' : 'border-danger';
                const statusBadgeClass = isAuthorized ? 'bg-success' : 'bg-danger';
                const statusText = isAuthorized ? 'Authorized' : 'Not Authorized';

                // Determine online/offline status
                const onlineStatusBadgeClass = isOnline ? 'bg-info' : 'bg-secondary';
                const onlineStatusText = isOnline ? 'Online' : 'Offline';

                // Determine if the "Accept" button should be displayed
                const verifyButton = isPending ?
                    `<button class="btn btn-warning btn-sm mt-2" onclick="event.stopPropagation(); verifyDevice(${device.id})">Accept</button>` :
                    '';

                // Determine the onclick event based on authorization status
                const onClickEvent = isAuthorized ?
                    `onclick="showData(${device.id})"` :
                    `onclick="showActivateDeviceModal(${device.id})"`;

                // Return the HTML for a single device card
                return `
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card h-100 ${cardClass}" style="cursor: pointer;" ${onClickEvent}>
                <div class="card-body d-flex flex-column">
                    <!-- Device Icon or Image -->
                    <div class="text-center mb-3">
                        <i class="fas fa-microchip fa-3x text-primary"></i>
                    </div>
                    <!-- Device Name -->
                    <h5 class="card-title text-primary text-center">${device.name}</h5>
                    <!-- Device Status Badges -->
                    <div class="text-center mb-2">
                        <span class="badge ${statusBadgeClass} me-1">${statusText}</span>
                        ${isPending ? `<span class="badge bg-warning text-dark">Pending</span>` : ''}
                        <span class="badge ${onlineStatusBadgeClass} ms-1">${onlineStatusText}</span>
                    </div>
                    <!-- Additional Device Information -->
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            <strong>MAC:</strong> ${device.mac_address || 'N/A'}
                        </li>
                        <li class="list-group-item">
                            <strong>API Key:</strong> ${device.short_apikey || 'N/A'}
                        </li>
                    </ul>
                    <!-- Action Buttons -->
                    ${verifyButton}
                </div>
            </div>
        </div>
        `;
            }

            // Function to show data when device is authorized
            function showData(deviceId) {
                // Redirect to the device details page
                window.location.href = `/devices/${deviceId}`;
            }

            // Function to show modal for activating the device (when device is not authorized)
            let currentDeviceId;

            function showActivateDeviceModal(deviceId) {
                const device = deviceMap[deviceId];
                const deviceName = device.name;

                // Store the current device ID
                currentDeviceId = deviceId;

                // Set the modal title and body content
                $('#activateDeviceModal .modal-title').text('Device Not Authorized');
                $('#activateDeviceModal .modal-body').html(`
                <div class="text-center">
                    <i class="fas fa-lock fa-3x text-danger mb-3"></i>
                    <p>Please activate the device <strong>${deviceName}</strong> to access its features.</p>
                </div>
            `);

                // Show the modal
                $('#activateDeviceModal').modal('show');
            }

            function redirectToDeviceShow() {
                window.location.href = `/devices/${currentDeviceId}`;
            }

            function noDevicesAssignedMessage() {
                return `
                <div class="col">
                    <div class="alert alert-info text-center" role="alert">
                        You have no devices assigned yet.
                    </div>
                </div>
            `;
            }

            function fetchDevicesErrorMessage() {
                return `
                <div class="col">
                    <div class="alert alert-danger text-center" role="alert">
                        An error occurred while fetching the devices. Please try again later.
                    </div>
                </div>
            `;
            }

            window.verifyDevice = function(deviceId) {
                fetch(`{{ url('verify-device-model') }}/${deviceId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            showVerificationModal(data, deviceId);
                        }
                    })
                    .catch(error => {
                        console.error('Error during verification:', error);
                        Swal.fire({
                            title: "Error",
                            text: `Failed to verify device ID ${deviceId}`,
                            icon: "error"
                        });
                    });
            };

            function showVerificationModal(data, deviceId) {
                const contentHtml = `
                <p>Device Name: <strong>${data.name}</strong></p>
                <p>Status: ${data.status}</p>
            `;

                const verifyAgainButton = `
                <button type="button" class="btn btn-warning w-100 mt-3" onclick="sendToDevice(${deviceId})">
                    Accept
                </button>
            `;

                $('#modalContent').html(contentHtml + verifyAgainButton);
                $('#verificationModal').modal('show');
            }

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
                        }, 1000);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then(() => {
                    // After the timer, proceed with activation
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
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error during device activation:', error);
                            Swal.fire({
                                title: "Error",
                                text: `Failed to authorize device ID ${deviceId}. Please try again.`,
                                icon: "error"
                            });
                        });
                });
            }
        });
    </script>
@endsection
