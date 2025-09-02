@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.panen.script')
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
                                Panen
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-map-marker"></i>
                                        <span>Transaksi Panen</span>
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
                                <th>No Panen</th>
                                <th>Tanggal Panen</th>
                                <th>Siklus</th>
                                <th>Lokasi</th>
                                <th>Kolam</th>
                                <th>Luas</th>
                                <th>Total</th>
                                <th>Harga Per Kg</th>
                                <th>Biomass</th>
                                <th>Keterangan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PN2025100001</td>
                                <td>2025-10-31</td>
                                <td>2025-10-01 sd 2025-12-31</td>
                                <td>Sekuro</td>
                                <td>Kolam 003</td>
                                <td>500</td>
                                <td>55.000.000</td>
                                <td>200.000</td>
                                <td>3.000 kg</td>
                                <td>panen karna kurang berkembang</td>
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
                                Buat Panen
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-save"></i>
                                        <span>Simpan Transaksi</span>
                                    </span>
                                </button>
                            </li>
                            <li class="m-portlet__nav-item">
                                <button ng-click="kembali()" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-arrow-left"></i>
                                        <span>Kembali ke List Panen</span>
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
                                    <label for="recipient-name" class="form-control-label">No Panen</label>
                                    <input type="text" class="form-control" id="recipient-name" value="PO202508003" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Panen</label>
                                    <input type="date" class="form-control" id="recipient-name" value="2025-08-26">
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Siklus</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>2025-06-01 sd 2025-09-31</option>
                                        <option>2025-10-01 sd 2025-12-31</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Lokasi</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>Sekuro</option>
                                        <option>Bandengan</option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Area</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>A1</option>
                                        <option>A2</option>
                                        <option>A3</option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Kolam</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>Kolam 001</option>
                                        <option>Kolam 002</option>
                                        <option>Kolam 003</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Jenis Panen</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option>Partial</option>
                                        <option>GLobal</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Biomas</label>
                                    <input type="text" class="form-control" id="recipient-name" value="0">
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Harga Per Kg</label>
                                    <input type="text" class="form-control" id="recipient-name" value="0">
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="#" class="btn btn-outline-primary btn-sm m-btn m-btn--icon mb-2">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Penjualan</span>
                                    </span>
                                </a>
                                <table class="table table-striped- table-bordered table-hover table-checkable">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Customer</th>
                                            <th>Metode Pembayaran</th>
                                            <th>Item</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2025-11-01</td>
                                            <td>Sudirman</td>
                                            <td>Piutang</td>
                                            <td>Udang Sehat isi 9</td>
                                            <td class="text-right">150.000</td>
                                            <td class="text-right">100</td>
                                            <td class="text-right">15.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>2025-11-01</td>
                                            <td>Baharudin</td>
                                            <td>Piutang</td>
                                            <td>Udang Sehat isi 20</td>
                                            <td class="text-right">100.000</td>
                                            <td class="text-right">100</td>
                                            <td class="text-right">10.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>2025-11-01</td>
                                            <td>Komarudin</td>
                                            <td>Tunai</td>
                                            <td>Udang Sehat isi 5</td>
                                            <td class="text-right">300.000</td>
                                            <td class="text-right">100</td>
                                            <td class="text-right">30.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total</th>
                                            <th class="text-right">4</th>
                                            <th class="text-right">55.000.000</th> 
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="message-text" class="form-control-label" >Keterangan</label>
                                    <textarea class="form-control" id="alamat"></textarea>
                                </div>
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



