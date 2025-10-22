@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/template/assets/src/select2.min.css" rel="stylesheet" type="text/css"/>
@endsection
@section('ctrl')
@include('feature.akuntansi.setup-biaya.script')
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
                                Master Setup Biaya
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button id="btnTambah" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Tambah Setup Biaya</span>
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
                        <thead>
                            <tr>
                                <th>Kode Biaya</th>
                                <th>Nama Biaya</th>
                                <th>Kelompok Biaya</th>
                                <th>Periode Biaya</th>
                                <th>COA</th>
                                <th>Default Nominal</th>
                                <th>Catatan</th>
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
<div class="modal fade" id="m_create" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formSetupBiaya">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Setup Biaya</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="uuid" name="uuid">
                    <div class="form-group">
                        <label>Kode Biaya</label>
                        <input type="text" class="form-control" id="kode_biaya" name="kode_biaya" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Biaya</label>
                        <input type="text" class="form-control" id="nama_biaya" name="nama_biaya" required>
                    </div>
                    <div class="form-group">
                        <label for="kelompok_biaya">Kelompok Biaya</label>
                        <select class="form-control" id="kelompok_biaya" name="kelompok_biaya" ng-model="kelompok_biaya" required>
                            <option value="">- Pilih -</option>
                            <option value="Gabungan">Gabungan</option>
                            <option value="Perlokasi">Perlokasi</option>
                            <option value="Perpetak">Perpetak</option>
                        </select>
                    </div>

                    <!-- Perlokasi -->
                    <div class="form-group" ng-show="kelompok_biaya == 'Perlokasi'">
                    <label for="lokasi_id_single">Nama Lokasi</label>
                    <select class="form-control"
                            id="lokasi_id_single"
                            name="lokasi[]"
                            ng-model="lokasiSingle"
                            ng-options="l.id as l.nama for l in lokasiList"
                            >
                        <option value="">- Pilih Lokasi -</option>
                    </select>
                    </div>

                    <!-- Gabungan -->
                    <div class="form-group" ng-show="kelompok_biaya == 'Gabungan'">
                    <label for="lokasi_id_multi">Nama Lokasi (Bisa pilih lebih dari 1)</label>
                    <select class="form-control select2"
                            id="lokasi_id_multi"
                            ng-model="lokasi"
                            name="lokasi[]"
                            multiple
                            style="width:100%; min-height:120px; font-size:14px;"
                            ng-options="l.id as l.nama for l in lokasiList">
                    </select>
                    </div>

                    <!-- Petak hanya tampil jika Perpetak -->
                    {{-- <div class="form-group" ng-show="kelompok_biaya == 'Perpetak'"  id="petak-group">
                        <label for="petak">Nama Petak</label>
                        <select class="form-control" id="petak_id" name="petak_id" ng-model="petak_id">
                            <option value="">- Pilih Petak -</option>
                        </select>
                    </div> --}}

                    <div class="form-group">
                        <label class="m-checkbox" style="margin-top: 10px;">
                            <input type="checkbox" ng-model="periode_biaya" name="periode_biaya"> Biaya Periode
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Default Nominal</label>
                        <input type="number" class="form-control" id="nominal_biaya" name="nominal_biaya">
                    </div>
                    <div class="form-group">
                        <label>COA</label>
                        <select class="form-control" id="coa_id" name="coa_id">
                            <!-- Diisi dari AJAX -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan"></textarea>
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
<script src="{{ url('/') }}/template/assets/src/select2.min.js"></script>
<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}

<!--end::Page Resources -->
@endsection



