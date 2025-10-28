@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.panen.script')
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
                                Panen
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Transaksi Panen</span>
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
                                Buat Panen
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
                                        <span>Kembali ke List Panen</span>
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
                                    <label for="recipient-name" class="form-control-label">No Panen</label>
                                    <input type="text" class="form-control" id="no_panen" name="no_panen" ng-model="input.no_panen">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Panen</label>
                                    <input type="text" class="form-control general_datepicker" id="tanggal_panen" name="tanggal_panen" ng-model="input.tanggal_panen">
                                </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Siklus</label>
                                    <select class="form-control" id="uuid_siklus" name="uuid_siklus" ng-change="get_blok()" ng-model="input.uuid_siklus">
                                        <option value="">Pillih Siklus</option>
                                        <option ng-repeat="x in siklus" value="<% x.uuid %>"><% x.nama_siklus %></option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Blok</label>
                                    <select class="form-control" id="uuid_blok" name="uuid_blok" ng-change="get_petak()" ng-model="input.uuid_blok">
                                        <option value="">Pillih Siklus</option>
                                        <option ng-repeat="x in blok" value="<% x.uuid %>"><% x.nama_blok %></option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Petak</label>
                                    <select class="form-control" id="uuid_petak" name="uuid_petak" ng-model="input.uuid_petak">
                                        <option value="">Pillih Petak</option>
                                        <option ng-repeat="x in petak" value="<% x.uuid %>"><% x.nama_petak %></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Jenis Panen</label>
                                    <select class="form-control" id="jenis_panen" name="jenis_panen" ng-model="input.jenis_panen">
                                        <option value="PARTIAL">PARTIAL</option>
                                        <option value="FINAL">FINAL</option>
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
                                <button type="button" ng-click="handleClickPenjualan()" class="btn btn-outline-primary btn-sm m-btn m-btn--icon mb-2">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Penjualan</span>
                                    </span>
                                </button>
                                <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info">
                                    <thead>
                                        <tr>
                                            <th style="width: 400px">Nama Customer</th>
                                            <th style="width: 200px">Metode Pembayaran</th>
                                            <th style="width: 200px">Item</th>
                                            <th style="width: 200px">Harga</th>
                                            <th style="width: 200px">Jumlah</th>
                                            <th style="width: 200px">Subtotal</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="i in detail">
                                            <td><% i.nama_customer %></td>
                                            <td>
                                                <select style="width: 200px" id="uuid_payment_method" name="uuid_payment_method" ng-model="i.uuid_payment_method">
                                                    <option value="">Pillih Payment</option>
                                                    <option ng-repeat="x in payment_method" value="<% x.uuid %>"><% x.payment_method %></option>
                                                </select>
                                            </td>
                                            <td>
                                                <select style="width: 200px" id="uuid_item" name="uuid_item" ng-model="i.uuid_item">
                                                    <option value="">Pillih Item</option>
                                                    <option ng-repeat="x in item" value="<% x.uuid %>"><% x.nama_item %></option>
                                                </select>
                                            </td>
                                            <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-model="i.harga" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-model="i.jumlah" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-model="i.subtotal" readonly></td>
                                            <td ><button ng-click="remove_detail($index,item)" type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View" style="height: 25px;"><i class="la la-remove m--font-danger"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">Total</th>
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
      lookup-id="lookup_customer"
      ajax-url="{{ route('panen.get_customer') }}"
      columns="customerColumns"
      page-length="8"
      on-select="selectCustomer(row)">
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



