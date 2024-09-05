@section('script')
    @if ($showNewUserModel)
        <script>
            $(document).ready(function() {
                $('#terms-conditions').modal('show');
            });
        </script>
    @endif

    @if ($showPasswordChangeModal)
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
                        sessionStorage.setItem('showDeviceGuide',
                            'true'); // Set a flag to show the guide
                        window.location.href = '/devices'; // Redirect to device management
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

        $(function() {
            var table = $('.dashboard-devices-ajax-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get-dashboard-devices-ajax-datatable') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'deviceName',
                        name: 'deviceName'
                    },
                    {
                        data: 'deviceStatus',
                        data: 'deviceStatus'
                    },
                    {
                        data: 'healthStatus',
                        name: 'healthStatus'
                    },
                    {
                        data: 'faultStatus',
                        data: 'faultStatus'
                    },
                    {
                        data: 'TimeStamps',
                        data: 'TimeStamps'
                    },
                    {
                        data: 'actions',
                        data: 'actions'
                    },
                ]
            });
        });

        // $(document).ready(function() {
        //     // Function to check device status
        //     function checkDeviceStatus() {
        //         const authUserId = '{{ auth()->user()->id }}'; // Get the user ID dynamically
        //         $.ajax({
        //             url: `/customer/assigned/devices/data/${authUserId}`,
        //             type: 'GET',
        //             dataType: 'json',
        //             success: function(devices) {
        //                 devices.forEach(device => {
        //                     if (device.device_assigned && device.device_assigned
        //                         .login_to_device && device.device_assigned.status !== 'Accept'
        //                     ) {
        //                         Swal.fire({
        //                             title: 'Authorization Required!',
        //                             text: `Device ${device.name} has successfully logged in but requires Authorization.`,
        //                             icon: 'warning',
        //                             showCancelButton: true,
        //                             confirmButtonText: 'Go to Device',
        //                             cancelButtonText: 'Dismiss'
        //                         }).then((result) => {
        //                             if (result.isConfirmed) {
        //                                 // Redirect to the device session page
        //                                 window.location.href = `/devices`;
        //                             }
        //                         });
        //                     }
        //                 });
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error("An error occurred: ", error);
        //             }
        //         });
        //     }
        //     // Check device status on page load
        //     checkDeviceStatus();
        //     // Optionally, you can set an interval to check the device status periodically
        //     setInterval(checkDeviceStatus, 6000); // Check every 60 seconds
        // });

        document.getElementById('deviceStep2').addEventListener('click', function() {
            fetch('/deviceStep2')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        document.getElementById('modalToggle2Label').innerText = "Error";
                        document.getElementById('secondModalContent').innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        ${data.error}
                    </div>
                `;
                    } else {
                        document.getElementById('modalToggle2Label').innerText = data.title;
                        let content = '';
                        data.devices.forEach(device => {
                            content += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">${device.device_name}</h5>
                                <p class="card-text highlighted yellow animating">MAC Address: ${device.mac_address}</p>
                                <p class="card-text highlighted yellow animating">API Key: ${device.api_key}</strong></p>
                            </div>
                        </div>
                    `;
                        });
                        document.getElementById('secondModalContent').innerHTML = content;
                    }
                    setTimeout(function() {
                        var animating = document.querySelectorAll('.highlighted.animating');
                        for (var i = 0; i < animating.length; i++) {
                            animating[i].classList.remove('animating');
                        }
                    }, 2000);
                    var secondModal = new bootstrap.Modal(document.getElementById('modalToggle2'));
                    secondModal.show();
                })
                .catch(error => {
                    document.getElementById('modalToggle2Label').innerText = "Error";
                    document.getElementById('secondModalContent').innerHTML = `
                <div class="alert alert-danger" role="alert">
                    No Device Found
                </div>
            `;
                    var secondModal = new bootstrap.Modal(document.getElementById('modalToggle2'));
                    secondModal.show();
                    console.error('There was a problem with the fetch operation:', error);
                });
        });


        document.getElementById('prevModal').addEventListener('click', function() {
            var firstModal = new bootstrap.Modal(document.getElementById('modalToggle'));
            firstModal.show();
        });

        document.getElementById('deviceStep3').addEventListener('click', function() {
            var thirdModal = new bootstrap.Modal(document.getElementById('modalToggle3'));
            thirdModal.show();
        });

        document.getElementById('prevModal2').addEventListener('click', function() {
            var secondModal = new bootstrap.Modal(document.getElementById('modalToggle2'));
            secondModal.show();
        });

        document.getElementById('deviceStep4').addEventListener('click', function() {
            var fourthModal = new bootstrap.Modal(document.getElementById('modalToggle4'));
            fourthModal.show();
        });

        document.getElementById('prevModal3').addEventListener('click', function() {
            var thirdModal = new bootstrap.Modal(document.getElementById('modalToggle3'));
            thirdModal.show();
        });


        $(document).ready(function() {
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
                                      <b style="color: red;"> ${deviceNames.join(', ')} ; need to be accepted from the command center</b>
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
            // Logic to handle device activation steps
            // alert("Activating device with ID:", deviceId);
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
    </script>
@endsection
