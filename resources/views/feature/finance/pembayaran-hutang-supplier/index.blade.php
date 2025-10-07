@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.finance.pembayaran-hutang-supplier.script')
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
                                Transaksi Pembayaran Hutang Supplier
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-map-marker"></i>
                                        <span>Buat Pembayaran</span>
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
                                Buat Pembayaran
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button type="button" ng-click="kembali()" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>Kembali ke List Pembayaran</span>
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
                                <label for="recipient-name" class="form-control-label">Nomor Faktur</label>
                                <input type="text" class="form-control" id="no_faktur" name="no_faktur" ng-model="input.no_faktur">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <label for="recipient-name" class="form-control-label">Tanggal Faktur</label>
                                <input type="text" class="form-control general_datepicker" id="tanggal_bayar" name="tanggal_bayar" ng-model="input.tanggal_bayar">
                            </div>
                        </div>
                        <div class="col-lg-4">
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
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-12">
                            <h5>Hutang Supplier</h5>
                            <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>
                                        <th style="width: 200px">Faktur</th>
                                        <th style="width: 200px">Nomor Faktur</th>
                                        <th style="width: 200px">Nominal</th>
                                        <th style="width: 200px">Sudah di Bayar</th>
                                        <th style="width: 200px">Belum di Bayar</th>
                                        <th style="width: 200px">Jumlah Bayar</th>
                                        <th style="width: 200px">Tanggal Nota</th>
                                        <th style="width: 40px">Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in input.hutang">
                                        <td><input type="checkbox" ng-model="i.checked" ng-change="hitung()"></td>
                                        <td><% i.reff_trans %></td>
                                        <td><% i.no_faktur %></td>
                                        <td class="text-right"><% i.jumlah_hutang | currency:'' %></td>
                                        <td class="text-right"><% i.dibayar | currency:'' %></td>
                                        <td class="text-right"><% i.sisa | currency:'' %></td>
                                        <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-change="hitung()" ng-model="i.bayar" ng-change="hitung_hutang()"></td>
                                        <td><% i.tanggal_hutang %></td>
                                        <td><% i.created_by %></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right">Total Hutang</th>
                                        <th class="text-right"><% total_hutang | currency:'' %></th>
                                        <th colspan="2"></th> 
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-12">
                            <h5>Piutang Supplier</h5>
                            <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>
                                        <th style="width: 200px">Faktur</th>
                                        <th style="width: 200px">Nomor Faktur</th>
                                        <th style="width: 200px">Nominal Piutang</th>
                                        <th style="width: 200px">Tanggal Nota</th>
                                        <th style="width: 40px">Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in input.piutang">
                                        <td><input type="checkbox" ng-model="i.checked" ng-change="hitung()"></td>
                                        <td><% i.reff_trans %></td>
                                        <td><% i.no_faktur %></td>
                                        <td class="text-right"><% i.jumlah_piutang | currency:'' %></td>
                                        <td><% i.tanggal_piutang %></td>
                                        <td><% i.created_by %></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total Piutang</th>
                                        <th class="text-right"><% total_piutang | currency:'' %></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-form__group row">
                                <label for="example-email-input" class="col-4 col-form-label m--font-boldest">Total Bayar</label>
                                <div class="col-8">
                                    <input class="form-control m-input m--font-boldest text-right" type="text" input-currency ng-model="total_bayar" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 " style="text-align: right;">
                            <button type="button" ng-click="handleClickProsesPayment()" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                <span>
                                    <i class="la la-money"></i>
                                    <span>Proses Payment</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
 {{-- Modal Proses Bayar --}}
<div class="modal fade" id="m_proses_bayar" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Kode Supplier</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="input.kode_supplier" readonly>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Supplier</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="input.nama_supplier" readonly>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="exampleTextarea" class="col-4 col-form-label">Alamat Supplier</label>
                            <div class="col-8">
                                <textarea class="form-control" ng-model="input.alamat_supplier" rows="4" readonly></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Total Bayar</label>
                            <div class="col-8">
                                <input type="text" class="form-control m--font-boldest text-right" input-currency ng-model="total_bayar" readonly>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="exampleSelect1" class="col-4 col-form-label">Metode Bayar</label>
                            <div class="col-8">
                                <select class="form-control" id="metode_bayar" name="metode_bayar" ng-model="input.metode_bayar">
                                    <option value="TRANSFER">TRANSFER</option>
                                    <option value="GIRO">GIRO</option>
                                    <option value="TUNAI">TUNAI</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="exampleTextarea" class="col-4 col-form-label">Keterangan</label>
                            <div class="col-8">
                                <textarea class="form-control" ng-model="input.keterangan" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label class="col-4 col-form-label">Dari Rekening</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="text" class="form-control"  placeholder="Search for..." ng-model="form_transfer.rekening">
                                    <div class="input-group-append">
                                        <button ng-click="cari_rekening()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Waktu Transfer</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="form_transfer.waktu_transfer" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Nominal</label>
                            <div class="col-8">
                                <input type="text" class="form-control text-right" input-currency ng-model="form_transfer.nominal" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Biaya Transfer</label>
                            <div class="col-8">
                                <input type="text" class="form-control text-right" input-currency ng-model="form_transfer.biaya_transfer" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="m--font-boldest">Transfer Ke</label>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Nama Bank</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="form_transfer.bank_pengirim" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Pemilik Rekening</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="form_transfer.atas_nama_pengirim" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Nomor Rekening</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="form_transfer.no_rekening_pengirim" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="submit" class="btn btn-primary">Simpan Pembayaran Piutang</button>
            </div>
        </div>
    </div>
</div>


<!--end::Modal-->
<look-up-table
      lookup-id="lookup_supplier"
      ajax-url="{{ route('finance.pembayaran_hutang_supplier.supplier') }}"
      columns="supplierColumns"
      page-length="8"
      on-select="selectSupplier(row)">
</look-up-table>
<look-up-table
      lookup-id="lookup_rekening"
      ajax-url="{{ route('finance.pembayaran_hutang_supplier.rekening') }}"
      columns="rekeningColumns"
      page-length="8"
      on-select="selectRekening(row)">
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



