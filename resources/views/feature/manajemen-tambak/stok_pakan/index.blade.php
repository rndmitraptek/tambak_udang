@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.stok_pakan.script')
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
<style>
    .text-right {
        text-align: right !important;
    }
</style>

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
                                Stok Pakan
                            </h3>
                        </div>
                    </div>
                    
                </div>
                <div class="m-portlet__body">
                {{-- <h1><% tes %></h1> --}}
                    <!--begin: Datatable -->
                    <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info" id="viewtabel">
                        <thead>
                            <tr>
                                <th>Lokasi</th>
                                <th>Kode Pakan</th>
                                <th>Nama Pakan</th>
                                <th>Jenis Pakan</th>
                                <th>Merk</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--begin::Modal-->
<!-- Modal Detail History -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 90% !important;"> <!-- modal besar -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">History Kartu Stok</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table id="historyTable" class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info" style="width:100%">
              <thead>
                  <tr>
                      <th>Tanggal</th>
                      <th>Nama Pakan</th>
                      <th>Lokasi</th>
                      <th>Transaksi</th>
                      <th>No Ref</th>
                      <th>Stok Awal</th>
                      <th>Stok Masuk</th>
                      <th>Stok Keluar</th>
                      <th>Stok Akhir</th>
                  </tr>
              </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<!--end::Modal-->
@endsection

@section('js')
<!--begin::Page Vendors -->
<script src="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="{{ url('/') }}/template/assets/src/jquery.validate.min.js"></script>
<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}

<!--end::Page Resources -->
@endsection



