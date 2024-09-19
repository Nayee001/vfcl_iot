@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                const preloader = document.getElementById('preloader');
                if (preloader) {
                    preloader.classList.add('preloader-hidden');
                    document.getElementById('main-content').style.display = 'block';
                }
            }, 2000); // Adjust this time as needed
        });

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
                                      <b style="color: red;"> ${deviceNames.join(', ')} ; need to be Authorized from the command center by the user</b>
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
                const statusIconColor = device.status === 'Authorized' ? 'green' : '#dc3545';
                const isPending = device.device_assigned.status === 'Not Responded' || device.device_assigned.status === 'Reject';

                // Define an array of possible background colors
                const colors = ['#f8d7da', '#d4edda', '#cce5ff', '#fff3cd', '#d1ecf1', '#e2e3e5', '#f1f3f5'];
                // Randomly select a color from the array
                const randomColor = colors[Math.floor(Math.random() * colors.length)];

                return `
                    <div class="col-6 col-md-3">
                        <a href="/devices/${device.id}" class="card card-custom text-center h-100" style="background-color: ${randomColor};">
                            <div class="card-body">
                                <div class="icon-wrapper">
                                    <i class="bx bx-line-chart" style="color: ${statusIconColor}; font-size: 2rem;"></i>
                                </div>
                                <h6 class="card-title">${device.name}</h6>
                                ${isPending ? `<span class="badge badge-new">NEW</span>` : ''}
                            </div>
                        </a>
                    </div>
                `;
            }



            function viewGraph(deviceId) {
                console.log('Viewing graph for device:', deviceId);
            }

            function noDevicesAssignedMessage() {
                return `
                    <div class="col">
                        <div class="alert alert-info" role="alert">
                            There are no devices assigned to you!
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
        });
    </script>
@endsection
