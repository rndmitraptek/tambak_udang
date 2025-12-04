@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.akuntansi.buku_besar.script')
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
        <div class="col-lg-12">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Buku Besar
                            </h3>
                        </div>
                    </div>
                    {{-- <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Tambah Jurnal</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div> --}}
                </div>
                <div class="m-portlet__body">
                    <div class="form-row align-items-end">
                        <!-- Start Date -->
                        <div class="col-md-2 mb-3">
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
                        <div class="col-md-2 mb-3">
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
                        <div class="col-md-3 form-group m-form__group">
                            <label>Kode Akun</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="akun" name="akun" placeholder="Search for..." ng-model="nama_coa">
                                <div class="input-group-append">
                                    <button ng-click="handleClickCoa()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <!-- Tombol Search -->
                        <div class="col-md-2 mb-3">
                            <button type="button" ng-click="get_buku_besar()" class="btn btn-primary btn-block">
                                <i class="la la-search"></i> Search
                            </button>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h2>Buku Besar</h2>
                            <h3><% kode_coa %> - <% nama_coa %></h3>
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
                                        <th style="width: 150px">Kode COA</th>
                                        <th >Keterangan</th>
                                        <th style="width: 200px" class="text-center">debit</th>
                                        <th style="width: 200px" class="text-center">Kredit</th>
                                        <th style="width: 250px" class="text-center">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in buku_besar | filter:{keterangan: cari_keterangan}">
                                        <td><% i.tanggal %></td>
                                        <td><% i.no_bukti %></td>
                                        <td><% i.kode_coa %></td>
                                        <td><% i.keterangan %></td>
                                        <td class="text-right"><a ng-show="i.debit!=0"><% i.debit | currency:'' %></a></td>
                                        <td class="text-right"><a ng-show="i.kredit!=0"><% i.kredit | currency:'' %></a></td>
                                        <td class="text-right"><a><% i.saldo | currency:'' %></a></td>
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



