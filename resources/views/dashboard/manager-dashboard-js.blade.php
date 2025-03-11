<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('script')
    <script>
        $(document).ready(function() {
            // Show modals based on conditions
            @if ($showNewUserModel)
                $('#terms-conditions').modal('show');
            @endif

            @if ($firstPassword)
                setTimeout(function() {
                    $('#password-change-modal').modal('show');
                }, 500);
            @endif

            // Initialize DataTables
            $('.devices-ajax-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('devices-ajax-datatable') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'deviceName'
                    },
                    {
                        data: 'health'
                    },
                    {
                        data: 'deviceStatus'
                    },
                    {
                        data: 'ownedBy'
                    },
                    {
                        data: 'assignee'
                    },
                    {
                        data: 'location',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('.users-show-hierarchy-ajax-datatables').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users-show-hierarchy-ajax-datatables') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user'
                    },
                    {
                        data: 'devices',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Load devices and update greeting
            loadDevices();
            updateGreeting();
            setInterval(updateGreeting, 1000); // Update every minute

            // Form submissions
            $('#terms-and-conditions-form, #form-users-change-password').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        if (resp.code === 200) {
                            location.reload();
                        } else {
                            toastr.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong. Please try again.');
                    }
                });
            });
        });

        // Global functions
        let deviceMap = {};
        let currentDeviceId;

        function loadDevices() {
            $.ajax({
                url: '/customer/devices/data',
                type: 'GET',
                dataType: 'json',
                success: function(devices) {
                    let contentHtml = '',
                        topMessageHtml = '',
                        deviceNames = [];
                    deviceMap = {};

                    if (devices.length > 0) {
                        devices.forEach(device => {
                            deviceMap[device.id] = device;

                            if (['Not Responded', 'Reject'].includes(device.device_assigned.status)) {
                                deviceNames.push(device.name);
                            }

                            contentHtml += deviceCardTemplate(device);
                        });

                        if (deviceNames.length > 0) {
                            topMessageHtml = `
                            <div class="alert alert-warning alert-dismissible fade show">
                                <strong>Attention!</strong> Devices need authorization: ${deviceNames.join(', ')}.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>`;
                        }
                    } else {
                        contentHtml = `
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <p>You have no devices assigned yet.</p>
                                <a href="/devices/create" class="btn btn-primary">Add New Device</a>
                            </div>
                        </div>`;
                    }

                    $('#myDevices').html(contentHtml);
                    $('#top-message').html(topMessageHtml);
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
                error: function() {
                    $('#myDevices').html('<div class="alert alert-danger">Failed to load devices.</div>');
                }
            });
        }

        function deviceCardTemplate(device) {
            const isAuthorized = device.device_assigned.connection_status === 'Authorized';
            const cardClass = isAuthorized ? 'border-success' : 'border-danger';
            const statusBadgeClass = isAuthorized ? 'bg-success' : 'bg-danger';
            const statusText = isAuthorized ? 'Authorized' : 'Not Authorized';

            return `
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 ${cardClass}" style="cursor: pointer;" onclick="${isAuthorized ? `showData(${device.id})` : `showActivateDeviceModal(${device.id})`}">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-microchip fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title text-primary text-center">${device.name}</h5>
                        <div class="text-center mb-2">
                            <span class="badge ${statusBadgeClass}">${statusText}</span>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Mac :</strong> ${device.mac_address || 'N/A'}</li>
                            <li class="list-group-item"><strong>ApiKey :</strong> ${device.short_apikey || 'N/A'}</li>
                        </ul>
                    </div>
                </div>
            </div>`;
        }

        function updateGreeting() {
                const now = new Date();
                const hours = now.getHours();
                let greeting = "Good Evening";

                if (hours < 12) {
                    greeting = "Good Morning";
                } else if (hours < 18) {
                    greeting = "Good Afternoon";
                }

                document.getElementById('greeting-message').textContent =
                    `${greeting}, {{ Auth::user()->fname }} {{ Auth::user()->lname }} 🎉`;
                document.getElementById('current-time').textContent = now.toLocaleTimeString();
            }


        // Define missing functions
        function showData(deviceId) {
            console.log('Show data for device:', deviceId);
            // Implement data display logic
        }

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
                        let deviceName = entry.name;
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
                            maintainAspectRatio: false, // Ensures chart expands to div size
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
