@section('script')
    <script>
        $(function() {
            $('#terms-and-conditions-form').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#terms-and-conditions-form')[0]);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('users.terms-and-conditions') }}',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        if (resp.code == '{{ __('statuscode.CODE200') }}') {
                            $('#terms-conditions').modal('toggle');
                            $(".modal-backdrop").fadeOut();
                            let timerInterval
                            Swal.fire({
                                title: resp.Message,
                                html: 'Please Wait for a moment, while we setup your IOT platform,<br> please do not refresh the page...',
                                timer: 10000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                    const b = Swal.getHtmlContainer().querySelector(
                                        'b')
                                    timerInterval = setInterval(() => {
                                        b.textContent = Swal.getTimerLeft()
                                    }, 100)
                                },
                                willClose: () => {
                                    clearInterval(timerInterval)
                                }
                            })
                            setTimeout(function() {
                                location.reload();
                            }, 10000);
                        } else {
                            toastr.error(resp.Message);
                        }
                    },
                    error: function(data) {
                        $(".submit").attr("disabled", false);
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
        });
        var myDate = new Date();
        var hrs = myDate.getHours();
        var greet;
        if (hrs < 12)
            greet = 'Good Morning ☀️';
        else if (hrs >= 12 && hrs <= 17)
            greet = 'Good Afternoon ☀️';
        else if (hrs >= 17 && hrs <= 24)
            greet = 'Good Evening 🌆';
        document.getElementById('greetings').innerHTML = '<b>' + greet + '</b>';
    </script>
@endsection
