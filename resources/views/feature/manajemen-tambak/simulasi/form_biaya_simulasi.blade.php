@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.simulasi.script_biaya_simulasi')
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
                                Transaksi Biaya Simulasi
                            </h3>
                            <input type="hidden" name="uuid_simulasi" value="{{ $uuid_simulasi }}">
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-map-marker"></i>
                                        <span>Buat Transaksi Biaya Simulasi</span>
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
                                <th>No Transaksi</th>
                                <th>Siklus</th>
                                <th>Tanggal Transaksi</th>
                                <th>Biaya</th>
                                <th>Nominal Biaya</th>
                                <th>Keterangan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
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
                                Transaksi Biaya Simulasi
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button id="btn-simpan" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-save"></i>
                                        <span>Simpan Transaksi Simulasi</span>
                                    </span>
                                </button>
                            </li>
                            <li class="m-portlet__nav-item">
                                <button ng-click="kembali()" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>Kembali ke List</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <form id="formTransaksi">
                        <input type="hidden" id="uuid" name="uuid">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">No Transaksi</label>
                                    <input type="text" class="form-control" id="no_transaksi" name="no_transaksi" required>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Transaksi</label>
                                    <input type="text" class="form-control" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleSelect1">Biaya</label>
                                    <select id="biaya-dropdown" class="form-control"></select>
                                    <div id="biaya-detail"></div>
                                </div>
                                {{-- <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Biaya</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>Biaya Gaji Pegawai Sekuro</option>
                                        <option>Biaya Gaji Pegawai Semarang	</option>
                                        <option>Biaya Pakan	</option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Siklus<b> Sekuro</b></label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>2025-06-01 sd 2025-09-31</option>
                                        <option>2025-10-01 sd 2025-12-31</option>
                                    </select>
                                </div>                                
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Siklus<b> Blebak</b></label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>2025-06-01 sd 2025-09-31</option>
                                        <option>2025-10-01 sd 2025-12-31</option>
                                    </select>
                                </div>--}}
                                <div class="row" id="periode-biaya" style="display: none;">
                                    <div class="col-lg-6">
                                        <div class="form-group m-form__group">
                                            <label for="exampleSelect1">Tanggal Mulai</label>
                                            <input type="text" class="form-control" id="tanggal_mulai" name="tanggal_mulai" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-form__group">
                                            <label for="exampleSelect1">Tanggal Selesai</label>
                                            <input type="text" class="form-control" id="tanggal_selesai" name="tanggal_selesai" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Nominal</label>
                                    <input type="text" class="form-control text-right" id="nominal" name="nominal" input-currency ng-model="nominal" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleSelect1">COA Pasangan Biaya</label>
                                    <select class="form-control" id="coa_id" name="coa_id">
                                        <!-- Diisi dari AJAX -->
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label" >Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-8" style="border-left: 1px solid #ccc;">
                                <table class="table table-striped- table-bordered table-hover table-checkable" id="viewtabelpetak">
                                    <thead>
                                        <tr>
                                            <th>ID Siklus</th>
                                            <th>ID Petak</th>
                                            <th>Lokasi</th>
                                            <th>Petak</th>
                                            <th>Status</th>
                                            <th>Luas</th>
                                            <th>Persen</th>
                                            <th>biaya perpetak</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" style="text-align:right">Total:</th>
                                            <th></th>
                                            <th></th>
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
<div class="modal fade" id="m_supplier" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label>Cari Supplier</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for...">
                        <div class="input-group-append">
                            <button class="btn btn-info" type="button"><i class="la la-search"></i></button>
                        </div>
                    </div>
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Nomor Telepon</th>
                            <th>Email</th>
                            <th>Nama Perusahaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SUP0001</td>
                            <td>Supriyadi</td>
                            <td>Jalan soekarno hatta, semarang</td>
                            <td>+62 3456 3453 2343 3453, 024 3456 3456</td>
                            <td>supriyadi@gmail.com</td>
                            <td>PT. BENUR JAYA</td>
                        </tr>
                        <tr>
                            <td>SUP0001</td>
                            <td>Sudarsono</td>
                            <td>bukit mutiara jaya, semarang</td>
                            <td>+62 8264 9782 6786, 024 5082 3347</td>
                            <td>supriyadi@gmail.com</td>
                            <td>PT. NUSANTARA UDANG</td>
                        </tr>
                    </tbody>
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



