@section('script')
    <script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <script>
        function rmsValue(signal) {
            const squared = signal.map(x => x ** 2);
            const mean = squared.reduce((a, b) => a + b, 0) / squared.length;
            return Math.sqrt(mean);
        }

        function instantaneousPower(LV1, CT1, LV2, CT2, LV3, CT3) {
            return LV1.map((_, i) =>
                LV1[i] * CT1[i] + LV2[i] * CT2[i] + LV3[i] * CT3[i]
            );
        }

        function displayMetrics(LV1_rms, LV2_rms, LV3_rms, CT1_rms, CT2_rms, CT3_rms, Vabc_rms, Iabc_rms, S) {
            document.getElementById('total-power').textContent = `${S.toFixed(2)} W`;
            document.getElementById('voltage-rms').textContent = `${Vabc_rms.toFixed(2)} V`;
            document.getElementById('current-rms').textContent = `${Iabc_rms.toFixed(2)} A`;

            const P = S * Math.cos(Math.acos(Vabc_rms / S));
            const Q = Math.sqrt(S ** 2 - P ** 2);

            document.getElementById('active-power').textContent = `${P.toFixed(2)} W`;
            document.getElementById('reactive-power').textContent = `${Q.toFixed(2)} VAR`;
        }

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
                    type: 'linear'
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

        function fetchAndGenerateCharts(deviceId) {
            fetch(`/get-device-line-chart-data/${deviceId}`)
                .then(response => response.json())
                .then(newData => {
                    const rawData = newData.original.eventData.original.eventData.original.eventData || [];
                    console.log(rawData);
                    // ✅ Safely parse: handles both stringified and parsed objects
                    const eventData = rawData.map(e => typeof e === 'string' ? JSON.parse(e) : e);

                    const time = eventData.map((_, i) => i); // Use timestamp if available

                    const LV1 = eventData.map(e => e.LineVoltage1);
                    const LV2 = eventData.map(e => e.LineVoltage2);
                    const LV3 = eventData.map(e => e.LineVoltage3);
                    const CT1 = eventData.map(e => e.LineCurrentCT1);
                    const CT2 = eventData.map(e => e.LineCurrentCT2);
                    const CT3 = eventData.map(e => e.LineCurrentCT3);

                    const LV1_rms = rmsValue(LV1);
                    const LV2_rms = rmsValue(LV2);
                    const LV3_rms = rmsValue(LV3);
                    const CT1_rms = rmsValue(CT1);
                    const CT2_rms = rmsValue(CT2);
                    const CT3_rms = rmsValue(CT3);

                    const Vabc_rms = Math.sqrt((LV1_rms ** 2 + LV2_rms ** 2 + LV3_rms ** 2) / 3);
                    const Iabc_rms = Math.sqrt((CT1_rms ** 2 + CT2_rms ** 2 + CT3_rms ** 2) / 3);

                    const P_t = instantaneousPower(LV1, CT1, LV2, CT2, LV3, CT3);
                    const S = Vabc_rms * Iabc_rms;
                    const Q_t = P_t.map(p => Math.sqrt(Math.abs(S ** 2 - p ** 2)));

                    displayMetrics(LV1_rms, LV2_rms, LV3_rms, CT1_rms, CT2_rms, CT3_rms, Vabc_rms, Iabc_rms, S);

                    const voltageSeries = [{
                            name: 'LineVoltage1 (V)',
                            data: time.map((t, i) => [t, LV1[i]])
                        },
                        {
                            name: 'LineVoltage2 (V)',
                            data: time.map((t, i) => [t, LV2[i]])
                        },
                        {
                            name: 'LineVoltage3 (V)',
                            data: time.map((t, i) => [t, LV3[i]])
                        },
                    ];

                    const currentSeries = [{
                            name: 'LineCurrentCT1 (A)',
                            data: time.map((t, i) => [t, CT1[i]])
                        },
                        {
                            name: 'LineCurrentCT2 (A)',
                            data: time.map((t, i) => [t, CT2[i]])
                        },
                        {
                            name: 'LineCurrentCT3 (A)',
                            data: time.map((t, i) => [t, CT3[i]])
                        },
                    ];

                    const powerSeries = [{
                            name: 'P(t) (W)',
                            data: time.map((t, i) => [t, P_t[i]])
                        },
                        {
                            name: 'Q(t) (Var)',
                            data: time.map((t, i) => [t, Q_t[i]])
                        },
                    ];

                    createPlotlyChart('voltageChart', voltageSeries, 'Line Voltages', 'Voltage (V)');
                    createPlotlyChart('currentChart', currentSeries, 'CT Currents', 'Current (A)');
                    createPlotlyChart('powerChart', powerSeries, 'Power Waveforms', 'Power (W / Var)');
                })
                .catch(error => console.error("Error fetching chart data:", error));
        }

        document.addEventListener("DOMContentLoaded", function() {
            const deviceId = {{ $device_id }}; // Blade or inline script inject
            fetchAndGenerateCharts(deviceId);
        });
    </script>



    {{-- <script>
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

    // Function to display metrics in the metric boxes
    function displayMetrics(VA_rms, VB_rms, VC_rms, IA_rms, IB_rms, IC_rms, Vabc_rms, Iabc_rms, S) {
        document.getElementById('total-power').textContent = `${S.toFixed(2)} W`;
        document.getElementById('voltage-rms').textContent = `${Vabc_rms.toFixed(2)} V`;
        document.getElementById('current-rms').textContent = `${Iabc_rms.toFixed(2)} A`;

        // Example calculations for active power (P) and reactive power (Q)
        const P = S * Math.cos(Math.acos(Vabc_rms / S));  // Active power (example calculation)
        const Q = Math.sqrt(S ** 2 - P ** 2);             // Reactive power (example calculation)

        document.getElementById('active-power').textContent = `${P.toFixed(2)} W`;
        document.getElementById('reactive-power').textContent = `${Q.toFixed(2)} VAR`;
    }

    // Function to create a Plotly chart with smooth curves
    function createPlotlyChart(containerId, seriesData, title, yTitle) {
        const data = seriesData.map(series => ({
            x: series.data.map(point => point[0]),
            y: series.data.map(point => point[1]),
            mode: 'lines',
            name: series.name,
            line: { shape: 'spline', smoothing: 1.3 },
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
</script> --}}
    {{-- <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const broker = "wss://broker.hivemq.com:8884/mqtt";
    const topic = "mqttdevice/6bfa9c/json_payload";

    const client = mqtt.connect(broker, {
        clientId: "WebCommandCenter"
    });

    let timeLabels = [], voltage1 = [], current1 = [];

    Plotly.newPlot("voltageChart", [{
        x: [], y: [], name: "LineVoltage1", mode: "lines", line: { shape: "spline" }
    }], { title: "Live Voltage", xaxis: { title: "Time" }, yaxis: { title: "Voltage (V)" } });

    Plotly.newPlot("currentChart", [{
        x: [], y: [], name: "LineCurrentCT1", mode: "lines", line: { shape: "spline" }
    }], { title: "Live Current", xaxis: { title: "Time" }, yaxis: { title: "Current (A)" } });

    client.on("connect", () => {
        console.log("Connected to HiveMQ");
        client.subscribe(topic);
    });

    client.on("message", (t, message) => {
        try {
            const payload = JSON.parse(message.toString());
            const event = JSON.parse(payload.event_data);
            const now = new Date().toLocaleTimeString();

            // Shift if more than 50
            if (timeLabels.length >= 50) {
                timeLabels.shift(); voltage1.shift(); current1.shift();
            }

            timeLabels.push(now);
            voltage1.push(event.LineVoltage1);
            current1.push(event.LineCurrentCT1);

            Plotly.update("voltageChart", { x: [timeLabels], y: [voltage1] });
            Plotly.update("currentChart", { x: [timeLabels], y: [current1] });
        } catch (err) {
            console.error("Bad MQTT message", err);
        }
    });
});
</script> --}}
    {{-- <script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script> --}}

    {{-- <script> --}}
    {{-- document.addEventListener("DOMContentLoaded", function () {
        const broker = "wss://broker.hivemq.com:8884/mqtt";
        const topic = "mqttdevice/6bfa9c/json_payload";

        const client = mqtt.connect(broker, {
            clientId: "WebCommandCenter" + Math.random().toString(16).substr(2, 8)
        });

        const timeLabels = [];
        const voltage1 = [], voltage2 = [], voltage3 = [];
        const current1 = [], current2 = [], current3 = [];

        Plotly.newPlot("voltageChart", [
            { x: [], y: [], name: "LineVoltage1", mode: "lines", line: { shape: "spline" } },
            { x: [], y: [], name: "LineVoltage2", mode: "lines", line: { shape: "spline" } },
            { x: [], y: [], name: "LineVoltage3", mode: "lines", line: { shape: "spline" } }
        ], {
            title: "Live Voltage (3-Phase)",
            xaxis: { title: "Time" },
            yaxis: { title: "Voltage (V)" }
        });

        Plotly.newPlot("currentChart", [
            { x: [], y: [], name: "LineCurrentCT1", mode: "lines", line: { shape: "spline" } },
            { x: [], y: [], name: "LineCurrentCT2", mode: "lines", line: { shape: "spline" } },
            { x: [], y: [], name: "LineCurrentCT3", mode: "lines", line: { shape: "spline" } }
        ], {
            title: "Live Current (3-Phase)",
            xaxis: { title: "Time" },
            yaxis: { title: "Current (A)" }
        });

        client.on("connect", () => {
            console.log("✅ Connected to HiveMQ");
            client.subscribe(topic, (err) => {
                if (err) console.error("Subscription error", err);
            });
        });

        client.on("message", (t, msg) => {
            try {
                const payload = JSON.parse(msg.toString());
                const event = JSON.parse(payload.event_data);
                const now = new Date().toLocaleTimeString();

                if (timeLabels.length >= 50) {
                    timeLabels.shift();
                    voltage1.shift(); voltage2.shift(); voltage3.shift();
                    current1.shift(); current2.shift(); current3.shift();
                }

                timeLabels.push(now);
                voltage1.push(event.LineVoltage1);
                voltage2.push(event.LineVoltage2);
                voltage3.push(event.LineVoltage3);
                current1.push(event.LineCurrentCT1);
                current2.push(event.LineCurrentCT2);
                current3.push(event.LineCurrentCT3);

                Plotly.update("voltageChart", {
                    x: [timeLabels, timeLabels, timeLabels],
                    y: [voltage1, voltage2, voltage3]
                });

                Plotly.update("currentChart", {
                    x: [timeLabels, timeLabels, timeLabels],
                    y: [current1, current2, current3]
                });

            } catch (e) {
                console.error("Error parsing MQTT message", e);
            }
        });
    });
    </script> --}}
@endsection
