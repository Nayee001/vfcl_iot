@extends('layouts.app')
@section('content')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Menu Management / </span> Menus
            </h4>
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Create New Menu </h5>
                        </div>
                        <div class="card-body">
                            <form id="form-menu-create" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-fullname">Menu title</label>
                                    {!! Form::text('title', null, ['placeholder' => 'Menu title', 'id' => 'title', 'class' => 'form-control']) !!}
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-company">Link</label>
                                    {!! Form::text('link', null, ['placeholder' => 'admin/index', 'id' => 'link', 'class' => 'form-control']) !!}
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-email">Icon</label>
                                    <div class="input-group input-group-merge">
                                        {!! Form::text('icon', null, [
                                            'placeholder' => 'bx bx-menu-alt-right',
                                            'id' => 'icon',
                                            'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                    <div class="form-text ">bx bx-menu-alt-right - <a class="example-icon" target="_blank"
                                            href="https://boxicons.com/">Click to find more</a></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-phone">Sort</label>
                                    {!! Form::number('sort', null, [
                                        'placeholder' => 'Sorting Number',
                                        'max' => '8',
                                        'min' => '1',
                                        'step' => '1',
                                        'id' => 'sort',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-message">Menu Type</label>
                                    {!! Form::select('menu_type', ['1' => 'Page', '2' => 'Link', '3' => 'submenu'], '2', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Menu Type',
                                    ]) !!}
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-message">Submenu Type</label>
                                    {!! Form::select('submenu', ['1' => 'Yes', '0' => 'No'], '0', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Submenu Type',
                                    ]) !!}
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-message">Target</label>
                                    {!! Form::select('target', ['_self' => 'Open in same window', '_blank' => 'Open in new window'], '_self', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Target',
                                    ]) !!}
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-message">Permissions</label>
                                    {!! Form::select('permission_id', $permission, null, [
                                        'id' => 'permission_id',
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Permission',
                                    ]) !!}
                                    <div class="form-text ">couldn't find appropriate permission ? <a
                                            onclick="getPermissionForm()" class="example-icon" href="javascript:void(0)">
                                            Create New </a></div>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="status" id="status"
                                        checked="">
                                    <label class="form-check-label" for="status">Publish Menu</label>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="submit" class="btn btn-secondary">Reset</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"></h5>
                            {{-- <small class="text-muted float-end">Drag and Sort Menus</small> --}}
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="createPermissionModel" data-backdrop="static">
            </div>
        </div>
    </div>
    <!-- Content wrapper -->
@endsection
@include('menus.menu-js')
