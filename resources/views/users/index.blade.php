@extends('layouts.app')
<!-- Content -->
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User Management /</span> User List</h4>

            </div>
            <div class="mt-3">
                <!-- Button trigger modal -->
                <a href="" class="btn btn-primary">Create User</a>
            </div>

        </div>
        <div class="card">
            <h5 class="card-header">User Management</h5>

            <div class="table-responsive text-nowrap">
                <div class="container">
                    <table class="table users-datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Devices</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('users.user-js')
