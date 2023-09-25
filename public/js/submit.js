function formSubmit(name) {
    e.preventDefault();
    e.stopPropagation();
    $('submit').attr('disabled', true);
    var formData = new FormData($('#form-roles-create')[0]);
    $.ajax({
        method: 'POST',
        url: '{{ route('roles.store') }}',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(resp) {
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
  }
