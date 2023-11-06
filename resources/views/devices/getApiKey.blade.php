<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="assignDevice"><i class="bx bx-chip"></i> {{ $deviceData->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="assign-device-to-user" method="post">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="device_id" class="form-label">Api Key</label>
                        <!-- We will use this span to show the API Key -->
                        <span id="apiKeyText">{{ $deviceData->api_key }}</span>
                        <!-- This button is for copying the API Key -->
                        <button type="button" id="copy" class="btn rounded-pill btn-icon btn-outline-primary"><i
                                class='bx bx-copy-alt'></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <!-- We don't need another submit button here -->
            </div>
        </form>
    </div>
</div>
<script>
    // Function to copy text to clipboard using the modern clipboard API
    async function copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            // Trigger the tooltip to show the copied message
            const tooltipTrigger = document.getElementById('copy');
            const bsTooltip = bootstrap.Tooltip.getInstance(tooltipTrigger);
            // Show toast message
            showToast("API key copied to clipboard!");

        } catch (err) {
            console.error('Could not copy text: ', err);
        }
    }

    // Event listener for the copy button
    document.getElementById('copy').addEventListener('click', function() {
        const apiKey = document.getElementById('apiKeyText').textContent;
        copyToClipboard(apiKey);
    });

    document.getElementById('apiKeyText').addEventListener('click', function() {
        const apiKey = document.getElementById('apiKeyText').textContent;
        copyToClipboard(apiKey);
    });

    // Initialize the tooltip for the copy button
    const copyButton = document.getElementById('copy');
    new bootstrap.Tooltip(copyButton, {
        title: 'Click to copy API key',
        placement: 'bottom',
    });

    function showToast(message) {
        var $toast = $('<div class="toast">').text(message);
        $("body").append($toast);
        $toast.fadeIn(400).delay(2000).fadeOut(400, function() {
            $(this).remove();
        });
    }
</script>
