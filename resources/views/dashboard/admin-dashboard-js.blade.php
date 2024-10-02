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
        // public/js/dashboard.js

        document.addEventListener('DOMContentLoaded', function() {
            // Simulate 3-phase wave data for 1 minute
            var ctx = document.getElementById('waveChart').getContext('2d');

            var timeLabels = [];
            var phaseAData = [];
            var phaseBData = [];
            var phaseCData = [];
            var totalPoints = 60; // 1 minute with data points every second

            // Generate initial data
            for (var i = 0; i < totalPoints; i++) {
                timeLabels.push(i);
                phaseAData.push(Math.sin(2 * Math.PI * (i / totalPoints)));
                phaseBData.push(Math.sin(2 * Math.PI * (i / totalPoints) + (2 * Math.PI / 3)));
                phaseCData.push(Math.sin(2 * Math.PI * (i / totalPoints) + (4 * Math.PI / 3)));
            }

            var waveChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: timeLabels,
                    datasets: [{
                            label: 'Phase A',
                            data: phaseAData,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            fill: false,
                        },
                        {
                            label: 'Phase B',
                            data: phaseBData,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            fill: false,
                        },
                        {
                            label: 'Phase C',
                            data: phaseCData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            fill: false,
                        },
                    ],
                },
                options: {
                    animation: false,
                    scales: {
                        x: {
                            type: 'linear',
                            position: 'bottom',
                            title: {
                                display: true,
                                text: 'Time (s)',
                            },
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Amplitude',
                            },
                        },
                    },
                },
            });

            // Update the chart every second
            var currentIndex = totalPoints;
            setInterval(function() {
                // Shift the labels
                waveChart.data.labels.push(currentIndex);
                waveChart.data.labels.shift();

                // Generate new data points
                var newPhaseA = Math.sin(2 * Math.PI * (currentIndex / totalPoints));
                var newPhaseB = Math.sin(2 * Math.PI * (currentIndex / totalPoints) + (2 * Math.PI / 3));
                var newPhaseC = Math.sin(2 * Math.PI * (currentIndex / totalPoints) + (4 * Math.PI / 3));

                // Update datasets
                waveChart.data.datasets[0].data.push(newPhaseA);
                waveChart.data.datasets[0].data.shift();

                waveChart.data.datasets[1].data.push(newPhaseB);
                waveChart.data.datasets[1].data.shift();

                waveChart.data.datasets[2].data.push(newPhaseC);
                waveChart.data.datasets[2].data.shift();

                waveChart.update();

                currentIndex++;
            }, 1000);
        });
    </script>
@endsection
