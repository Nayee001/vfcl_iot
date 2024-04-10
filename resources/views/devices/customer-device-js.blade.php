@section('script')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/customer/devices/data',
                type: 'GET',
                dataType: 'json',
                success: function(devices) {
                    let contentHtml = '';
                    if (devices.length > 0) {
                        devices.forEach(function(device) {
                            contentHtml += `
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title">${device.name}</h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">${device.status}</h6>
                                    <p class="card-text">${device.description}</p>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">API KEY: ${device.api_key}</h6>
                                </div>
                            </div>
                        </div>`;
                        });
                    } else {
                        contentHtml = `
                    <div class="col">
                        <div class="alert alert-info" role="alert">
                            There are no devices assigned to you!
                        </div>
                    </div>`;
                    }
                    $('#devices').html(contentHtml);
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: ", error);
                    $('#devices').html(`
                <div class="col">
                    <div class="alert alert-danger" role="alert">
                        An error occurred while fetching the devices.
                    </div>
                </div>`);
                }
            });
        });
    </script>
@endsection
