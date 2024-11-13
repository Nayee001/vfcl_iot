@section('script')
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

    <script>
        // Helper function to calculate RMS values
        function rmsValue(signal) {
            const squared = signal.map(x => x ** 2);
            const mean = squared.reduce((a, b) => a + b, 0) / squared.length;
            return Math.sqrt(mean);
        }

        // Function to calculate instantaneous power P(t)
        function instantaneousPower(VA, IA, VB, IB, VC, IC) {
            return VA.map((v, i) => v * IA[i] + VB[i] * IB[i] + VC[i] * IC[i]);
        }

        // Function to display metrics
        function displayMetrics(VA_rms, VB_rms, VC_rms, IA_rms, IB_rms, IC_rms, Vabc_rms, Iabc_rms, S) {
            document.getElementById('metrics').innerHTML = `
                <b>Calculated Metrics:</b><br>
                RMS Voltages: VA = ${VA_rms.toFixed(2)} V, VB = ${VB_rms.toFixed(2)} V, VC = ${VC_rms.toFixed(2)} V<br>
                RMS Currents: IA = ${IA_rms.toFixed(2)} A, IB = ${IB_rms.toFixed(2)} A, IC = ${IC_rms.toFixed(2)} A<br>
                Three-phase RMS Voltage: Vabc_rms = ${Vabc_rms.toFixed(2)} V<br>
                Three-phase RMS Current: Iabc_rms = ${Iabc_rms.toFixed(2)} A<br>
                Apparent Power (S): ${S.toFixed(2)} VA
            `;
        }

        // Function to create a Plotly chart with smooth curves
        function createPlotlyChart(containerId, seriesData, title, yTitle) {
            const data = seriesData.map(series => ({
                x: series.data.map(point => point[0]),
                y: series.data.map(point => point[1]),
                mode: 'lines',
                name: series.name,
                line: { shape: 'spline', smoothing: 1.3 }, // Smooth curve with spline
            }));

            const layout = {
                title: { text: title, font: { size: 18 } },
                xaxis: { title: 'Time', type: 'date' },
                yaxis: { title: yTitle },
                margin: { t: 50, r: 30, b: 50, l: 70 },
                showlegend: true
            };

            Plotly.newPlot(containerId, data, layout);
        }

        // Fetch event data and generate Plotly charts
        function fetchAndGenerateCharts(deviceId) {
            fetch(`/get-device-line-chart-data/${deviceId}`)
                .then(response => response.json())
                .then(newData => {
                    const eventData = newData.original.eventData || [];
                    console.log(eventData);
                    if (eventData.length === 0) {
                        console.error("No event data available for the selected device");
                        return;
                    }

                    const time = eventData.map(entry => new Date(entry['Time (seconds)'] * 1000).toISOString());
                    const VA = eventData.map(entry => entry['VAB(V)']);
                    const VB = eventData.map(entry => entry['VBC(V)']);
                    const VC = eventData.map(entry => entry['VCA(V)']);
                    const IA = eventData.map(entry => entry['IA(A)']);
                    const IB = eventData.map(entry => entry['IB(A)']);
                    const IC = eventData.map(entry => entry['IC(A)']);

                    // Calculate RMS and Power values
                    const VA_rms = rmsValue(VA);
                    const VB_rms = rmsValue(VB);
                    const VC_rms = rmsValue(VC);
                    const IA_rms = rmsValue(IA);
                    const IB_rms = rmsValue(IB);
                    const IC_rms = rmsValue(IC);

                    const Vabc_rms = Math.sqrt((VA_rms ** 2 + VB_rms ** 2 + VC_rms ** 2) / 3);
                    const Iabc_rms = Math.sqrt((IA_rms ** 2 + IB_rms ** 2 + IC_rms ** 2) / 3);

                    const P_t = instantaneousPower(VA, IA, VB, IB, VC, IC);
                    const S = Vabc_rms * Iabc_rms;
                    const Q_t = P_t.map(p => Math.sqrt(Math.abs(S ** 2 - p ** 2)));

                    displayMetrics(VA_rms, VB_rms, VC_rms, IA_rms, IB_rms, IC_rms, Vabc_rms, Iabc_rms, S);

                    // Prepare series data for the charts
                    const currentSeries = [
                        { name: 'IA (A)', data: time.map((t, i) => [t, IA[i]]) },
                        { name: 'IB (A)', data: time.map((t, i) => [t, IB[i]]) },
                        { name: 'IC (A)', data: time.map((t, i) => [t, IC[i]]) },
                    ];
                    const voltageSeries = [
                        { name: 'VAB (V)', data: time.map((t, i) => [t, VA[i]]) },
                        { name: 'VBC (V)', data: time.map((t, i) => [t, VB[i]]) },
                        { name: 'VCA (V)', data: time.map((t, i) => [t, VC[i]]) },
                    ];
                    const powerSeries = [
                        { name: 'P(t) (W)', data: time.map((t, i) => [t, P_t[i]]) },
                        { name: 'Q(t) (Var)', data: time.map((t, i) => [t, Q_t[i]]) },
                    ];

                    // Create the Plotly charts with smooth curves
                    createPlotlyChart('currentChart', currentSeries, 'Current Waveforms', 'Current (A)');
                    createPlotlyChart('voltageChart', voltageSeries, 'Voltage Waveforms', 'Voltage (V)');
                    createPlotlyChart('powerChart', powerSeries, 'Power Waveforms', 'Power (W / Var)');
                })
                .catch(error => console.error("Error fetching chart data:", error));
        }

        // Initialize charts on page load
        document.addEventListener("DOMContentLoaded", function () {
            const deviceId = {{ $device_id }}; // Replace with actual device ID
            fetchAndGenerateCharts(deviceId);
        });
    </script>
@endsection
