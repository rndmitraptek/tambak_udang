@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.penaburan-benur.script')
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
                                Penaburan Benur
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-map-marker"></i>
                                        <span>Buat Penaburan Benur</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                {{-- <h1><% tes %></h1> --}}
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="viewtabel">
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
                                Buat Penaburan Benur
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
                                    <label for="recipient-name" class="form-control-label">No Penaburan Benur</label>
                                    <input type="text" class="form-control" id="no_penaburan_benur" nama="no_penaburan_benur" ng-model="input.no_penaburan_benur">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Penaburan</label>
                                    <input type="text" class="form-control general_datepicker" id="tanggal_penaburan" name="tanggal_penaburan" ng-model="input.tanggal_penaburan" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label>Pillih PO</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" ng-model="input.no_po" name="no_po" placeholder="Search for..." readonly>
                                        <div class="input-group-append">
                                            <button ng-disabled="detail.length!=0" ng-click="cari_po()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">supplier</label>
                                    <input type="text" class="form-control" ng-model="input.nama_supplier" readonly>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Lokasi</label>
                                    <input type="text" class="form-control" ng-model="input.nama_lokasi" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Siklus</label>
                                    <input type="text" class="form-control" ng-model="input.nama_siklus" readonly>
                                </div>
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
                                <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                                    <thead>
                                    <tr>
                                            <th colspan="4" class="text-center">Item</th>
                                            <th colspan="3" class="text-center">Bruto</th>
                                            <th colspan="3" class="text-center">Neto</th>
                                            <th colspan="3" class="text-center">Actual</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                        <tr>
                                            <th>Blok</th>
                                            <th>Petak</th>
                                            <th>Kode Benur</th>
                                            <th>Jenis Benur</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in detail">
                                            <td><% item.blok %></td>
                                            <td><% item.petak %></td>
                                            <td>
                                                <button type="button" ng-class="{'btn btn-outline-brand btn-sm':item.kode_benur != '','btn btn-outline-danger btn-sm':item.kode_benur == ''}" class="btn btn-outline-brand btn-sm" style="padding: 3px;" ng-click="change_benur($index)"><% item.kode_benur=='' ? 'pillih kode benur' : item.kode_benur%></button>
                                            </td>
                                            <td><% item.jenis_benur %></td>
                                            <td class="text-right"><input style="width: 60px" class="text-right" type="text" input-currency ng-model="item.harga_bruto" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 90px;" class="text-right" type="text" input-currency ng-model="item.jumlah_bruto" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 120px;" class="text-right" type="text" input-currency ng-model="item.subtotal_bruto" readonly></td>
                                            <td class="text-right"><input style="width: 60px" class="text-right" type="text" input-currency ng-model="item.harga_neto" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 90px;"  class="text-right" type="text" input-currency ng-model="item.jumlah_neto" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 120px;" class="text-right" type="text" input-currency ng-model="item.subtotal_neto" readonly></td>
                                            <td class="text-right"><input style="width: 60px" class="text-right" type="text" input-currency ng-model="item.harga_actual" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 90px;"  class="text-right" type="text" input-currency ng-model="item.jumlah_actual" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 120px;" class="text-right" type="text" input-currency ng-model="item.subtotal_actual" readonly></td>
                                            <td ><button ng-click="remove_detail($index,item)" type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="remove" style="height: 25px;"><i class="la la-remove m--font-danger"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total</th>
                                            <th class="text-right"><% total_jumlah_bruto | currency:'' %></th>
                                            <th class="text-right"><% total_harga_bruto | currency:'' %></th> 
                                            <th  class="text-right">Total</th>
                                            <th class="text-right"><% total_jumlah_neto | currency:'' %></th>
                                            <th class="text-right"><% total_harga_neto | currency:'' %></th> 
                                            <th  class="text-right">Total</th>
                                            <th class="text-right"><% total_jumlah_actual | currency:'' %></th>
                                            <th class="text-right"><% total_harga_actual | currency:'' %></th>
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
                <table class="table table-striped- table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Blok</th>
                            <th>Petak</th>
                            <th>Luas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat='item in data_petak | filter:{is_add:false}' >
                            <td><input ng-model="item.checked" type="checkbox"></td>
                            <td><% item.blok %></td>
                            <td><% item.petak %></td>
                            <td><% item.luas %></td>
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
      lookup-id="lookup_po"
      ajax-url="{{ route('finance.penaburan.get_po') }}"
      columns="poColumns"
      page-length="8"
      on-select="selectPo(row)">
</look-up-table>

<look-up-table
      lookup-id="lookup_benur"
      ajax-url="{{ route('finance.penaburan.get_benur') }}"
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



