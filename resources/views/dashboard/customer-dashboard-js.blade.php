<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('script')
    @if ($showNewUserModel)
        <script>
            $(document).ready(function() {
                $('#terms-conditions').modal('show');
            });
        </script>
    @endif

    @if ($firstPassword)
        <script>
            $(document).ready(function() {
                // You might want to delay showing this modal if the terms modal is also shown
                setTimeout(function() {
                    $('#password-change-modal').modal('show');
                }, 500); // Adjust the timeout as necessary
            });
        </script>
    @endif

    <script>
        // Global deviceMap to store device information
        let deviceMap = {};

        // Load devices when the document is ready
        $(document).ready(function() {
            loadDevices();
        });

        // Function to load devices and generate cards
        function loadDevices() {
            $.ajax({
                url: '/customer/devices/data', // Your API endpoint
                type: 'GET',
                dataType: 'json',
                success: function(devices) {
                    let contentHtml = ''; // This will store the generated cards
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

                            // Generate each device card
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
                    $('#myDevices').html(contentHtml);
                    $('#top-message').html(topMessageHtml);

                    // Initialize tooltips (if using tooltips in device cards)
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
                error: function(xhr, status, error) {
                    // Handle the error and display an appropriate message
                    console.error("An error occurred: ", error);
                    $('#myDevices').html(fetchDevicesErrorMessage());
                }
            });
        }

        // Function to create the HTML for each device card
        function deviceCardTemplate(device) {
            const isAuthorized = device.device_assigned.connection_status === 'Authorized';
            const isPending = device.device_assigned.status === 'Not Responded' || device.device_assigned.status ===
                'Reject';
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
                        <strong>Mac :</strong> ${device.mac_address || 'N/A'}
                    </li>
                    <li class="list-group-item">
                        <strong>ApiKey :</strong> ${device.short_apikey || 'N/A'}
                    </li>
                </ul>
                <!-- Action Buttons -->

            </div>
        </div>
    </div>
    `;
        }

        let currentDeviceId;

        function showActivateDeviceModal(deviceId) {
            const device = deviceMap[deviceId];
            const deviceName = device.name;

            // Store the current device ID
            currentDeviceId = deviceId;

            // Set the modal title and body content
            $('#activateDeviceModal .modal-title').text('Device Not Authorized Yet !');
            $('#activateDeviceModal .modal-body').html(`
            <div class="text-center">
            <i class="fas fa-lock fa-3x text-danger mb-3"></i>
            <p>Please login to device and activate the device <strong>${deviceName}</strong> to access its features.</p>
            </div>
            `);

            // Show the modal
            $('#activateDeviceModal').modal('show');
        }

        function redirectToDeviceShow() {
            window.location.href = `/devices/${currentDeviceId}`;
        }



        // Function to verify the device (Accept button)
        function verifyDevice(deviceId) {
            // Prevent the card click event from firing
            event.stopPropagation();

            // Implement the verification logic here
            // For example, send an AJAX request to verify the device
            $.ajax({
                url: `/devices/${deviceId}/verify`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // Include CSRF token if using Laravel
                },
                success: function(response) {
                    // Handle success (e.g., reload the devices)
                    loadDevices();
                    alert('Device verified successfully!');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error("An error occurred: ", error);
                    alert('Failed to verify the device.');
                }
            });
        }

        // Function to display a message when no devices are assigned
        function noDevicesAssignedMessage() {
            return `
    <div class="col-12">
        <div class="alert alert-info text-center">
            <p>You have no devices assigned yet.</p>
            <a href="/devices/create" class="btn btn-primary">Add New Device</a>
        </div>
    </div>
    `;
        }

        // Function to display an error message when devices cannot be fetched
        function fetchDevicesErrorMessage() {
            return `
    <div class="col-12">
        <div class="alert alert-danger text-center">
            <p>Sorry, we couldn't load your devices at this time. Please try again later.</p>
        </div>
    </div>
    `;
        }



        $(function() {
            $('#terms-and-conditions-form').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#terms-and-conditions-form')[0]);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('users.terms-and-conditions') }}',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        if (resp.code == '{{ __('statuscode.CODE200') }}') {
                            $('#terms-conditions').modal('toggle');
                            $(".modal-backdrop").fadeOut();
                            Swal.fire({
                                title: resp.Message,
                                html: 'Please wait for a moment...',
                                timer: 5000, // Adjust the timer if needed
                                timerProgressBar: true,
                                didClose: () => {
                                    $('#password-change-modal').modal('show');
                                }
                            });

                        } else {
                            toastr.error(resp.Message);
                            $(".submit").attr("disabled", false);
                        }
                    },
                    error: function(data) {
                        $(".submit").attr("disabled", false);
                        var errors = data.responseJSON;
                        $.each(errors.errors, function(key, value) {
                            var ele = "#" + key;
                            $(ele).addClass('error');
                            $('<label class="error">' + value + '</label>').insertAfter(
                                ele);
                        });
                    }
                });
            });
            $('#form-users-change-password').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#form-users-change-password')[0]);
                $.ajax({
                    method: 'post',
                    url: '{{ route('users.password-change', $user->id) }}',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        $('#password-change-modal').modal('toggle');
                        $(".modal-backdrop").fadeOut();
                        Swal.fire({
                            title: resp.Message,
                            html: 'Please wait for a moment...',
                            timer: 5000, // Adjust the timer if needed
                            timerProgressBar: true,
                            didClose: () => {
                                // Show the password change modal after the Swal message closes
                                // $('#password-change-modal').modal('show');
                            }
                        });
                    },
                    error: function(data) {
                        $(".submit").attr("disabled", false);
                        var errors = data.responseJSON;
                        $.each(errors.errors, function(key, value) {
                            var ele = "#" + key;
                            $(ele).removeClass('errors');
                            $(ele).addClass('errors');
                            var parentInputGroup = $(ele).closest('.input-group-merge');
                            if (parentInputGroup.length > 0) {
                                $('<label class="error">' + value + '</label>')
                                    .insertAfter(
                                        parentInputGroup);
                            } else {
                                $('<label class="error">' + value + '</label>')
                                    .insertAfter(ele);
                            }
                        });
                    }
                });
            });
        });

        function getRandomBackgroundColor() {
            const colors = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-dark'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        function generateDeviceCard(device, verifyButton) {
            const statusIconColor = device.status === 'Active' ? 'green' : '#dc3545';
            const isPending = device.device_assigned.status === 'Not Responded' || device.device_assigned.status ===
                'Reject';
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
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card custom-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title">
                                        <i class="bi bi-laptop"></i> ${device.name}
                                    </h5>
                                    <h6 class="card-subtitle mb-2">
                                        <i class="bi bi-circle-fill" style="color: ${statusIconColor};"></i> ${device.device_assigned.connection_status}
                                    </h6>
                                </div>

                                <p class="card-text"><strong>Location:</strong> ${device.device_assigned.device_location.location_name || 'i Hack'}</p>
                                <p class="card-text"><strong>Last Sync:</strong> ${device.lastActive || 'Not Synced Yet'}</p>
                                <p class="card-text"><strong>Connection Status:</strong> ${device.device_assigned.connection_status || 'Authorized'}</p>
                                <p class="card-text"><strong>API KEY:</strong><span style="color: ${statusIconColor};"> ${device.short_apikey}</span></p>
                                <p class="card-text"><strong>Mac Address:</strong><span style="color: ${statusIconColor};"> ${device.mac_address}</span></p>

                                ${device.device_assigned.login_to_device == false || device.device_assigned.login_to_device == 0 ? `
                                                                                                                                        ${notLoggedInMessage}
                                                                                                                                    ` : isPending ? `
                                                                                                                                        ${needsAcceptanceMessage}
                                                                                                                                        ${verifyButton}
                                                                                                                                    ` : ''}
                            </div>
                            <div class="card-footer">
                                ${device.device_assigned.status === 'Accept' ? `
                                                                                                                                        <p class="card-text mt-2 mb-0 text-success">${deviceStatusText}</p>
                                                                                                                                    ` : ''}
                                ${device.device_assigned.login_to_device == true || device.device_assigned.login_to_device == 1 ? `
                                                                                                                                        <button class="btn btn-primary mt-3" onclick="viewGraph('${device.id}')">See Graph</button>
                                                                                                                                    ` : ''}
                                <button class="btn btn-primary btn-circle mt-3" data-bs-toggle="modal"
                                    data-bs-target="#modalToggle" onclick="activateDevice('${device.id}')">Activate</button>
                            </div>
                        </div>
                    </div>
                `;
        }

        function activateDevice(deviceId) {

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



        // document.addEventListener("DOMContentLoaded", function () {
        //     fetch('/device-faults/data')
        //         .then(response => response.json())
        //         .then(data => {
        //             if (!data.faultData || data.faultData.length === 0) {
        //                 console.error("No fault data found.");
        //                 return;
        //             }

        //             let labels = [];
        //             let datasetData = {};

        //             data.faultData.forEach(entry => {
        //                 let deviceId = "Device " + entry.device_id;
        //                 let faultType = entry.fault_status;

        //                 if (!datasetData[faultType]) {
        //                     datasetData[faultType] = {};
        //                 }

        //                 datasetData[faultType][deviceId] = entry.count;

        //                 if (!labels.includes(deviceId)) {
        //                     labels.push(deviceId);
        //                 }
        //             });

        //             let datasets = [];
        //             let colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#BA68C8'];

        //             Object.keys(datasetData).forEach((faultType, index) => {
        //                 let dataValues = labels.map(device => datasetData[faultType][device] || 0);

        //                 datasets.push({
        //                     label: faultType,
        //                     data: dataValues,
        //                     backgroundColor: colors[index % colors.length]
        //                 });
        //             });

        //             let ctx = document.getElementById('faultChart').getContext('2d');
        //             new Chart(ctx, {
        //                 type: 'bar', // You can change this to 'pie' for a pie chart
        //                 data: {
        //                     labels: labels,
        //                     datasets: datasets
        //                 },
        //                 options: {
        //                     responsive: true,
        //                     scales: {
        //                         x: {
        //                             title: { display: true, text: "Assigned Devices" }
        //                         },
        //                         y: {
        //                             title: { display: true, text: "Fault Count" },
        //                             beginAtZero: true
        //                         }
        //                     }
        //                 }
        //             });
        //         })
        //         .catch(error => console.error("Error fetching fault data:", error));
        // });

        document.addEventListener("DOMContentLoaded", function() {
            fetch('/device-faults/data')
                .then(response => response.json())
                .then(data => {
                    if (!data.faultData || data.faultData.length === 0) {
                        console.error("No fault data found.");
                        document.getElementById("totalFaults").innerText = 0;
                        return;
                    }

                    let totalFaults = 0;
                    let labels = [];
                    let datasetData = {};

                    data.faultData.forEach(entry => {
                        let deviceName = entry.name; // Use device name instead of ID
                        let faultType = entry.fault_status;

                        totalFaults += entry.count;

                        if (!datasetData[faultType]) {
                            datasetData[faultType] = {};
                        }

                        datasetData[faultType][deviceName] = entry.count;

                        if (!labels.includes(deviceName)) {
                            labels.push(deviceName);
                        }
                    });

                    document.getElementById("totalFaults").innerText = totalFaults;

                    let datasets = [];
                    let colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#BA68C8'];

                    Object.keys(datasetData).forEach((faultType, index) => {
                        let dataValues = labels.map(device => datasetData[faultType][device] || 0);

                        datasets.push({
                            label: faultType,
                            data: dataValues,
                            backgroundColor: colors[index % colors.length]
                        });
                    });

                    let ctx = document.getElementById('faultChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: "Assigned Devices"
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: "Fault Count"
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error("Error fetching fault data:", error));
        });
    </script>
@endsection
