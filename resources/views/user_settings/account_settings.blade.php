@extends('layouts.app')
@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('change-password', Auth::user()->id) }}"><i
                                    class='bx bxs-lock-open'></i>
                                Change Password</a>
                        </li>
                        {{-- @can('api-connections')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('api-connections') }}"><i class="bx bx-link-alt me-1"></i>
                                    Api Connections</a>
                            </li>
                        @endcan --}}
                    </ul>

                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <!-- Account -->
                        {{-- <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="../assets/img/avatars/1.png" alt="user-avatar" class="d-block rounded"
                                    height="100" width="100" id="uploadedAvatar" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" class="account-file-input" hidden
                                            accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>

                                    <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                </div>
                            </div>
                        </div> --}}
                        <hr class="my-0" />
                        <div class="card-body">
                            <form id="form-users-edit" method="post">
                                @csrf
                                {{ method_field('PUT') }}
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="fname" class="form-label">First Name {!!dynamicRedAsterisk()!!}</label>
                                        {!! Form::text('fname', $user['fname'], [
                                            'placeholder' => 'First Name',
                                            'id' => 'fname',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="lname" class="form-label">Last Name {!!dynamicRedAsterisk()!!}</label>
                                        {!! Form::text('lname', $user['lname'], [
                                            'placeholder' => 'Last Name',
                                            'id' => 'lname',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">E-mail {!!dynamicRedAsterisk()!!}</label>
                                        {!! Form::text('email', $user['email'], ['placeholder' => 'Email', 'id' => 'email', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="title" class="form-label">Title {!!dynamicRedAsterisk()!!}</label>
                                        {!! Form::text('title', $user['title'], [
                                            'placeholder' => 'Title; Professor, Research Assistant, etc..',
                                            'class' => 'form-control',
                                            'id' => 'title',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="phoneNumber">Phone Number {!!dynamicRedAsterisk()!!}</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">US (+1)</span>
                                            {!! Form::tel('phonenumber', $user['phonenumber'], [
                                                'placeholder' => '123 456 7890',
                                                'class' => 'form-control',
                                                'id' => 'phonenumber',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="role" class="form-label">Role</label>
                                        {!! Form::select('roles', $roles, $userRole, [
                                            'class' => 'form-control',
                                            'id' => 'roles',
                                            'placeholder' => 'Select Role',
                                            disabledIfNotSuperAdmin(),
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" id="submit"
                                        class="submit btn btn-primary me-2">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /Account -->
                    </div>

                    {{-- <div class="card">
                        <h5 class="card-header">Delete Account</h5>
                        <div class="card-body">
                            <div class="mb-3 col-12 mb-0">
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?
                                    </h6>
                                    <p class="mb-0">Once you delete your account, there is no going back. Please be
                                        certain.</p>
                                </div>
                            </div>
                            <form id="form-users-deactivation" method="post">
                                @csrf
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="accountDeactivation"
                                        id="accountDeactivation" />
                                    <label class="form-check-label" for="accountDeactivation">I confirm my account
                                        deactivation</label>
                                </div>
                                <button type="submit" class="btn btn-danger deactivate-account">Deactivate
                                    Account</button>
                            </form>
                        </div>
                    </div>
                     --}}
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->
@endsection
@section('script')
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
    <script>
        // User deactivation
        $(document).ready(function() {
            $('#form-users-deactivation').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#form-users-deactivation')[0]);
                $.ajax({
                    method: 'post',
                    url: '{{ route('user-deactivate', $user->id) }}',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        if (resp.code == '{{ __('statuscode.CODE200') }}') {
                            let timerInterval
                            Swal.fire({
                                title: resp.Message,
                                timer: 2000,
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
                            $(ele).addClass('errors');
                            $('<label class="error">' + value + '</label>')
                                .insertAfter(ele);
                        });
                    }
                });
            });

            $('#form-users-edit').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('submit').attr('disabled', true);
                var formData = new FormData($('#form-users-edit')[0]);
                $.ajax({
                    method: 'POST',
                    url: '{{ route('users.update', $user->id) }}',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
                            $(ele).addClass('errors');
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
    </script>
@endsection
