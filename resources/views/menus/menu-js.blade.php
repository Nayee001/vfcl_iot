@section('script')
    <script>
        $(function() {
            $('#form-menu-create').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#form-menu-create')[0]);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('menus.store') }}',
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
                            $(ele).removeClass('error');
                            $(ele).addClass('error');
                            var parentInputGroup = $(ele).closest('.input-group-merge');

                            if (parentInputGroup.length > 0) {
                                $('<label class="error">' + value + '</label>')
                                    .insertAfter(
                                        parentInputGroup);
                            } else {
                                $('<label class="error">' + value + '</label>')
                                    .insertAfter(ele);
                            }
                        });
                    }
                });
            });
        });

        function getPermissionForm() {
            $.ajax({
                url: '{{ route('permissions.create') }}',
                method: 'GET',
                success: function(res) {
                    $("#createPermissionModel").html(res);
                    $("#createPermissionModel").modal('show');
                }
            });
        }
    </script>
@endsection
