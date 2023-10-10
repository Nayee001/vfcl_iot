@extends('layouts.app')
@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Roles And Persmissions /</span> Create New</h4>

            <div class="row">
                <div class="col-md-12">

                    <div class="card mb-4">
                        <h5 class="card-header">Create Role and Permission</h5>
                        <hr class="my-0" />
                        <div class="card-body">
                            <form id="form-roles-create" method="post">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-3 col-md-4">
                                            <label for="nameBasic" class="form-label">Name</label>
                                            {!! Form::text('name', null, ['placeholder' => 'Role Name', 'id' => 'name', 'class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        @php $counter = 0 @endphp
                                        @foreach ($permission as $value)
                                            <div class="col-md-3">
                                                <label>{{ Form::checkbox('permission[]', $value->id, false, ['id' => 'permission', 'class' => 'name']) }}
                                                    {{ $value->name }}</label>
                                            </div>
                                            @php $counter++ @endphp
                                            @if ($counter % 4 == 0)
                                    </div>
                                    <div class="row">
                                        @endif
                                        @endforeach
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
                        <!-- /Account -->
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->
@endsection
@include('roles.role-js')

