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
    </script>
@endsection
