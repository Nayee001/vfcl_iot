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
                    if (devices.length > 0) {
                        devices.forEach(device => {
                            const verifyButton = device.device_assigned.status === 'Not Responded' ?
                                `<button class="btn btn-warning btn-sm" onclick="verifyDevice(${device.id})">Verify</button>` : '';
                            contentHtml += generateDeviceCard(device, verifyButton);
                        });
                    } else {
                        contentHtml = noDevicesAssignedMessage();
                    }
                    $('#devices').html(contentHtml);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: ", error);
                    $('#devices').html(fetchDevicesErrorMessage());
                }
            });
        }

        // Generate device card HTML
        function generateDeviceCard(device, verifyButton) {
            return `
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="bi bi-laptop"></i> ${device.name}
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <i class="bi bi-circle-fill" style="color: ${device.status === 'Active' ? 'green' : 'red'};"></i> ${device.status}
                            </h6>
                            <p class="card-text">${device.description}</p>
                            <p class="card-text">
                                ${device.device_assigned.status}
                                ${verifyButton}
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <small class="text-muted">API KEY: <span class="text-secondary">${device.short_apikey}</span></small>
                        </div>
                    </div>
                </div>
            `;
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
            console.log("Verifying device with ID:", deviceId);
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
            verifyAgainButton.textContent = 'Verify Device';
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
                title: 'Device Authorization',
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
                    // Optional: Show another modal after Swal closes
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
                    alert(`Failed to verify device ID ${deviceId}`);
                });
        };
    });
</script>
@endsection
