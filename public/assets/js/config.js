/**
 * Config
 * -------------------------------------------------------------------------------------
 * ! IMPORTANT: Make sure you clear the browser local storage In order to see the config changes in the template.
 * ! To clear local storage: (https://www.leadshook.com/help/how-to-clear-local-storage-in-google-chrome-browser/).
 */

'use strict';

// JS global variables
let config = {
  colors: {
    primary: '#696cff',
    secondary: '#8592a3',
    success: '#71dd37',
    info: '#03c3ec',
    warning: '#ffab00',
    danger: '#ff3e1d',
    dark: '#233446',
    black: '#000',
    white: '#fff',
    body: '#f4f5fb',
    headingColor: '#566a7f',
    axisColor: '#a1acb8',
    borderColor: '#eceef1'
  }
};

// Getting Count
function getDeviceDataCount() {
    fetch('/device-data/count')
        .then(response => response.json())
        .then(data => {
            document.getElementById('dataCount').innerText = data.count;
        })
        .catch(error => console.error('Error:', error));
}
setInterval(getDeviceDataCount, 100);

// Getting Data
function fetchAndUpdateData() {
    fetch('/device-data/messages')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(record => {
                console.log('Data:', data);
                html += `
                    <div class="d-flex align-items-center border-top py-3">
                        <span class="text-success me-2">
                            <i class="mdi mdi-circle"></i>
                        </span>
                        <h6 class="fw-normal mb-0">${record.device ? record.device.name : 'Device Name'}</h6>
                        <div class="flex-grow-1"></div>
                        <h6 class="text-end me-4 mb-0">${record.fault_status}</h6>
                        <h6 class="text-end me-2 mb-0">${record.device_status}</h6>
                    </div>`;
            });
            document.getElementById('deviceDataContainer').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}

// Fetch and update data every 1000 milliseconds
setInterval(fetchAndUpdateData, 5000);
