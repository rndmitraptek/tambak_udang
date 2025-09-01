@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
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
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-map-marker"></i>
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
                        <tbody>
                            <tr>
                                <td>BBN50001</td>
                                <td>Biaya Gaji Pegawai Sekuro</td>
                                <td>Perlokasi</td>
                                <td>Ya</td>
                                <td>51111 - Biaya Gaji Pegawai</td>
                                <td>25.000.000</td>
                                <td>Biaya gaji pegawai sekuro</td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td>BBN50002</td>
                                <td>Biaya Gaji Pegawai Semarang</td>
                                <td>Gabungan</td>
                                <td>Ya</td>
                                <td>51111 - Biaya Gaji Pegawai</td>
                                <td>30.000.000</td>
                                <td>Biaya gaji pegawai semarang</td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td>BBN50003</td>
                                <td>Biaya Pakan</td>
                                <td>Perpetak</td>
                                <td>tidak</td>
                                <td>51112 - Biaya Pakan</td>
                                <td>-</td>
                                <td>pakan benur</td>
                                <td nowrap></td>
                            </tr>
                        </tbody>
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
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Setup Biaya</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Kode Biaya</label>
                        <input type="text" class="form-control" id="recipient-name">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Nama Biaya</label>
                        <input type="text" class="form-control" id="recipient-name">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelect1">Kelompok Biaya</label>
                        <select class="form-control" id="exampleSelect1" ng-model="kelompok" value="Perpetak">
                            <option value=""></option>
                            <option value="Gabungan">Gabungan</option>
                            <option value="Perlokasi">Perlokasi</option>
                            <option value="Perpetak">Perpetak</option>
                        </select>
                    </div>
                    <div class="form-group" ng-model="lokasi" ng-if="kelompok != 'Perpetak'">
                        <label for="exampleSelect1">Nama Lokasi</label>
                        <select class="form-control" id="exampleSelect1">
                            <option value=""></option>
                            <option>Sekuro</option>
                            <option>Bandengan</option>
                        </select>
                    </div>
                    <div ng-if="kelompok=='Gabungan'" class="form-group" ng-model="lokasi" ng-if="kelompok != 'Perpetak'">
                        <label for="exampleSelect1">Nama Lokasi</label>
                        <select class="form-control" id="exampleSelect1">
                            <option value=""></option>
                            <option>Sekuro</option>
                            <option>Bandengan</option>
                        </select>
                    </div>
                    <button ng-if="kelompok=='Gabungan'" ng-click="tambah()" href="#" class=" btn btn-primary " title="View"><i class="la la-plus"></i>Tambah Lokasi</button>
                    </td>
                    <div class="form-group">
                        <label class="m-checkbox" style="margin-top: 10px;">
                            <input type="checkbox"> Biaya Periode
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">Default Nominal</label>
                        <input type="text" class="form-control" id="recipient-name">
                    </div>
                    <div class="form-group" ng-model="lokasi">
                        <label for="exampleSelect1">COA</label>
                        <select class="form-control" id="exampleSelect1">
                            <option value=""></option>
                            <option ng-repeat="akun in coa"><% akun.kode_akun %> - <% akun.nama_akun %></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="form-control-label" id="catatan" >Catatan</label>
                        <textarea class="form-control" id="alamat"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
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

<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}

<!--end::Page Resources -->
@endsection



