@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.finance.retur-pakan.script')
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
                                Retur Pakan
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Buat Retur Pakan</span>
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
                                Buat Retur Pakan
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
                                    <label for="recipient-name" class="form-control-label">No Retur Pakan</label>
                                    <input type="text" class="form-control" id="no_retur" nama="no_retur" ng-model="input.no_retur">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Retur</label>
                                    <input type="text" class="form-control general_datepicker" id="tanggal_retur" name="tanggal_retur" ng-model="input.tanggal_retur" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label>Pillih Pembelian</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" ng-model="input.no_pembelian" name="no_pembelian" placeholder="Search for...">
                                        <div class="input-group-append">
                                            <button ng-click="cari_pembelian()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Lokasi</label>
                                    <input type="text" class="form-control" ng-model="input.nama_lokasi" readonly>
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
                                <button type="button" ng-click="add_pakan()" class="btn btn-outline-primary btn-sm m-btn m-btn--icon mb-2">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>PAKAN</span>
                                    </span>
                                </button>
                                <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info">
                                    <thead>
                                        <tr>
                                            <th>Kode Pakan</th>
                                            <th>Nama Pakan</th>
                                            <th>Harga Per Kg</th>
                                            <th>Jumlah Retur (Kg)</th>
                                            <th>Subtotal</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in detail">
                                            <td><% item.kode_pakan %></td>
                                            <td><% item.nama_pakan %></td>
                                            <td class="text-right"><input style="width: 120px; background-color:rgb(223, 216, 216);" class="text-right" type="text" input-currency ng-model="item.harga_per_kg" readonly ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 90px;" class="text-right" type="text" input-currency ng-model="item.jumlah_retur" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 120px; background-color:rgb(223, 216, 216);" class="text-right" type="text" input-currency ng-model="item.subtotal" readonly></td>
                                            <td ><button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="remove" style="height: 25px;"><i class="la la-remove m--font-danger"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total</th>
                                            <th class="text-right"><% total_jumlah | currency:'' %></th>
                                            <th class="text-right"><% total_harga | currency:'' %></th> 
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
<div class="modal fade" id="m_pakan" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pillih Pakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Pakan</th>
                            <th>Nama Pakan</th>
                            <th>Sisa Stok (Kg)</th>
                            <th>Harga Per Kg</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat='item in data_pakan'>
                            <td><input ng-model="item.checked" type="checkbox"></td>
                            <td><% item.kode_pakan %></td>
                            <td><% item.nama_pakan %></td>
                            <td><% item.jumlah | currency:'' %></td>
                            <td><% item.harga | currency:'' %></td>
                            <td><% item.subtotal | currency:'' %></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" ng-click="add_detail()" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!--end::Modal-->

<look-up-table
    lookup-id="lookup_pembelian"
    ajax-url="{{ route('finance.retur_pakan.get_pembelian') }}"
    columns="pembelianColumns"
    page-length="8"
    on-select="selectPembelian(row)">
</look-up-table>

<look-up-table
      lookup-id="lookup_benur"
      ajax-url="{{ route('finance.retur_pakan.get_benur') }}"
      columns="benurColumns"
      page-length="8"
      on-select="selectBenur(row)">
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



