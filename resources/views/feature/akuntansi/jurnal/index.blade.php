@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.akuntansi.jurnal.script')
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
                                Jurnal Umum
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="history()" class="btn btn-warning m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-edit"></i>
                                        <span>History Input Jurnal</span>
                                    </span>
                                </button>
                            </li>
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Tambah Jurnal</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-row align-items-end">
                        <!-- Start Date -->
                        <div class="col-md-4 mb-3">
                            <label for="startDate">Start Date</label>
                            <div class="input-group date">
                                <input type="text" id="startDate" name="start_date" class="form-control" placeholder="yyyy-mm-dd" autocomplete="off"/>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-4 mb-3">
                            <label for="endDate">End Date</label>
                            <div class="input-group date">
                                <input type="text" id="endDate" name="end_date" class="form-control" placeholder="yyyy-mm-dd" autocomplete="off"/>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Search -->
                        <div class="col-md-4 mb-3">
                            <button type="button" ng-click="get_jurnal_umum()" class="btn btn-primary btn-block">
                                <i class="la la-search"></i> Search
                            </button>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h2>Jurnal Umum</h2>
                            <h4>Periode <% start_date %> S/D <% end_date %></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group -form__group">
                                <label for="message-text" class="form-control-label" >Cari Keterangan</label>
                                <input type="text" class="form-control" id="cari_keterangan" nama="cari_keterangan" ng-model="cari_keterangan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-sm m-table m-table--head-bg-brand">
                                <thead>
                                    <tr>
                                        <th style="width: 150px">Tanggal</th>
                                        <th style="width: 200px">Nomor Bukti</th>
                                        <th style="width: 130px">Kode Akun</th>
                                        <th style="width: 300px">Nama Akun</th>
                                        <th >Keterangan</th>
                                        <th style="width: 200px" class="text-center">debit</th>
                                        <th style="width: 200px" class="text-center">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in jurnal_umum | filter:{keterangan: cari_keterangan}" >
                                        <td><a ng-show="i.no_bukti!=jurnal_umum[$index-1].no_bukti"><% i.tanggal %></a></td>
                                        <td><a ng-show="i.no_bukti!=jurnal_umum[$index-1].no_bukti"><% i.no_bukti %></a></td>
                                        <td><a ng-show="i.debit==0" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<% i.kode_coa %></a><a ng-show="i.debit!=0"><% i.kode_coa %></a></td>
                                        <td><a ng-show="i.debit==0" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<% i.nama_coa %></a><a ng-show="i.debit!=0"><% i.nama_coa %></a></td>
                                        <td><a ng-show="i.no_bukti!=jurnal_umum[$index-1].no_bukti"><% i.keterangan %></a></td>
                                        <td class="text-right"><a ng-show="i.debit!=0"><% i.debit | currency:'' %></a></td>
                                        <td class="text-right"><a ng-show="i.kredit!=0"><% i.kredit | currency:'' %></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                Input Jural Umum
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="simpan()" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-save"></i>
                                        <span>Simpan Jurnal</span>
                                    </span>
                                </button>
                            </li>
                            <li class="m-portlet__nav-item">
                                <button ng-click="kembali()" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>Kembali ke List Jurnal</span>
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
                                    <label for="recipient-name" class="form-control-label">No Bukti</label>
                                    <input type="text" class="form-control" id="no_bukti" name="no_bukti" ng-model="input.no_bukti">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Jurnal</label>
                                    <input type="text" class="form-control general_datepicker" id="tanggal" name="tanggal" ng-model="input.tanggal">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group -form__group">
                                    <label for="message-text" class="form-control-label" >Keterangan</label>
                                    <textarea class="form-control" id="keterangan" nama="keterangan" ng-model="input.keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="button" ng-click="handleClickDetail()" class="btn btn-outline-primary btn-sm m-btn m-btn--icon mb-2">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Detail</span>
                                    </span>
                                </button>
                                <table class="table table-sm table-striped- table-bordered table-hover table-checkable">
                                    <thead>
                                        <tr>
                                            <th style="width: 150px">Kode COA</th>
                                            <th style="width: 400px">Nama COA</th>
                                            <th style="width: 200px">debit</th>
                                            <th style="width: 200px">Kredit</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="i in detail">
                                            <td><% i.kode_coa %></td>
                                            <td><% i.nama_coa %></td>
                                            <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-model="i.debit" ng-change="hitung()"></td>
                                            <td class="text-right"><input style="width: 200px" class="text-right" type="text" input-currency ng-model="i.kredit" ng-change="hitung()"></td>
                                            <td ><button type="button" ng-click="remove_detail($index,i)" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="hapus" style="height: 25px;"><i class="la la-remove m--font-danger"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-right">Total</th>
                                            <th class="text-right"><% total_debit | currency:'' %></th>
                                            <th class="text-right"><% total_kredit | currency:'' %></th> 
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
        <div class="col-lg-12" ng-show="form == 'history'">
        <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                History Jural Umum
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="kembali()" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>Kembali ke List Jurnal</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info" id="viewtabel"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<look-up-table
      lookup-id="lookup_coa"
      ajax-url="{{ route('akuntansi.jurnal.get_coa') }}"
      columns="coaColumns"
      page-length="8"
      on-select="selectCoa(row)">
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



