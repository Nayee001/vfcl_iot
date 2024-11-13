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
// setInterval(getDeviceDataCount, 100);

// Getting Data
function fetchAndUpdateData() {
    fetch("/device-data/messages")
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                return response.text().then((text) => {
                    throw new Error(
                        `Unexpected content type: ${contentType}. Response: ${text}`
                    );
                });
            }
        })
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
                    }">
                        ${record.fault_status}
                    </h6>
                    <h6 class="text-end me-2 mb-0">${record.device_status}</h6>
                    </div>`;
            });
            document.getElementById("deviceDataContainer").innerHTML = html;
        })
        .catch((error) => {
            console.error("Fetch and Update Data Error:", error);
            document.getElementById(
                "deviceDataContainer"
            ).innerHTML = `<div class="alert alert-danger" role="alert">${error.message}</div>`;
        });
}

// Fetch and update data every 2000 milliseconds
// setInterval(fetchAndUpdateData, 2000);

async function fetchDeviceData(deviceId) {
    if (!deviceId) {
        return Promise.reject(new Error("No device ID provided"));
    }
    try {
        const response = await fetch(`/device-data/${deviceId}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return await response.json();
        } else {
            const errorText = await response.text();
            throw new Error(
                `Unexpected content type: ${contentType}. Response: ${errorText}`
            );
        }
    } catch (error) {
        console.error("Fetch Device Data Error:", error);
        throw error;
    }
}

function updateUIWithDeviceData(deviceData) {
    const falutStatusClass =
        deviceData.fault_status === "ON" ? "text-danger" : "text-success";
    const deviceStatusClass =
        deviceData.status === "Active" ? "text-danger" : "text-success";

    const imageSrc =
        deviceData.fault_status === "ON"
            ? "assets/img/illustrations/red.png"
            : "assets/img/illustrations/green.png";
    const html = `
        <div class="">
        <div class="text-center fw-semibold pt-3 mb-2">
            <img class="fault-img mb-3" src="${imageSrc}" alt="Device image" style="margin: auto;">
            <div>
                <span class="fault ${falutStatusClass}">${
        deviceData.fault_status
    }</span><br>
                <span>${deviceData.device.name}</span><br>
                <span class="fault ${deviceStatusClass}">${
        deviceData.device.status
    }</span>
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
var chart;
var XAXISRANGE = 10 * 60000; // Set range for X-Axis (last 10 minutes)

// Initialize the
function initializeChart() {
    var options = {
        chart: {
            type: "line",
            height: 350,

            animations: {
                enabled: true,
                easing: "linear",
                dynamicAnimation: {
                    speed: 1000,
                },
            },
            stroke: {
                curve: "smooth",
            },
            toolbar: {
                show: false,
            },
            zoom: {
                enabled: false,
            },
        },
        series: [
            {
                name: "Phase 1",
                data: [], // Initialize empty data
            },
            {
                name: "Phase 2",
                data: [], // Initialize empty data
            },
            {
                name: "Phase 3",
                data: [], // Initialize empty data
            },
        ],
        xaxis: {
            type: "datetime", // X-axis is datetime based
            range: XAXISRANGE, // Range is 10 minutes
        },
        yaxis: {
            title: {
                text: "Current (Amps)",
            },
        },
    };

    chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
}

// Fetch waveform data and update the chart
function fetchChartDataAndUpdateChart(deviceId) {
    fetch(`/get-device-line-chart-data/${deviceId}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((newData) => {
            console.log("Received Data:", newData);

            if (!newData.original || newData.original.eventData.length === 0) {
                console.error("No data available for the selected device");
                return;
            }

            // Prepare data for the chart
            var phase1Data = [];
            var phase2Data = [];
            var phase3Data = [];

            newData.data.forEach((item) => {
                var timestamp = new Date(item.x * 1000).getTime(); // Convert seconds to milliseconds

                // Populate data for each phase
                phase1Data.push({ x: timestamp, y: item.current_phase1 });
                phase2Data.push({ x: timestamp, y: item.current_phase2 });
                phase3Data.push({ x: timestamp, y: item.current_phase3 });
            });

            // Update chart data for all phases
            chart.updateSeries([
                { name: "Phase 1", data: phase1Data },
                { name: "Phase 2", data: phase2Data },
                { name: "Phase 3", data: phase3Data },
            ]);

            // Debug: Check if the chart was updated successfully
            console.log("Chart updated with new data");
        })
        .catch((error) => {
            console.error("Error fetching chart data:", error);
        });
}

// Show the data in the chart for a given deviceId
function showLineChart(deviceId) {
    // Fetch data and update chart when user selects the device
    fetchChartDataAndUpdateChart(deviceId);
}

// Initialize the chart when the page loads
document.addEventListener("DOMContentLoaded", function () {
    initializeChart(); // Initialize the chart once the DOM is loaded
});

async function showData(deviceId) {
    try {
        const deviceData = await fetchDeviceData(deviceId);
        updateUIWithDeviceData(deviceData);
        showLineChart(deviceId);
    } catch (error) {
        showError(error);
    }
}
