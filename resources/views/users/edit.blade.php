@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User Management /</span> Edit User</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
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
                                        <label for="role" class="form-label">Role {!!dynamicRedAsterisk()!!}</label>
                                        {!! Form::select('roles', $roles, $userRole, [
                                            'class' => 'form-control',
                                            'id' => 'roles',
                                            'placeholder' => 'Select Role',
                                            disabledIfNotSuperAdmin(),
                                        ]) !!}
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <div class="form-password-toggle">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text" title="Generate Random Password"><a
                                                        href="javascript:void(0);" title="Generate Random Password"
                                                        id="generatePassword"
                                                        class="btn rounded-pill btn-icon-generate_password btn-outline-primary __web-inspector-hide-shortcut__"><span
                                                            class="tf-icons bx bx-refresh"></span></a></span>
                                                {!! Form::password('password', ['placeholder' => 'Password', 'id' => 'password', 'class' => 'form-control']) !!}
                                                <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                                        class="bx bx-hide"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <div class="form-password-toggle">
                                            <label class="form-label" for="confirm-password">Confirm Password</label>
                                            <div class="input-group input-group-merge">
                                                {!! Form::password('confirm-password', [
                                                    'placeholder' => 'Confirm Password',
                                                    'id' => 'confirm_password',
                                                    'class' => 'form-control',
                                                ]) !!}
                                                <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                                        class="bx bx-hide"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="mb-3 col-md-6">
                                        <label for="location_type" class="form-label">Location Type {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('location_type', $locationTypes, $user->locations->location_type, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Select Location Type',
                                            'id' => 'location_type',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="address" class="form-label">Address {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('address', $user->locations->address, ['placeholder' => '109 University Square', 'id' => 'address', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="city" class="form-label">City {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('city', $user->locations->city, ['placeholder' => 'Erie', 'id' => 'city', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="state" class="form-label">State {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('state', $user->locations->state, [
                                            'placeholder' => 'PA',
                                            'class' => 'form-control',
                                            'id' => 'state',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="country" class="form-label">Country {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('country', $user->locations->country, ['placeholder' => 'USA', 'id' => 'country', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="postal_code" class="form-label">Postal Code {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('postal_code', $user->locations->postal_code, [
                                            'placeholder' => '16541',
                                           'class' => 'form-control',
                                            'id' => 'postal_code',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" id="submit"
                                        class="submit btn btn-primary me-2">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->
@endsection
@section('script')
    <script>
        // User Update
        $(document).ready(function() {
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
                                location.href = '{{ url('users') }}';
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

        // to Genarate Random Password
        function generateRandomPassword(length) {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";

            let password = "";
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset.charAt(randomIndex);
            }
            return password;
        }
        var controlError = document.getElementById("generatePassword");
        if (controlError) {
            document.getElementById("generatePassword").addEventListener("click", function() {
                const randomPassword = generateRandomPassword(8);
                document.getElementById("password").value = randomPassword;
                document.getElementById("confirm_password").value = randomPassword;
            });
        }
    </script>
@endsection
