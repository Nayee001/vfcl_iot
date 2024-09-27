@section('script')
    <script>
        // Function to format timestamp (helper function)
        function formatTimestamp(timestamp) {
            const date = new Date(timestamp * 1000); // Convert to milliseconds
            return date.toLocaleString();
        }

        // Function to update the UI with real-time fault detection data
        function updateUIWithDeviceData(deviceData) {
            const faultStatusClass =
                deviceData.fault_status === "ON" ? "text-danger" : "text-success";
            const deviceStatusClass =
                deviceData.device.status === "Active" ? "text-danger" : "text-success";

            const imageSrc =
                deviceData.fault_status === "ON" ?
                "assets/img/illustrations/red.png" :
                "assets/img/illustrations/green.png";

            const html = `
        <div class="text-center fw-semibold pt-3 mb-2">
            <img class="fault-img mb-3" src="${imageSrc}" alt="Device image" style="margin: auto;">
            <div>
                <span class="fault ${faultStatusClass}">${deviceData.fault_status}</span><br>
                <span>${deviceData.device.name}</span><br>
                <span class="fault ${deviceStatusClass}">${deviceData.device.status}</span>
            </div>
        </div>
        <div class="d-flex justify-content-center px-xxl-4 px-lg-2 p-4">
            <div class="d-flex gap-xxl-3 gap-lg-1 gap-3">
                <div class="d-flex align-items-center me-4">
                    <span class="badge bg-label-primary p-2 me-2"><i class='bx bxs-heart'></i></span>
                    <div>
                        <small>Health Status</small>
                        <h6 class="mb-0"><span class="fw-medium ${faultStatusClass}">${deviceData.health_status}</span></h6>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-label-info p-2 me-2"><i class='bx bx-time'></i></span>
                    <div>
                        <small>Last Sync</small>
                        <h6 class="mb-0">${formatTimestamp(deviceData.timestamp)}</h6>
                    </div>
                </div>
            </div>
        </div>`;

            document.getElementById("device-fault-status-shown").innerHTML = html;
        }

        // Function to initialize the line chart
        function initializeChart() {
            var options = {
                chart: {
                    type: "line",
                    height: 350,
                    animations: {
                        enabled: true,
                        easing: "linear",
                        dynamicAnimation: {
                            speed: 1000
                        },
                    },
                    stroke: {
                        curve: "smooth"
                    },
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                },
                series: [{
                        name: "Phase 1",
                        data: []
                    },
                    {
                        name: "Phase 2",
                        data: []
                    },
                    {
                        name: "Phase 3",
                        data: []
                    },
                ],
                xaxis: {
                    type: "datetime",
                    range: 600000, // Show 10 minutes of data
                },
                yaxis: {
                    title: {
                        text: "Current (Amps)"
                    },
                },
            };

            chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }

        // Function to fetch line chart data and update the chart
        function fetchChartDataAndUpdateChart(deviceId) {
            fetch(`/get-device-line-chart-data/${deviceId}`)
                .then(response => response.json())
                .then(newData => {
                    if (!newData.data || newData.data.length === 0) {
                        console.error("No data available for the selected device");
                        return;
                    }

                    const phase1Data = [];
                    const phase2Data = [];
                    const phase3Data = [];

                    newData.data.forEach(item => {
                        const timestamp = new Date(item.x * 1000).getTime(); // Convert seconds to milliseconds
                        phase1Data.push({
                            x: timestamp,
                            y: item.current_phase1
                        });
                        phase2Data.push({
                            x: timestamp,
                            y: item.current_phase2
                        });
                        phase3Data.push({
                            x: timestamp,
                            y: item.current_phase3
                        });
                    });

                    chart.updateSeries([{
                            name: "Phase 1",
                            data: phase1Data
                        },
                        {
                            name: "Phase 2",
                            data: phase2Data
                        },
                        {
                            name: "Phase 3",
                            data: phase3Data
                        },
                    ]);
                })
                .catch(error => console.error("Error fetching chart data:", error));
        }

        // Function to fetch fault detection data
        function fetchDeviceDataAndUpdateUI(deviceId) {
            fetch(`/device-data/${deviceId}`)
                .then(response => response.json())
                .then(deviceData => {
                    updateUIWithDeviceData(deviceData);
                })
                .catch(error => console.error("Error fetching device data:", error));
        }

        // Real-time update function using setInterval
        function startRealTimeUpdates(deviceId) {
            // Fetch data every 10 seconds for real-time updates
            setInterval(() => {
                fetchDeviceDataAndUpdateUI(deviceId);
                fetchChartDataAndUpdateChart(deviceId);
            }, 10000); // 10 seconds
        }

        function fetchDeviceData(deviceId) {
            const apiUrl = `/devices/${deviceId}data`;

            fetch(apiUrl)
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    // Update device details as before
                    document.getElementById('deviceName').textContent = data.name;
                    document.getElementById('deviceHealth').textContent = data.health;
                    document.getElementById('mqttStatus').textContent = data.device_assigned.connection_status;
                    document.getElementById('encryptionStatus').textContent =
                        `Encryption: ${data.device_assigned.encryption_key ? "True" : "False"}`;
                    document.getElementById('lastSync').textContent =
                        `Last Sync: ${data.device_assigned.last_sync || 'No sync data'}`;
                    document.getElementById('dataCommunication').textContent =
                        `Data Comm: ${data.device_assigned.send_mac ? 'Active' : 'Inactive'}`;

                    // Update location details
                    const location = data.device_assigned.device_location;
                    const assigneeLocation = data.device_assigned.assignee.locations;

                    // Set location name
                    document.getElementById('locationName').textContent =
                        `🏠 ${location.location_name || 'Unknown Location'}`;

                    // Set location address
                    document.getElementById('locationAddress').textContent =
                        `${assigneeLocation.address}, ${assigneeLocation.city}, ${assigneeLocation.state}, ${assigneeLocation.country}` ||
                        'No address available';
                })
                .catch(error => {
                    console.error('Error fetching device data:', error);
                });
        }
        // Call the function to fetch and render the device data
        window.onload = function() {
            const deviceId = {{ $device_id }}; // Example device ID
            fetchDeviceData(deviceId);
            initializeChart();
            startRealTimeUpdates(deviceId);
        };
    </script>
@endsection
