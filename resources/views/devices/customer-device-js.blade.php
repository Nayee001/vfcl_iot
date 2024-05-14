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
            $.ajax({
                url: "{{ url('verify-device-model') }}" + "/" + deviceId,
                type: 'get',
                success: function(response) {
                    if (response) {
                        var contentHtml = '<p>' + response.message + '</p>';
                        contentHtml += '<ul>';
                        contentHtml += '<li>Device ID: ' + response.id + '</li>';
                        contentHtml += '<li>Device Name: ' + response.name + '</li>';
                        contentHtml += '<li>Status: ' + response.status + '</li>';
                        contentHtml += '<li>Location: ' + response.location + '</li>';
                        contentHtml += '</ul>';

                        $('#modalContent').html(contentHtml);
                        $('#verificationModal').modal('show');
                    }
                },
                error: function(error) {
                    console.log('Error during verification:', error);
                    alert('Failed to verify device ID ' + deviceId);
                }
            });
        }
    </script>
@endsection
