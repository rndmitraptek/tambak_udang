@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <style>
        .menu-tree {
            list-style-type: none;  /* hilangkan bullet */
        }
        .menu-tree li ul {
            list-style-type: none; /* hilangkan bullet di child */
        }
    </style>
@endsection
@section('ctrl')
@include('feature.auth.role.script')
@endsection

@section('content')
<!-- BEGIN: Subheader -->
{{-- <div class="m-subheader">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Dashboard</h3>
        </div>
        <div>
            
        </div>
    </div>
</div> --}}

<!-- END: Subheader -->
<div class="m-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Role
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Tambah Role</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="viewtabel"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="m_create" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formInput">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        <label for="recipient-name" class="form-control-label">role</label>
                        <input type="text" class="form-control" name="role" ng-model="input.role">
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" ng-model="input.keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->
<!--begin::Modal-->
<div class="modal fade" id="m_member" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formInput">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group m-form__group">
                                <label>Member</label>
                                <div class="input-group">
                                    <select class="form-control" id="id_user" ng-model="id_user" name="id_user">
                                        <option ng-repeat="x in member" value="<% x.id_user %>" ><% x.nama %></option>
                                    </select>
                                    <div class="input-group-append">
                                        <button ng-click="tambah_member()" class="btn btn-primary" type="button"><i class="la la-plus"></i>Tambah Member</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm m-table m-table--head-bg-brand">
                        <thead class="thead-inverse">
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="x in member_role">
                                <td><% x.nama %></td>
                                <td><% x.username %></td>
                                <td><a ng-click="hapus_member(x)" href="javascript:void(0)" id="hapus" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-danger la la-remove"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->
<!--begin::Modal-->
<div class="modal fade" id="m_akses" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{-- <form id="formInput"> --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Akses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="m-scrollable m-scroller ps" data-scrollbar-shown="true" data-scrollable="true" data-height="<% vh70 %>" style="height: 70vh; overflow: hidden;">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="menu-tree">
                                    <li ng-repeat="menu in menu">
                                        <label class="m-checkbox m-checkbox--success">
                                            <input type="checkbox" ng-model="menu.checked"> <i class="<%menu.icon%>"></i> <%menu.label%>
                                            <span></span>
                                        </label>
                                        <!-- Level 2 -->
                                        <ul ng-if="menu.items && menu.items.length > 0">
                                            <li ng-repeat="child1 in menu.items">
                                                <label class="m-checkbox m-checkbox--success">
                                                    <input type="checkbox" ng-model="child1.checked"> <i class="<%child1.icon%>"></i> <%child1.label%>
                                                    <span></span>
                                                </label>

                                                <!-- Level 3 -->
                                                <ul ng-if="child1.items && child1.items.length > 0">
                                                    <li ng-repeat="child2 in child1.items">

                                                        <label class="m-checkbox m-checkbox--success">
                                                            <input type="checkbox" ng-model="child2.checked"> <i class="<%child2.icon%>"></i> <%child2.label%>
                                                            <span></span>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" ng-click="update_menu()" class="btn btn-primary">Simpan</button>
                </div>
            {{-- </form> --}}
        </div>
    </div>
</div>
<!--end::Modal-->
@endsection

@section('js')
<!--begin::Page Vendors -->
<script src="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}

<!--end::Page Resources -->
@endsection



