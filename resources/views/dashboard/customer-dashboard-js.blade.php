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

        $(document).ready(function() {
            // Function to check device status
            function checkDeviceStatus() {
                const authUserId = '{{ auth()->user()->id }}'; // Get the user ID dynamically
                $.ajax({
                    url: `/customer/assigned/devices/data/${authUserId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(devices) {
                        devices.forEach(device => {
                            if (device.device_assigned && device.device_assigned
                                .login_to_device && device.device_assigned.status !== 'Accept'
                            ) {
                                Swal.fire({
                                    title: 'Action Required!',
                                    text: `Device ${device.name} is logged in but not yet accepted.`,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Go to Device',
                                    cancelButtonText: 'Dismiss'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redirect to the device session page
                                        window.location.href = `/devices`;
                                    }
                                });
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: ", error);
                    }
                });
            }
            // Check device status on page load
            checkDeviceStatus();
            // Optionally, you can set an interval to check the device status periodically
            setInterval(checkDeviceStatus, 6000); // Check every 60 seconds
        });

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
                                <p class="card-text">MAC Address: ${device.mac_address}</p>
                                <p class="card-text">API Key: <strong>${device.api_key}</strong></p>
                            </div>
                        </div>
                    `;
                        });
                        document.getElementById('secondModalContent').innerHTML = content;
                    }
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
    </script>
@endsection
