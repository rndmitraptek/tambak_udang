@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.siklus.script')
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
                                Master Siklus
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button id="btnTambah" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Tambah Siklus</span>
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
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="m_create" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 80vw;">
        <div class="modal-content">
            <form id="formSiklus">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Siklus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="uuid" name="uuid">
                    <div class="row">
                        <div class="col-lg-4">
                            <h4>Info Siklus</h4>
                            <div class="form-group m-form__group">
                                <label for="exampleSelect1">Nama Lokasi</label>
                                <select class="form-control" id="lokasi_id" name="lokasi_id" ng-model="lokasi" required>
                                    <option value="">--Pilih Lokasi--</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Nama Siklus</label>
                                <input type="text" class="form-control" id="nama_siklus" name="nama_siklus" required>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Tanggal Mulai</label>
                                <input type="text" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Tanggal Selesai</label> <!--Optional-->
                                <input type="text" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="form-control-label" >Catatan</label>
                                <textarea class="form-control" id="catatan" name="catatan"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-8" style="border-left: 1px solid #ccc;">
                            <h4>Petak Dalam Siklus</h4>
                            <table id="viewtabelpetak" class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info" ng-if="lokasi != ''">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAllPetak"></th>
                                        <th>Blok</th>
                                        <th>Petak</th>
                                        <th>Luas</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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



