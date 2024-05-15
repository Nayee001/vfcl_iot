@section('script')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/customer/devices/data',
                type: 'GET',
                dataType: 'json',
                success: function(devices) {
                    let contentHtml = '';
                    if (devices.length > 0) {
                        devices.forEach(function(device) {
                            console.log(device);
                            let verifyButton = '';
                            if (device.device_assigned.status === 'Not Responded') {
                                verifyButton =
                                    '<button class="btn btn-warning btn-sm" onclick="verifyDevice(' +
                                    device.id + ')">Verify</button>';
                            }
                            // Adding icons and additional details like model and last updated time
                            contentHtml += `
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
                        });
                    } else {
                        contentHtml = `
                    <div class="col">
                        <div class="alert alert-info" role="alert">
                            There are no devices assigned to you!
                        </div>
                    </div>`;
                    }
                    $('#devices').html(contentHtml);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: ", error);
                    $('#devices').html(`
                <div class="col">
                    <div class="alert alert-danger" role="alert">
                        An error occurred while fetching the devices.
                    </div>
                </div>`);
                }
            });
        });

        function verifyDevice(deviceId) {
            console.log("Verifying device with ID:", deviceId);
            fetch(`{{ url('verify-device-model') }}/${deviceId}`, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        // Building the content HTML
                        var contentHtml = `Device Name: <b>${data.name}</b> <br>`;
                        contentHtml += `Status: ${data.status}<br>`;

                        // Create Reject Button dynamically
                        const rejectButton = document.createElement('button');
                        rejectButton.type = 'button';
                        rejectButton.className = 'btn btn-danger';
                        rejectButton.textContent = 'Reject';
                        rejectButton.setAttribute('data-dismiss', 'modal');

                        // Create Verify Again Button dynamically
                        const verifyAgainButton = document.createElement('button');
                        verifyAgainButton.type = 'button';
                        verifyAgainButton.className = 'btn btn-warning';
                        verifyAgainButton.textContent = 'Verify Again';
                        verifyAgainButton.onclick = function() {
                            sendToDevice(deviceId); // This will re-verify using the same device ID
                        };

                        // Add buttons to the content HTML
                        document.getElementById('modalContent').innerHTML = contentHtml;
                        // document.getElementById('modalContent').appendChild(rejectButton);
                        document.getElementById('modalContent').appendChild(verifyAgainButton);

                        $('#verificationModal').modal('show');
                    }
                })
                .catch(error => {
                    console.log('Error during verification:', error);
                    alert(`Failed to verify device ID ${deviceId}`);
                });
        }

        const verifyButton = document.getElementById('verifyButton');
        if (verifyButton) {
            verifyButton.addEventListener('click', function() {
                const deviceId = this.getAttribute('data-device-id');
                sendToDevice(deviceId);
            });
        }


        function sendToDevice(deviceId) {
            console.log("sendToDevice device with ID:", deviceId);
            fetch(`{{ url('send-device-mqtt') }}/${deviceId}`, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        Swal.fire({
                            title: "Good job!",
                            text: "You clicked the button!",
                            icon: "success"
                        }).then((result) => {
                            if (result.value) {
                                // Trigger the modal to hide
                                $('#verificationModal').modal('hide');
                            }
                        });
                        // Event listener for when the modal has finished being hidden
                        $('#verificationModal').on('hidden.bs.modal', function() {
                            // Reload the page
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    console.log('Error during verification:', error);
                    alert(`Failed to verify device ID ${deviceId}`);
                });
        }
    </script>
@endsection
