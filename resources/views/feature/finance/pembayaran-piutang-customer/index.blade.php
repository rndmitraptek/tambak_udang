@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.finance.pembayaran-piutang-customer.script')
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
                                Transaksi Pembayaran Piutang Customer
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
                                <label>Customer</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="nama_customer" name="nama_customer" placeholder="Search for..." ng-model="input.nama_customer">
                                    <div class="input-group-append">
                                        <button ng-click="cari_customer()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-12">
                            <h5>Piutang Customer</h5>
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
                                        <th style="width: 100px">Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in input.piutang">
                                        <td><input type="checkbox" ng-model="i.checked" ng-change="hitung()"></td>
                                        <td><% i.reff_trans %></td>
                                        <td><% i.no_faktur %></td>
                                        <td class="text-right"><% i.jumlah_piutang | currency:'' %></td>
                                        <td class="text-right"><% i.dibayar | currency:'' %></td>
                                        <td class="text-right"><% i.sisa | currency:'' %></td>
                                        <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-change="hitung()" ng-model="i.bayar"></td>
                                        <td><% i.tanggal_piutang %></td>
                                        <td><% i.created_by %></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right">Total Bayar</th>
                                        <th class="text-right"><% total_bayar | currency:'' %></th>
                                        <th colspan="2"></th> 
                                    </tr>
                                </tfoot>
                            </table>
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
        </div>
        <div class="col-lg-12" ng-show="form == 'detail'">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Transaksi Pembayaran Piutang Customer
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
                            <li class="m-portlet__nav-item">
                                <button ng-click="batal()" class="btn btn-danger m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-close"></i>
                                        <span>Batal Transaksi Pembayaran Piutang</span>
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
                                <input type="text" class="form-control"  ng-model="detail.no_faktur" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <label for="recipient-name" class="form-control-label">Tanggal Faktur</label>
                                <input type="text" class="form-control" ng-model="detail.tanggal_bayar" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <label for="recipient-name" class="form-control-label">Customer</label>
                                <input type="text" class="form-control" ng-model="detail.customer.nama_customer" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h5>Piutang Customer</h5>
                            <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>
                                        <th style="width: 200px">Faktur</th>
                                        <th style="width: 200px">Nomor Faktur</th>
                                        <th style="width: 200px">Tanggal Nota</th>
                                        <th style="width: 200px">Nominal Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in detail.detail">
                                        <td><% $index + 1 %></td>
                                        <td><% i.piutang_customer.reff_trans %></td>
                                        <td><% i.piutang_customer.reff_trans %></td>
                                        <td><% i.piutang_customer.tanggal_piutang %></td>
                                        <td class="text-right"><% i.nominal_piutang | currency:'' %></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total Piutang</th>
                                        <th class="text-right"><% detail.total_bayar | currency:'' %></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Proses Payment
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" ng-show="detail.transfer.length !=0">
                        <div class="col-lg-12">
                            <h5>Transfer</h5>
                            <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>
                                        <th style="width: 200px">Rekening</th>
                                        <th style="width: 200px">Waktu Transfer</th>
                                        <th style="width: 200px">Nominal Transfer</th>
                                        <th style="width: 200px">Biaya Transfer</th>
                                        <th style="width: 200px">Bank Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in detail.transfer">
                                        <td><% $index + 1 %></td>
                                        <td><% i.bank_pengirim %> <% i.atas_nama_pengirim %> <% i.no_rekening_pengirim %></td>
                                        <td><% i.waktu_transfer %></td>
                                        <td class="text-right"><% i.nominal | currency:'' %></td>
                                        <td class="text-right"><% i.biaya_transfer | currency:'' %></td>
                                        <td><% i.rekening_bank.nama_bank %> <% i.rekening_bank.no_rekening %></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" ng-show="detail.giro.length !=0">
                        <div class="col-lg-12">
                            <h5>Giro</h5>
                            <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>
                                        <th style="width: 200px">Rekening</th>
                                        <th style="width: 200px">Nomor Giro</th>
                                        <th style="width: 200px">Jatuh Tempo</th>
                                        <th style="width: 200px">Nominal Giro</th>
                                        <th style="width: 200px">Bea Materai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in detail.giro">
                                        <td><% $index + 1 %></td>
                                        <td><% i.rekening_bank.nama_bank %> <% i.rekening_bank.no_rekening %></td>
                                        <td><% i.no_giro %></td>
                                        <td><% i.jatuh_tempo%></td>
                                        <td class="text-right"><% i.nominal | currency:'' %></td>
                                        <td><% i.is_biaya_materai %></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" ng-show="detail.tunai.length !=0">
                        <div class="col-lg-12">
                            <h5>Tunai</h5>
                            <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>
                                        <th style="width: 200px">Nama Pemberi</th>
                                        <th style="width: 200px">Nama Penerima</th>
                                        <th style="width: 200px">Waktu Bayar</th>
                                        <th style="width: 200px">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in detail.tunai">
                                        <td><% $index + 1 %></td>
                                        <td><% i.nama_pemberi %></td>
                                        <td><% i.nama_penerima %></td>
                                        <td><% i.tanggal_bayar %></td>
                                        <td class="text-right"><% i.nominal | currency:'' %></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
                            <label for="recipient-name" class="col-4 col-form-label">Kode Customer</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="input.kode_customer" readonly>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Customer</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="input.nama_customer" readonly>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="exampleTextarea" class="col-4 col-form-label">Alamat Customer</label>
                            <div class="col-8">
                                <textarea class="form-control" ng-model="input.alamat_customer" rows="4" readonly></textarea>
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
                <div class="row" ng-show="input.metode_bayar=='TRANSFER'">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label class="col-4 col-form-label">Masuk Ke Rekening</label>
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
                                <input type="text" class="form-control" id='waktu_transfer' name='waktu_transfer' ng-model="form_transfer.waktu_transfer" >
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
                        <label class="m--font-boldest">Transfer Dari</label>
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
                        <div class="row">
                            <div class="col-lg-12 " style="text-align: right;">
                                <button type="button" ng-click="handleClickTambahPembayaran()" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-angle-double-down"></i>
                                        <span>Tambah Pembayaran</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h5>Transfer</h5>
                        <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th style="width: 200px">Rekening</th>
                                    <th style="width: 200px">Waktu Transfer</th>
                                    <th style="width: 200px">Nominal Transfer</th>
                                    <th style="width: 200px">Biaya Transfer</th>
                                    <th style="width: 200px">Bank Tujuan</th>
                                    <th style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="i in input.transfer" ng-init="total_transfer = (total_transfer || 0) + i.nominal">
                                    <td><% $index + 1 %></td>
                                    <td><% i.bank_pengirim %> <% i.atas_nama_pengirim %> <% i.no_rekening_pengirim %></td>
                                    <td><% i.waktu_transfer %></td>
                                    <td class="text-right"><% i.nominal | currency:'' %></td>
                                    <td class="text-right"><% i.biaya_transfer | currency:'' %></td>
                                    <td><% i.rekening %></td>
                                    <td><button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View" style="height: 25px;"><i class="la la-remove m--font-danger"></i></button></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Transfer</th>
                                    <th class="text-right"><% getTotalTransfer() | currency:'' %></th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row" ng-show="input.metode_bayar=='GIRO'">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label class="col-4 col-form-label">Rekening</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="text" class="form-control"  placeholder="Search for..." ng-model="form_giro.rekening">
                                    <div class="input-group-append">
                                        <button ng-click="cari_rekening_giro()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">No Giro</label>
                            <div class="col-8">
                                <input type="text" class="form-control" id='no_giro' name='no_giro' ng-model="form_giro.no_giro" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Terima Giro</label>
                            <div class="col-8">
                                <input type="text" class="form-control general_datepicker" id="terima_giro" ng-model="form_giro.terima_giro" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Jatuh Tempo</label>
                            <div class="col-8">
                                <input type="text" class="form-control general_datepicker" id="jatuh_tempo" ng-model="form_giro.jatuh_tempo" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Nominal</label>
                            <div class="col-8">
                                <input type="text" class="form-control text-right" ng-change="hitung_biaya_materai()" input-currency ng-model="form_giro.nominal" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">+ Materai</label>
                            <div class="col-8">
                                <input type="text" class="form-control text-right" input-currency ng-model="form_giro.nominal_materai" readonly>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Selisih Bayar</label>
                            <div class="col-8">
                                <input type="text" class="form-control text-right" input-currency ng-model="form_giro.selisih_bayar" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="m-checkbox" style="margin-top: 10px;">
                                <input name="is_biaya_materai" ng-change="hitung_biaya_materai()" ng-model="form_giro.is_biaya_materai" type="checkbox"> Bea Materai
                                <span></span>
                            </label>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Biaya Materai</label>
                            <div class="col-8">
                                <input type="text" class="form-control text-right" ng-change="hitung_biaya_materai()" input-currency ng-model="form_giro.biaya_materai" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 " style="text-align: right;">
                                <button type="button" ng-click="handleClickTambahPembayaranGiro()" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-angle-double-down"></i>
                                        <span>Tambah Pembayaran</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h5>Giro</h5>
                        <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th style="width: 200px">Rekening</th>
                                    <th style="width: 200px">Nomor Giro</th>
                                    <th style="width: 200px">Jatuh Tempo</th>
                                    <th style="width: 200px">Nominal Giro</th>
                                    <th style="width: 200px">Bea Materai</th>
                                    <th style="width:40px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="i in input.giro">
                                    <td><% $index + 1 %></td>
                                    <td><% i.rekening %></td>
                                    <td><% i.no_giro %></td>
                                    <td><% i.jatuh_tempo%></td>
                                    <td class="text-right"><% i.nominal | currency:'' %></td>
                                    <td><% i.is_biaya_materai %></td>
                                    <td><button type="button" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View" style="height: 25px;"><i class="la la-remove m--font-danger"></i></button></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total Giro</th>
                                    <th class="text-right"><% getTotalGiro() | currency:'' %></th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row" ng-show="input.metode_bayar=='TUNAI'">
                    <div class="col-lg-12">
                        <h5>Tunai</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Nama Pemberi</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="form_tunai.nama_pemberi" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Nama Penerima</label>
                            <div class="col-8">
                                <input type="text" class="form-control" ng-model="form_tunai.nama_penerima" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Waktu Bayar</label>
                            <div class="col-8">
                                <input type="text" class="form-control general_datepicker" id='waktu_bayar_tunai' name='waktu_bayar_tunai' ng-model="form_tunai.waktu_bayar" >
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-bottom:0px!important">
                            <label for="recipient-name" class="col-4 col-form-label">Nominal</label>
                            <div class="col-8">
                                <input type="text" class="form-control text-right" input-currency ng-model="form_tunai.nominal" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary " data-dismiss="modal">Keluar</button>
                <button type="button" ng-click="simpan_pembayaran_hutang()" class="btn btn-primary m-btn--icon">
                    <span>
                        <i class="la la-save"></i>
                        <span>Simpan Pembayaran Hutang</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!--end::Modal-->
<look-up-table
      lookup-id="lookup_customer"
      ajax-url="{{ route('finance.pembayaran_piutang_customer.customer') }}"
      columns="customerColumns"
      page-length="8"
      on-select="selectCustomer(row)">
</look-up-table>
<look-up-table
      lookup-id="lookup_rekening"
      ajax-url="{{ route('finance.pembayaran_piutang_customer.rekening') }}"
      columns="rekeningColumns"
      page-length="8"
      on-select="selectRekening(row)">
</look-up-table>
<look-up-table
      lookup-id="lookup_rekening_giro"
      ajax-url="{{ route('finance.pembayaran_piutang_customer.rekening') }}"
      columns="rekeningColumns"
      page-length="8"
      on-select="selectRekeningGiro(row)">
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



