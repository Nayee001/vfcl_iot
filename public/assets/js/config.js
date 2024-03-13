/**
 * Config
 * -------------------------------------------------------------------------------------
 * ! IMPORTANT: Make sure you clear the browser local storage In order to see the config changes in the template.
 * ! To clear local storage: (https://www.leadshook.com/help/how-to-clear-local-storage-in-google-chrome-browser/).
 */

"use strict";

// JS global variables
let config = {
    colors: {
        primary: "#696cff",
        secondary: "#8592a3",
        success: "#71dd37",
        info: "#03c3ec",
        warning: "#ffab00",
        danger: "#ff3e1d",
        dark: "#233446",
        black: "#000",
        white: "#fff",
        body: "#f4f5fb",
        headingColor: "#566a7f",
        axisColor: "#a1acb8",
        borderColor: "#eceef1",
    },
};

function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}
// Getting Count
function getDeviceDataCount() {
    fetch("/device-data/count")
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("dataCount").innerText = data.count;
        })
        .catch((error) => console.error("Error:", error));
}
setInterval(getDeviceDataCount, 100);

// Getting Data
function fetchAndUpdateData() {
    fetch("/device-data/messages")
        .then((response) => response.json())
        .then((data) => {
            let html = "";
            data.forEach((record) => {
                html += `
                    <div class="d-flex align-items-center border-top py-3">
                        <span class="text-success me-2">
                            <i class="mdi mdi-circle"></i>
                        </span>
                        <h6 class="fw-normal mb-0">
                            <a href="#" onclick="showData('${
                                record.device ? record.device.id : ""
                            }')">
                                ${
                                    record.device
                                        ? record.device.name
                                        : "Device Name"
                                }
                            </a>
                        </h6>
                    <div class="flex-grow-1"></div>
                    <h6 class="text-end me-4 mb-0 ${
                        record.fault_status == "ON" ? "green" : "red"
                    }">${record.fault_status}</h6>
                    <h6 class="text-end me-2 mb-0">${record.device_status}</h6>
                    </div>`;
            });
            document.getElementById("deviceDataContainer").innerHTML = html;
        })
        .catch((error) => console.error("Error:", error));
}

// Fetch and update data every 1000 milliseconds
setInterval(fetchAndUpdateData, 2000);

function showData(deviceId) {
    if (deviceId) {
        fetch(`/device-data/${deviceId}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((deviceData) => {
                // console.log(deviceData);
                let statusClass =
                    deviceData.fault_status === "ON" ? "red" : "green";
                let imageSrc =
                    deviceData.fault_status === "ON"
                        ? "assets/img/illustrations/red.png"
                        : "assets/img/illustrations/green.png";

                let html = `
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center" style="position: relative;">
                        <img class="fault-img" src="${imageSrc}" alt="Device image">
                            <span class="fault ${statusClass}">${
                    deviceData.fault_status
                }</span>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <div class="avatar">
                                <div class="avatar-initial ${
                                    deviceData.randomBackgroundColor
                                } rounded shadow">
                                <a href="#" class="white" onclick="showLineChart(${deviceId})"><i class='bx bx-line-chart'></i></a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar">
                                    <div class="avatar-initial ${
                                        deviceData.randomBackgroundColor
                                    } rounded shadow">
                                        <i class='bx bx-devices'></i>
                                    </div>
                                </div>
                                <div class="ms-3 d-flex flex-column">
                                    <p class="mb-1" style="font-size: 0.9em; color: #555;">${
                                        deviceData.device.name
                                    }</p>
                                    <h5 class="mb-1" style="font-weight: bold; ">${
                                        deviceData.device.status
                                    }</h5>
                                </div>
                            </div>
                            <hr>
                            <div class="row g-4">
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge badge-dot bg-primary me-2"></div>
                                        <p class="text-heading mb-0" style="font-weight: bold;">Health Status</p>
                                    </div>
                                    <p class="fw-medium mb-0  ${statusClass}">${
                    deviceData.health_status
                }</p>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge badge-dot bg-primary me-2"></div>
                                        <p class="text-heading mb-0" style="font-weight: bold;">Time Stamps</p>
                                    </div>
                                    <p class="fw-medium mb-0">${formatTimestamp(
                                        deviceData.timestamp
                                    )}</p>
                                    </div>
                            </div>
                        </div>
                    </div>`;

                document.getElementById("device-fault-status-shown").innerHTML =
                    html;
            })
            .catch((error) => {
                console.error("Error:", error);
                document.getElementById("device-fault-status-shown").innerHTML =
                    "Error loading device data";
            });
    } else {
        document.getElementById("device-fault-status-shown").innerHTML =
            "Error No device ID provided";
    }
}

function showLineChart(deviceId) {
    var faultStatus = document.getElementById("device-fault-status-shown");
    faultStatus.style.display = "none";
    var lineChart = document.getElementById("device-fault-line-chart");
    lineChart.style.display = "block";
    fetchChartDataAndUpdateChart(deviceId);
}
// setInterval(() => showData(1), 2000);
// Refresh the data every 2 seconds

document.addEventListener("DOMContentLoaded", function () {
    var lastDate = 0;
    var data = [];
    var XAXISRANGE = 10 * 60000; // Adjust this based on your needs

    function getNewSeries(baseDate, { min, max }) {
        var newDate = baseDate + 1000;
        lastDate = newDate;

        for (var i = 0; i < data.length - 10; i++) {
            // This is just to keep the array from becoming infinitely long, adjust as needed
            data[i].x = new Date(newDate - XAXISRANGE - 1000);
            data[i].y = 0;
        }

        data.push({
            x: new Date(newDate),
            y: Math.floor(Math.random() * (max - min + 1)) + min,
        });
    }

    function fetchChartDataAndUpdateChart(deviceId) {
        fetch(`/get-device-line-chart-data/${deviceId}`) // Adjust the URL to match your route
            .then((response) => response.json())
            .then((newData) => {
                data = newData.data.map((item) => ({
                    x: new Date(item.x),
                    y: item.y,
                }));
                // deviceName = newData.deviceName;
                chart.updateSeries([
                    {
                        data: data,
                    },
                ]);
            });
    }
    var options = {
        series: [
            {
                name: "Valts",
                data: data.slice(),
            },
        ],
        chart: {
            type: "area",
            stacked: false,
            height: 350,
            zoom: {
                type: "x",
                enabled: true,
                autoScaleYaxis: true,
            },
            toolbar: {
                autoSelected: "zoom",
            },
        },
        dataLabels: {
            enabled: false,
        },
        markers: {
            size: 0,
        },
        title: {
            // text: deviceName.slice(),
            align: "left",
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                inverseColors: false,
                opacityFrom: 0.5,
                opacityTo: 0,
                // stops: [0, 90, 100],
            },
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return val;
                },
            },
            title: {
                text: "Valts",
            },
        },
        xaxis: {
            type: "datetime",
        },
        tooltip: {
            shared: false,
            y: {
                formatter: function (val) {
                    return val;
                },
            },
        },
    };

    var chart = new ApexCharts(
        document.querySelector("#device-fault-line-chart"),
        options
    );
    chart.render();

    // Initially fetch some data to display
    fetchChartDataAndUpdateChart(1);

    // Then, update the chart every 1 second with new data
    window.setInterval(function () {
        fetchChartDataAndUpdateChart(1);
    }, 1000); // Adjust this interval as needed
});
