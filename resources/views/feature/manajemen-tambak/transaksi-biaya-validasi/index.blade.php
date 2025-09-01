@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.transaksi-biaya-validasi.script')
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
                                Validasi Transaksi Biaya
                            </h3>
                        </div>
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
                        <tbody>
                            <tr>
                                <td>TR2025082900001</td>
                                <td>2025-10-01 sd 2025-12-31</td>
                                <td>2025-10-31</td>
                                <td>Biaya Gaji Pegawai Sekuro</td>
                                <td>25.000.000</td>
                                <td>pembayaran gaji pegawai sekuro bulan oktober </td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td>TR2025082900002</td>
                                <td>2025-10-01 sd 2025-12-31</td>
                                <td>2025-10-31</td>
                                <td>Biaya Gaji Pegawai Semarang</td>
                                <td>30.000.000</td>
                                <td>pembayaran gaji pegawai semarang bulan oktober </td>
                                <td nowrap></td>
                            </tr>
                        </tbody>
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
                                Transaksi Biaya
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-check"></i>
                                        <span>Validasi Transaksi</span>
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
                    <form>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">No Transaksi</label>
                                    <input type="text" class="form-control" id="recipient-name" value="TR2025082900003" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Transaksi</label>
                                    <input type="date" class="form-control" id="recipient-name" value="2025-08-01" readonly>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Siklus</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>2025-06-01 sd 2025-09-31</option>
                                        <option>2025-10-01 sd 2025-12-31</option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Biaya</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>Biaya Gaji Pegawai Sekuro</option>
                                        <option>Biaya Gaji Pegawai Semarang	</option>
                                        <option>Biaya Pakan	</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group m-form__group">
                                            <label for="exampleSelect1">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="exampleSelect1" value="2025-10-01">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-form__group">
                                            <label for="exampleSelect1">Tanggal Selesai</label>
                                            <input type="date" class="form-control" id="exampleSelect1" value="2025-10-31">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Nominal</label>
                                    <input type="text" class="form-control text-right" id="recipient-name" value="25.000.000">
                                </div>
                                <div class="form-group">
                                    <label for="exampleSelect1">COA Pasangan Biaya</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option value=""></option>
                                        <option ng-repeat="akun in coa"><% akun.kode_akun %> - <% akun.nama_akun %></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-8" style="border-left: 1px solid #ccc;">
                                <table class="table table-striped- table-bordered table-hover table-checkable" ng-if="lokasi != ''">
                                    <thead>
                                        <tr>
                                            <th>Lokasi</th>
                                            <th>Kolam</th>
                                            <th>Status</th>
                                            <th>Luas</th>
                                            <th>Persen</th>
                                            <th>biaya perkolam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Sekuro</td>
                                            <td>Kolam 001</td>
                                            <td>Active</td>
                                            <td>1.000</td>
                                            <td>33,33%</td>
                                            <td>8.333.333,33</td>
                                        </tr>
                                        <tr>
                                            <td>Sekuro</td>
                                            <td>Kolam 002</td>
                                            <td>Active</td>
                                            <td>2.000</td>
                                            <td>66,67%</td>
                                            <td>16.666.666,67</td>
                                        </tr>
                                        <tr>
                                            <td>Sekuro</td>
                                            <td>Kolam 003</td>
                                            <td>Panen</td>
                                            <td>500</td>
                                            <td>0%</td>
                                            <td>0</td>
                                        </tr>
                                    </tbody>
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

<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}

<!--end::Page Resources -->
@endsection



