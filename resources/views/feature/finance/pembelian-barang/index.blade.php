@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.finance.pembelian-barang.script')
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
                                Pembelian Barang
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Transaksi Pembelian Barang</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                {{-- <h1><% tes %></h1> --}}
                    <!--begin: Datatable -->
                    <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info" id="viewtabel">
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-12" ng-show="form == 'input'">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Buat Pembelian Barang
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="simpan()" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-save"></i>
                                        <span>Simpan Transaksi</span>
                                    </span>
                                </button>
                            </li>
                            <li class="m-portlet__nav-item">
                                <button ng-click="kembali()" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>Kembali ke List Pembelian</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <form id="formInput">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="recipient-name" class="form-control-label">No Pembelian</label>
                                    <input type="text" class="form-control" id="no_pembelian_barang" name="no_pembelian_barang" ng-model="input.no_pembelian_barang">
                                </div>
                                <div class="row">
                                    <div class="form-group m-form__group col-lg-6">
                                        <label for="recipient-name" class="form-control-label">Tanggal Pembelian</label>
                                        <input type="text" class="form-control general_datepicker" id="tanggal_pembelian_barang" name="tanggal_pembelian_barang" ng-model="input.tanggal_pembelian_barang">
                                    </div>
                                    <div class="form-group m-form__group col-lg-6">
                                        <label for="recipient-name" class="form-control-label">Tanggal Jatuh Tempo</label>
                                        <input type="text" class="form-control general_datepicker" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" ng-model="input.tanggal_jatuh_tempo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="uuid_lokasi">Lokasi</label>
                                    <select class="form-control" id="uuid_lokasi" ng-model="input.uuid_lokasi" name="uuid_lokasi">
                                        <option  value="" >Pillih Lokasi</option>
                                        <option ng-repeat="x in lokasi" value="<% x.uuid %>" ><% x.nama_lokasi %></option>
                                    </select>
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
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Pembayaran</label>
                                    <select class="form-control" id="pembayaran" name="pembayaran" ng-model="input.pembayaran">
                                        <option value="TUNAI">TUNAI</option>
                                        <option value="HUTANG">HUTANG</option>
                                    </select>
                                </div>
                                <div class="form-group -form__group">
                                    <label for="message-text" class="form-control-label" >Keterangan</label>
                                    <textarea class="form-control" id="keterangan" nama="keterangan" ng-model="input.keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="button" ng-click="handleClickBarang()" class="btn btn-outline-primary btn-sm m-btn m-btn--icon mb-2">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Barang</span>
                                    </span>
                                </button>
                                <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info">
                                    <thead>
                                        <tr>
                                            <th style="width: 400px">Nama Barang</th>
                                            <th style="width: 200px">Harga</th>
                                            <th style="width: 200px">Jumlah</th>
                                            <th style="width: 200px">Subtotal</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="i in detail">
                                            <td><% i.nama_barang %></td>
                                            <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-model="i.harga" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-model="i.qty" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-model="i.subtotal" readonly></td>
                                            <td ><button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View" style="height: 25px;"><i class="la la-remove m--font-danger"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-right">Total</th>
                                            <th class="text-right"><% jumlah | currency:'' %></th>
                                            <th class="text-right"><% total | currency:'' %></th> 
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<look-up-table
      lookup-id="lookup_supplier"
      ajax-url="{{ route('finance.pembelian_barang.get_supplier') }}"
      columns="supplierColumns"
      page-length="8"
      on-select="selectSupplier(row)">
</look-up-table>

<look-up-table
      lookup-id="lookup_barang"
      ajax-url="{{ route('finance.pembelian_barang.get_barang') }}"
      columns="barangColumns"
      page-length="8"
      on-select="selectBarang(row)">
</look-up-table>

@endsection

@section('js')
<!--begin::Page Vendors -->
<script src="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}

<!--end::Page Resources -->
@endsection



