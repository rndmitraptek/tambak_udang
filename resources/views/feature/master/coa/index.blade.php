@extends('layout')
@section('css')
	<link href="<% url('/') %>/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.master.coa.script')
@endsection

@section('content')
<!-- BEGIN: Subheader -->

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
                                COA
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <table class="table m-table m-table--head-bg-brand" id="viewtabel">
                        <thead>
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Tipe Akun</th>
                            <th>Pos Laporan</th>
                            <th>Saldo Normal</th>
                            <th>Kode Parent</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="akun in coa | filter:searchText">
                            <td style="width:150px"><%akun.kode_akun%></td>
                            <td><%akun.nama_akun%></td>
                            <td><%akun.tipe_akun%></td>
                            <td><%akun.pos_laporan%></td>
                            <td><%akun.saldo_normal%></td>
                            <td><%akun.kode_parent || '-'%></td>
                            <td style="width:140px">
                                <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-edit m--font-warning"></i></a>
                                <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a>
                                <button ng-click="tambah()" ng-show="akun.kode_parent" href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-plus m--font-primary"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="m_create" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">COA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-body">
                    <h5>Akun Parent</h5>
                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <p class="mb-1">Kode Parent</p>
                            <h6>11</h6>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <p class="mb-1">Nama Parent</p>
                            <h6>Aset Lancar</h6>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <p class="mb-1">Tipe Akun</p>
                            <h6>Asset</h6>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <p class="mb-1">POS Laporan</p>
                            <h6>Neraca</h6>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <p class="mb-1">Saldo</p>
                            <h6>Neraca</h6>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group m-form__group">
                        <label for="exampleInputEmail1">Kode Akun</label>
                        <div class="input-group m-input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">11</span>
                            </div>
                            <input type="text" class="form-control" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Nama Akun</label>
                        <input type="text" class="form-control" id="recipient-name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--end::Modal-->
@endsection

@section('js')
<!--begin::Page Vendors -->
<script src="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--end::Page Resources -->
@endsection



