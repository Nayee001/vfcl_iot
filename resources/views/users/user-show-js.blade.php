@section('script')
    <script>
        $(function() {
            var table = $('.users-show-hierarchy-ajax-datatables').DataTable({
                processing: true,
                serverSide: true,

                ajax: "{{ route('users-show-hierarchy-ajax-datatables', $user->id) }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'fname',
                        name: 'fname'
                    },
                    {
                        data: 'lname',
                        name: 'lname'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'creater',
                        name: 'creater'
                    },
                    {
                        data: 'devices',
                        name: 'devices'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false
                    },
                ]
            });
        });

        $(document).ready(function() {
            const userId = '{{ $user->id }}'; // Get the user ID dynamically
            loadDevices(userId);

            function loadDevices(userId) {
                $.ajax({
                    url: `/customer/assinged/devices/data/${userId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(devices) {
                        console.log(devices);
                        let contentHtml = '';
                        let topMessageHtml = '';
                        let deviceNames = [];

                        if (devices.length > 0) {
                            devices.forEach(device => {
                                if (device.device_assigned && (device.device_assigned.status ===
                                        'Not Responded' || device.device_assigned.status ===
                                        'Reject')) {
                                    deviceNames.push(device.name);
                                }
                                const verifyButton = (device.device_assigned && (device
                                        .device_assigned.status === 'Not Responded' ||
                                        device.device_assigned.status === 'Reject')) ?
                                    `<button class="btn btn-warning btn-sm" onclick="verifyDevice(${device.id})">Accept</button>` :
                                    '';
                                contentHtml += generateDeviceCard(device, verifyButton);
                            });

                            if (deviceNames.length > 0) {
                                topMessageHtml = `
                        <div class="alert alert-warning" role="alert">
                            <b style="color: red;">${deviceNames.join(', ')} need to be accepted from the command center</b>
                        </div>
                    `;
                            }

                            $('#devices').html(contentHtml);
                            $('#top-message').html(topMessageHtml);
                            $('#no-devices-assigned').hide(); // Hide the no devices message
                        } else {
                            $('#no-devices-assigned').show(); // Show the no devices message
                            $('#devices').html(''); // Clear devices container
                            $('#top-message').html(''); // Clear top message container
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: ", error);
                        $('#devices').html(fetchDevicesErrorMessage());
                    }
                });
            }

            function generateDeviceCard(device, verifyButton) {
                const statusIconColor = device.device_assigned && device.device_assigned.connection_status ===
                    'Active' ? 'green' : '#dc3545';
                const isPending = device.device_assigned && (device.device_assigned.status === 'Not Responded' ||
                    device.device_assigned.status === 'Reject');
                const deviceStatusText = device.device_assigned && device.device_assigned.status === 'Accept' ?
                    `Accepted by ${device.device_assigned.assignee.fname}` :
                    (device.device_assigned ? device.device_assigned.status : '--');

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
                            <i class="bi bi-circle-fill" style="color: ${statusIconColor};"></i> ${device.device_assigned ? device.device_assigned.connection_status : 'Inactive'}
                        </h6>
                    </div>
                    <p class="card-text"><strong>Description:</strong> ${device.description}</p>
                    <p class="card-text"><strong>Location:</strong> ${device.device_assigned ? device.device_assigned.device_location.location_name : 'i Hack'}</p>
                    <p class="card-text"><strong>Last Sync:</strong> ${device.lastActive || '22 May 2024'}</p>
                    <p class="card-text"><strong>Connection Status:</strong> ${device.device_assigned ? device.device_assigned.connection_status : 'Inactive'}</p>
                    ${device.device_assigned && !device.device_assigned.login_to_device ? `
                                            ${notLoggedInMessage}
                                        ` : isPending ? `
                                            ${needsAcceptanceMessage}
                                            ${verifyButton}
                                        ` : ''}
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <small class="text-muted">API KEY: <span class="text-secondary">${device.short_apikey}</span></small>
                        ${device.device_assigned && device.device_assigned.status === 'Accept' ? `
                                        <p class="card-text mt-2 mb-0 text-success">${deviceStatusText}</p>
                                    ` : ''}
                        ${device.device_assigned && device.device_assigned.login_to_device ? `
                                        <button class="btn btn-primary mt-3" onclick="viewGraph('${device.id}')">See Graph</button>
                                    ` : ''}
                    </div>

                    <button onclick="resetDevice(${device.id})" title="Reset Device" type="button" class="btn rounded-pill btn-icon btn-outline-danger position-relative notification-button">
                        <i class='bx bx-reset'></i>
                                </button>
                </div>
            </div>
        </div>
    `;
            }

            function fetchDevicesErrorMessage() {
                return `
        <div class="col">
            <div class="alert alert-danger" role="alert">
                An error occurred while fetching the devices.
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
                        alert(`Failed to verify device ID ${deviceId}`);
                    });
            };

            function showVerificationModal(data, deviceId) {
                const contentHtml = `
        Device Name: <b>${data.name}</b> <br>
        Status: ${data.status}<br>
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

            window.resetDevice = function(deviceId) {
                Swal.fire({
                    title: 'Are you sure  want to rest this device ?',
                    text: "You won't be able to undo this action. Your credentials will be removed from your device, and everything on it will be permanently reset.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reset it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('reset-device') }}/${deviceId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire(
                                        'Reset!',
                                        'Your device has been reset.',
                                        'success'
                                    ).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Failed!',
                                        'Device reset failed.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error('Error resetting device:', error);
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while resetting the device.',
                                    'error'
                                );
                            });
                    }
                });
            };

            function viewGraph(deviceId) {
                console.log('Viewing graph for device:', deviceId);
                // Add your logic to view the graph for the device with the given deviceId
                // You can redirect to another page, open a modal, or anything else you need
            }
        });
    </script>
@endsection
