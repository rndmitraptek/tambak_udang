@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.penggunaan-pakan.script')
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
                                Penggunaan Pakan
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Buat Penggunaan Pakan</span>
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
                                Buat Penggunaan Pakan
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
                                        <span>Kembali ke List </span>
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
                                    <label for="recipient-name" class="form-control-label">No Penggunaan Pakan</label>
                                    <input type="text" class="form-control" id="no_penggunaan" nama="no_penggunaan" ng-model="input.no_penggunaan">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Penggunaan Pakan</label>
                                    <input type="text" class="form-control general_datepicker" id="tanggal_penggunaan" name="tanggal_penggunaan" ng-model="input.tanggal_penggunaan" >
                                </div>
                                {{-- <div class="form-group m-form__group">
                                    <label for="waktu">Waktu</label>
                                    <input type="time" class="form-control" id="waktu" ng-model="input.waktu" name="waktu" required>
                                </div> --}}
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="uuid_lokasi">Lokasi</label>
                                    <select class="form-control" id="uuid_lokasi" ng-change="get_siklus()" ng-model="input.uuid_lokasi" name="uuid_lokasi" required>
                                        <option  value="" >Pillih Lokasi</option>
                                        <option ng-repeat="x in lokasi" value="<% x.uuid %>" ><% x.nama_lokasi %></option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="uuid_siklus">Siklus</label>
                                    <select class="form-control" id="uuid_siklus" ng-change="get_petak()" ng-model="input.uuid_siklus" name="uuid_siklus" required>
                                        <option  value="" >Pillih Siklus</option>
                                        <option ng-repeat="x in siklus" value="<% x.uuid %>" ><% x.nama_siklus %></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="exampleTextarea">Keterangan</label>
                                    <textarea class="form-control" ng-model="input.keterangan" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="button" ng-click="add_petak()" class="btn btn-outline-primary btn-sm m-btn m-btn--icon mb-2">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>PETAK</span>
                                    </span>
                                </button>
                                <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info">
                                    <thead>
                                        <tr>
                                            <th>Kode Pakan</th>
                                            <th>Nama Pakan</th>
                                            <th>Blok</th>
                                            <th>Petak</th>
                                            <th>Jumlah (Kg)</th>
                                            <th>Harga Per Kg</th>
                                            <th>Subtotal</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in detail">
                                            <td><% item.kode_pakan %></td>
                                            <td><% item.nama_pakan %></td>
                                            <td><% item.nama_blok %></td>
                                            <td><% item.nama_petak %></td>
                                            <td class="text-right"><% item.jumlah | currency:'' %></td>
                                            <td class="text-right"><% item.harga | currency:'' %></td>
                                            <td class="text-right"><% item.subtotal | currency:'' %></td>
                                            <td ><button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" ng-click="remove_detail($index)" title="remove" style="height: 25px;"><i class="la la-remove m--font-danger"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-right">Total</th>
                                            {{-- <th class="text-right"><% total_jumlah | currency:'' %></th>
                                            <th class="text-right"><% total_harga | currency:'' %></th>  --}}
                                            <th class="text-right"><% grand_total | currency:'' %></th> 
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

<!--begin::Modal-->
<div class="modal fade" id="m_petak" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pillih Petak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label for="id_petak">Petak</label>
                    <select class="form-control" id="id_petak" ng-model="input.id_petak" ng-change="selected_petak()" name="id_petak" required>
                        <option  value="" >--Pillih Petak--</option>
                        <option ng-repeat="x in petak" value="<% x.uuid %>" ><% x.blok %> - <% x.petak %></option>
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label for="id_pakan">Pakan</label>
                    <select class="form-control"
                            id="id_pakan"
                            ng-model="input.id_pakan"
                            name="id_pakan"
                            ng-change="selected_pakan()"
                            required>
                        <option value="">--Pilih pakan--</option>
                        <option ng-repeat="x in pakan" value="<% x.uuid %>" ><% x.kode_pakan %> - <% x.nama_pakan %> - Stok => <% x.stok %></option>
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label for="stok">Stok (Kg)</label>
                    <input type="text" class="form-control text-right" input-currency name="stok" id="stok" ng-model="stok" readonly style="background-color: rgb(208, 205, 205);">
                </div>
                <div class="form-group m-form__group">
                    <label for="jumlah">Jumlah Penggunaan (Kg)</label>
                    <input type="text" class="form-control text-right" input-currency name="jumlah" id="jumlah" ng-model="jumlah" ng-change="hitungSubtotal()">
                </div>
                <div class="form-group m-form__group">
                    <label for="harga">Harga Per Kg</label>
                    <input type="text" class="form-control text-right" input-currency name="harga" id="harga" ng-model="harga" ng-change="hitungSubtotal()" readonly style="background-color: rgb(208, 205, 205);">
                </div>
                <div class="form-group m-form__group">
                    <label for="subtotal">Subtotal</label>
                    <input type="text" class="form-control text-right" input-currency name="subtotal" id="subtotal" ng-model="subtotal" readonly style="background-color: rgb(208, 205, 205);">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" ng-click="add_detail()" class="btn btn-primary">Simpan</button>
            </div>
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



