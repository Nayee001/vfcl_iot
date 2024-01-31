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
                    let imageSrc = deviceData.fault_status === "ON" ? "assets/img/illustrations/red.png" : "assets/img/illustrations/green.png";


                let html = `
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center" style="position: relative;">
                        <img class="fault-img" src="${imageSrc}" alt="Device image">
                            <span class="fault ${statusClass}">${
                    deviceData.fault_status
                }</span>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
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
            "No device ID provided";
    }
}

// setInterval(() => showData(1), 2000);
// Refresh the data every 2 seconds
