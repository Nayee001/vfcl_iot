<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="assignDevice"> <i class="bx bx-chip"></i> Assign Device to User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="assign-device-to-user" method="post">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-3">
                        <label for="device_id" class="form-label">Select Your Device {!! dynamicRedAsterisk() !!}</label>
                        {!! Form::select('device_id', $allDevices, $deviceData->id, [
                            'placeholder' => 'Select Device',
                            'id' => 'device_id',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                    <div class="col mb-3">
                        <label for="assign_to" class="form-label">Select Your Customer {!! dynamicRedAsterisk() !!}</label>
                        {!! Form::select('assign_to', $customers, null, [
                            'placeholder' => 'Select Customer',
                            'id' => 'assign_to',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>

                {{-- <span><i class='bx bx-location-plus'></i> Device Location</span>
                <hr> --}}
                <div class="row no-customer">
                    <p>No Customer Selected</p>
                </div>
                <div id="loading_spinner" style="display:none;">
                    <!-- Spinner from Bootstrap or custom HTML for spinner -->
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div id="location_radio_buttons">
                    <!-- Radio buttons will be added here by jQuery -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button id="submit" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        $('#assign-device-to-user').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Disable the submit button to prevent multiple submits
            $('.submit').attr('disabled', true);

            // Show the loader
            $('#loader').show();

            var formData = new FormData($('#assign-device-to-user')[0]);
            $.ajax({
                method: 'POST',
                url: '{{ route('assign.device') }}',
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(resp) {
                    // Hide the loader
                    $('#loader').hide();

                    if (resp.code == '{{ __('statuscode.CODE200') }}') {
                        toastr.success(resp.Message);
                        setTimeout(function() {
                            location.reload();
                        }, 1900);
                    } else {
                        toastr.error(resp.Message);
                    }
                },
                error: function(data) {
                    // Hide the loader
                    $('#loader').hide();

                    // Enable the submit button again
                    $('.submit').attr("disabled", false);

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

        $('#assign_to').change(function() {
            var customer_id = $(this).val(); // Get the selected customer id
            $('#loading_spinner').show();

            if (customer_id) {
                setTimeout(function() {
                    $.ajax({
                        url: '/get-customer-locations/' + customer_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#loading_spinner').hide(); // Hide spinner
                            $('.no-customer').hide();
                            $('div#location_radio_buttons')
                        .empty(); // Clear previous radio buttons

                            if (data.locations.length > 0) {
                                $.each(data.locations, function(key, location) {
                                    $('div#location_radio_buttons').append(
                                        `<div class="form-check">
                                <input type="radio" class="form-check-input" name="location_id" id="location_${location.id}" value="${location.id}">
                                <label class="form-check-label" for="location_${location.id}">${location.location_name}</label>
                            </div>`
                                    );
                                });
                            } else {
                                // Alternative message when no location is set
                                $('div#location_radio_buttons').append(
                                    `<div class="alert alert-warning" role="alert">
                            No location assigned to this user.
                        </div>`
                                );
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#loading_spinner').hide(); // Hide spinner
                            $('.no-customer').hide();
                            $('div#location_radio_buttons').empty().append(
                                `<div class="alert alert-danger" role="alert">
                        Unable to load locations. Please try again later.
                    </div>`
                            );
                        }
                    });
                }, 1000);
            } else {
                $('#loading_spinner').hide(); // Hide spinner immediately if no customer is selected
                $('div#location_radio_buttons').empty();
            }

        });
    });
</script>
