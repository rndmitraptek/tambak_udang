@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.finance.po.script')
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
        <div class="col-lg-12" ng-show="form == 'list'">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Transaksi PO Benur
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-map-marker"></i>
                                        <span>Buat PO Benur</span>
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
        <div class="col-lg-12" ng-show="form == 'input'">
        <form id="formInput">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Buat PO Benur
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button type="submit" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-save"></i>
                                        <span>Simpan Transaksi</span>
                                    </span>
                                </button>
                            </li>
                            <li class="m-portlet__nav-item">
                                <button type="button" ng-click="kembali()" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>Kembali ke List PO</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <label for="recipient-name" class="form-control-label">No PO</label>
                                <input type="text" class="form-control" id="no_po" name="no_po" ng-model="input.no_po">
                            </div>
                            <div class="form-group m-form__group">
                                <label>Supplier</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" placeholder="Search for..." ng-model="input.nama_supplier">
                                    <div class="input-group-append">
                                        <button ng-click="cari_supplier()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="uuid_lokasi">Lokasi</label>
                                <select class="form-control" id="uuid_lokasi" ng-change="get_siklus()" ng-model="input.uuid_lokasi" name="uuid_lokasi">
                                    <option  value="" >Pillih Lokasi</option>
                                    <option ng-repeat="x in lokasi" value="<% x.uuid %>" ><% x.nama_lokasi %></option>
                                </select>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="uuid_lokasi">Siklus</label>
                                <select class="form-control" id="uuid_siklus" ng-model="input.uuid_siklus" name="uuid_siklus">
                                    <option  value="" >Pillih Siklus</option>
                                    <option ng-repeat="x in siklus" value="<% x.uuid %>" ><% x.nama_siklus %></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <label for="recipient-name" class="form-control-label">Tanggal PO</label>
                                <input type="text" class="form-control general_datepicker" id="tanggal_po" name="tanggal_po" ng-model="input.tanggal_po">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Tanggal Kirim</label>
                                <input type="text" class="form-control general_datepicker" id="tanggal_kirim" name="tanggal_kirim" ng-model="input.tanggal_kirim">
                            </div>
                            <div class="form-group m-form__group">
                                <label for="exampleTextarea">Keterangan</label>
                                <textarea class="form-control" rows="4" id='keterangan' name='keterangan' ng-model='input.keterangan'></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <label for="recipient-name" class="form-control-label">Qty</label>
                                <input type="text" class="form-control text-right" input-currency id="qty" name="qty" ng-model="input.qty" ng-change="hitung()">
                            </div>
                            <div class="form-group m-form__group">
                                <label for="recipient-name" class="form-control-label">Harga Satuan</label>
                                <input type="text" class="form-control text-right" input-currency id="harga_satuan" name="harga_satuan" ng-model="input.harga_satuan" ng-change="hitung()">
                            </div>
                            <div class="form-group m-form__group">
                                <label for="recipient-name" class="form-control-label">Total</label>
                                <input type="text" class="form-control text-right" input-currency id="total" name="total" ng-model="input.total" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>

<!--end::Modal-->

<look-up-table
      lookup-id="lookup_supplier"
      ajax-url="{{ route('finance.po.supplier') }}"
      columns="supplierColumns"
      page-length="8"
      on-select="selectSupplier(row)">
</look-up-table>

<!--end::Modal-->
@endsection

@section('js')
<!--begin::Page Vendors -->

<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}
<script src="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>

<!--end::Page Resources -->
@endsection



