@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User Management /</span> Create New User</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="form-create-users" method="post">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="fname" class="form-label">First Name {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('fname', null, ['placeholder' => 'First Name', 'id' => 'fname', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="lname" class="form-label">Last Name {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('lname', null, ['placeholder' => 'Last Name', 'id' => 'lname', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">E-mail {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('email', null, ['placeholder' => 'Email', 'id' => 'email', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="title" class="form-label">Title {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('title', null, [
                                            'placeholder' => 'Title; Professor, Research Assistant, etc..',
                                            'class' => 'form-control',
                                            'id' => 'title',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="phoneNumber">Phone Number
                                            {!! dynamicRedAsterisk() !!}</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">US (+1)</span>
                                            {!! Form::tel('phonenumber', null, [
                                                'placeholder' => '123 456 7890',
                                                'class' => 'form-control',
                                                'id' => 'phonenumber',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="role" class="form-label">Role {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::select('role', $roles, 1, ['class' => 'form-control', 'id' => 'role']) !!}
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <div class="form-password-toggle">
                                            <label class="form-label" for="password">Password
                                                {!! dynamicRedAsterisk() !!}</label>
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
                                            <label class="form-label" for="confirm-password">Confirm Password
                                                {!! dynamicRedAsterisk() !!}</label>
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
                                        <label for="address" class="form-label">Address {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('address', null, [
                                            'placeholder' => '109 University Square',
                                            'id' => 'address',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="address" class="form-label">Street Address </label>
                                        {!! Form::text('address_optional', null, [
                                            'placeholder' => '109 University Square',
                                            'id' => 'address',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="city" class="form-label">City {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('city', null, ['placeholder' => 'Erie', 'id' => 'city', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="state" class="form-label">State {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('state', null, [
                                            'placeholder' => 'PA',
                                            'class' => 'form-control',
                                            'id' => 'state',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="country" class="form-label">Country {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('country', null, ['placeholder' => 'USA', 'id' => 'country', 'class' => 'form-control']) !!}
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="postal_code" class="form-label">Postal Code
                                            {!! dynamicRedAsterisk() !!}</label>
                                        {!! Form::text('postal_code', null, [
                                            'placeholder' => '16541',
                                            'class' => 'form-control',
                                            'id' => 'postal_code',
                                        ]) !!}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <span>
                                        <i class='bx bx-map'></i> Add Location Names
                                    </span>
                                    <div class="mb-3 col-md-6">
                                    <table class="table " id="dynamic_field">
                                        <tr>
                                          <td class="no-border"><input type="text"  name="location_name[]" placeholder="eg; I Hack Building" class="form-control name_list" /></td>
                                          <td class="no-border">
                                            <button title="Add More" type="button" name="add" id="add" class="btn rounded-pill btn-icon btn-primary">
                                                <i class='bx bx-plus' ></i>
                                              </button>
                                            </td>
                                        </tr>
                                      </table>
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
                    <div class="card">
                        <h5 class="card-header">Note: </h5>
                        <div class="card-body">
                            <div class="mb-3 col-12 mb-0">
                                <div class="alert alert-primary">
                                    <h6 class="alert-heading fw-bold mb-1">Next Step:
                                    </h6>
                                    <p class="mb-0">After creating a new user, ensure they are
                                        assigned at least one device before proceeding with additional steps.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->
@endsection
@include('users.user-js')
