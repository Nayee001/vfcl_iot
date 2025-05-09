@section('script')
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
                            let timerInterval
                            Swal.fire({
                                title: resp.Message,
                                html: 'Please Wait for a moment, while we setup your IOT platform,<br> please do not refresh the page...',
                                timer: 10000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                    const b = Swal.getHtmlContainer().querySelector(
                                        'b')
                                    timerInterval = setInterval(() => {
                                        b.textContent = Swal.getTimerLeft()
                                    }, 100)
                                },
                                willClose: () => {
                                    clearInterval(timerInterval)
                                }
                            })
                            setTimeout(function() {
                                location.reload();
                            }, 10000);
                        } else {
                            toastr.error(resp.Message);
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

        const chartOrderStatistics = document.querySelector('#deviceStatChart');
        let deviceData = @json($deviceTypesWithDeviceCount->toArray());
        const cardColor = '#ffffff'; // Default card color
        const axisColor = '#6c757d'; // Axis text color

        const orderChartConfig = {
            chart: {
                height: 165,
                width: 130,
                type: 'donut'
            },
            labels: Object.keys(deviceData),
            series: Object.values(deviceData),
            colors: ['#7367F0', '#FF9F43', '#00CFE8', '#28C76F'],
            stroke: {
                width: 5,
                colors: cardColor
            },
            dataLabels: {
                enabled: false,
                formatter: function(val, opt) {
                    return parseInt(val) + '%';
                }
            },
            legend: {
                show: false
            },
            grid: {
                padding: {
                    top: 0,
                    bottom: 0,
                    right: 15
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                fontSize: '0.8125rem',
                                color: axisColor,
                                label: 'Devices',
                                formatter: function() {
                                    return '{{ $deviceTypesWithDeviceCount->sum() }}';
                                }
                            }
                        }
                    }
                }
            }
        };

        if (chartOrderStatistics !== null) {
            const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
            statisticsChart.render();
        }
    </script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

    <script>
        // ✅ Function to create Plotly chart with smooth curves
        function createPlotlyChart(containerId, seriesData, title, yTitle) {
            const data = seriesData.map(series => ({
                x: series.data.map(point => point[0]),
                y: series.data.map(point => point[1]),
                mode: 'lines',
                name: series.name,
                line: {
                    shape: 'spline',
                    smoothing: 1.3
                },
            }));

            const layout = {
                title: {
                    text: title,
                    font: {
                        size: 18
                    }
                },
                xaxis: {
                    title: 'Time',
                    type: 'date'
                },
                yaxis: {
                    title: yTitle
                },
                margin: {
                    t: 50,
                    r: 30,
                    b: 50,
                    l: 70
                },
                showlegend: true
            };

            Plotly.newPlot(containerId, data, layout);
        }

        // ✅ Fetch event data and generate Plotly charts
        function fetchAndGenerateCharts(deviceId) {
            fetch(`/get-device-line-chart-data/${deviceId}`)
                .then(response => response.json())
                .then(newData => {
                    const eventData = newData.original.eventData || [];
                    if (eventData.length === 0) {
                        console.error("No event data available for the selected device");
                        return;
                    }

                    const time = eventData.map(entry => new Date(entry['Time (seconds)'] * 1000).toISOString());
                    const IA = eventData.map(entry => entry['IA(A)']);
                    const IB = eventData.map(entry => entry['IB(A)']);
                    const IC = eventData.map(entry => entry['IC(A)']);

                    // Prepare current waveform series data
                    const currentSeries = [{
                            name: 'IA (A)',
                            data: time.map((t, i) => [t, IA[i]])
                        },
                        {
                            name: 'IB (A)',
                            data: time.map((t, i) => [t, IB[i]])
                        },
                        {
                            name: 'IC (A)',
                            data: time.map((t, i) => [t, IC[i]])
                        },
                    ];

                    // ✅ Render Current Chart in Dashboard (inside "currentChart" div)
                    createPlotlyChart('currentChart', currentSeries, 'Current Waveforms', 'Current (A)');
                })
                .catch(error => console.error("Error fetching chart data:", error));
        }

        // ✅ Initialize charts on page load
        document.addEventListener("DOMContentLoaded", function() {
            const deviceId = 1; // Ensure the device ID is correctly passed
            fetchAndGenerateCharts(deviceId);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ✅ Fetch Current Time & Determine Greeting
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

            // ✅ Fetch Dashboard Stats & Update UI
            function fetchDashboardStats() {
                fetch('/get-dashboard-stats')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('active-devices').textContent = data.activeDevices || 0;
                        document.getElementById('faulty-devices').textContent = data.faultyDevices || 0;
                        document.getElementById('critical-alerts').textContent = data.criticalAlerts || 0;
                    })
                    .catch(error => console.error("Error fetching dashboard stats:", error));
            }

            // ✅ Run Functions
            updateGreeting();
            fetchDashboardStats();

            // ✅ Refresh Greeting Every 1 Minute
            setInterval(updateGreeting, 1000);
        });
    </script>
@endsection
