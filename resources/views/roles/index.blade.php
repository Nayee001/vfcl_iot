@extends('layouts.app')
<!-- Content -->
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between">
            <div>
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Role Management /</span> Role List</h4>

            </div>
            <div class="mt-3">
                @can('role-create')
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">Create Role</a>
                @endcan
                @can('permission-create')
                    <a onclick="getPermissionForm()" class="btn btn-secondary" href="javascript:void(0)">
                        Create Permission </a>
                @endcan
            </div>

        </div>
        <div class="card">
            <h5 class="card-header">Role Management</h5>

            <div class="table-responsive text-nowrap">
                <div class="container">
                    <table class="table roles-datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Role Name</th>
                                <th>Guard</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="createPermissionModel" data-backdrop="static">
    </div>
    <!-- / Content -->
@endsection
@include('roles.role-js')
