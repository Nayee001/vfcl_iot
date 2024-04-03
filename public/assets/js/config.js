/**
 * Config File for JS
 *
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

async function fetchDeviceData(deviceId) {
    if (!deviceId) {
        return Promise.reject(new Error("No device ID provided"));
    }
    const response = await fetch(`/device-data/${deviceId}`);
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
}

function updateUIWithDeviceData(deviceData) {
    const falutStatusClass = deviceData.fault_status === "ON" ? "text-danger" : "text-success";
    const deviceStatusClass = deviceData.status === "Active" ? "text-danger" : "text-success";

    const imageSrc =
        deviceData.fault_status === "ON"
            ? "assets/img/illustrations/red.png"
            : "assets/img/illustrations/green.png";
    const html = `
        <div class="col-md-12 mt-3">
        <div class="text-center fw-semibold pt-3 mb-2">
            <img class="fault-img mb-3" src="${imageSrc}" alt="Device image" style="margin: auto;">
            <div>
                <span class="fault ${falutStatusClass}">${
        deviceData.fault_status
    }</span><br>
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
                        <h6 class="mb-0"><span class="fw-medium ${falutStatusClass}">${
        deviceData.health_status
    }</span></h6>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-label-info p-2 me-2"><i class='bx bx-time'></i></span>
                    <div>
                        <small>Last Sync</small>
                        <h6 class="mb-0">${formatTimestamp(
                            deviceData.timestamp
                        )}</h6>
                    </div>
                </div>
            </div>
        </div>
        </div>`;
    document.getElementById("device-fault-status-shown").innerHTML = html;
}

function showError(error) {
    document.getElementById(
        "device-fault-status-shown"
    ).innerHTML = `<div class="alert alert-danger" role="alert">${error.message}</div>`;
}

async function showData(deviceId) {
    try {
        const deviceData = await fetchDeviceData(deviceId);
        updateUIWithDeviceData(deviceData);
    } catch (error) {
        showError(error);
    }
}

function showLineChart(deviceId) {
    var lineChart = document.getElementById("device-fault-line-chart");
    fetchChartDataAndUpdateChart(deviceId);
}
// setInterval(() => showData(1), 2000);

document.addEventListener("DOMContentLoaded", function () {
    var lastDate = 0;
    var data = [];
    var XAXISRANGE = 10 * 60000; // Adjust this based on your needs

    function getNewSeries(baseDate, { min, max }) {
        var newDate = baseDate + 1000;
        lastDate = newDate;

        for (var i = 0; i < data.length - 10; i++) {
            data[i].x = new Date(newDate - XAXISRANGE - 1000);
            data[i].y = 0;
        }

        data.push({
            x: new Date(newDate),
            y: Math.floor(Math.random() * (max - min + 1)) + min,
        });
    }

    function fetchChartDataAndUpdateChart(deviceId) {
        fetch(`/get-device-line-chart-data/${deviceId}`)
            .then((response) => response.json())
            .then((newData) => {
                data = newData.data.map((item) => ({
                    x: new Date(item.x),
                    y: item.y,
                }));
                chart.updateSeries([{ data: data }]);
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
    fetchChartDataAndUpdateChart();

    // Then, update the chart every 1 second with new data
    // window.setInterval(function () {
    //     fetchChartDataAndUpdateChart();
    // }, 1000); // Adjust this interval as needed
});
