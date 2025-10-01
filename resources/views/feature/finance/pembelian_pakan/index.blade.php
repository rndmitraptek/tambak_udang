@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/template/assets/src/select2.min.css" rel="stylesheet" type="text/css"/>
@endsection
@section('ctrl')
@include('feature.finance.pembelian_pakan.script')
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
    .select2-search__field {
        display: block !important;
    }
</style>

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
                                Transaksi Pembelian Pakan
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-map-marker"></i>
                                        <span>Buat Pembelian Pakan</span>
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
                        {{-- <thead>
                            <tr>
                                <th>No Pembelian</th>
                                <th>Tanggal Pembelian</th>
                                <th>Supplier</th>
                                <th>Lokasi</th>
                                <th>Jumlah Item</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                                <th>Actions</th>
                            </tr>
                        </thead> --}}
                        {{-- <tbody>
                            <tr>
                                <td>PP202508002</td>
                                <td>2025-08-26</td>
                                <td>Supriyadi - PT. PAKAN JAYA</td>
                                <td>Sekuro</td>
                                <td>3</td>
                                <td>30.000.000</td>
                                <td>Pakan untuk di kirim ke sekuro </td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td>PP202508001</td>
                                <td>2025-08-26</td>
                                <td>Sudarsono - PT. NUSANTARA PAKAN</td>
                                <td>Bandengan</td>
                                <td>4</td>
                                <td>35.000.000</td>
                                <td>pakan yang kualitas tinggi</td>
                                <td nowrap></td>
                            </tr>
                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-12" ng-show="form == 'input'">
            <div class="m-portlet m-portlet--tab">
                <form id="formInput">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Buat Pembelian Pakan
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button type="submit" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air">
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
                                        <span>Kembali ke List Pembelian</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">No Pembelian</label>
                                    <input type="text" class="form-control" name="no_pembelian" id="recipient-name" ng-model="input.no_pembelian" required>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Tanggal Pembelian</label>
                                    <input type="text" class="form-control general_datepicker" id="tanggal_pembelian" name="tanggal_pembelian" ng-model="input.tanggal_pembelian" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label>Pillih Supplier</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" ng-model="input.nama_supplier" id="nama_supplier" name="nama_supplier" placeholder="Search for..." required>
                                        <div class="input-group-append">
                                            <button ng-click="cari_supplier()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="uuid_lokasi">Lokasi</label>
                                    <select class="form-control" id="uuid_lokasi" ng-change="get_siklus()" ng-model="input.uuid_lokasi" name="uuid_lokasi" required>
                                        <option  value="" >Pillih Lokasi</option>
                                        <option ng-repeat="x in lokasi" value="<% x.uuid %>" ><% x.nama_lokasi %></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="uuid_lokasi">Siklus</label>
                                    <select class="form-control" id="uuid_siklus" ng-model="input.uuid_siklus" name="uuid_siklus" required>
                                        <option  value="" >Pillih Siklus</option>
                                        <option ng-repeat="x in siklus" value="<% x.uuid %>" ><% x.nama_siklus %></option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleTextarea">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" ng-model="input.keterangan" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <a ng-click="add_pakan()" class="btn btn-outline-primary btn-sm m-btn m-btn--icon mb-2">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Pakan</span>
                                    </span>
                                </a>
                                <table class="table table-striped- table-bordered table-hover table-checkable">
                                    <thead>
                                        <tr>
                                            <th>Kode Pakan</th>
                                            <th>Nama Pakan</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th style="width:40px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in daftarPakan">
                                            <td><% item.kode_pakan %></td>
                                            <td><% item.nama_pakan %></td>
                                            <td class="text-right"><% item.harga | number:0 %></td>
                                            <td class="text-right"><% item.jumlah %></td>
                                            <td class="text-right"><% item.subtotal | number:0 %></td>
                                            <td>
                                            <button class="btn btn-sm" ng-click="hapusPakan($index)">
                                                <i class="m--font-danger la la-remove"></i>
                                            </button>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold">TOTAL</td>
                                            <td class="text-right font-weight-bold"><% getTotal() | number:0 %></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    {{-- <tbody>
                                        <tr>
                                            <td>PAK001</td>
                                            <td>Pakan Super</td>
                                            <td class="text-right">10.000.000</td>
                                            <td class="text-right">2</td>
                                            <td class="text-right">20.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>PAK002</td>
                                            <td>Pakan Medium</td>
                                            <td class="text-right">5.000.000</td>
                                            <td class="text-right">1</td>
                                            <td class="text-right">5.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>PAK003</td>
                                            <td>Pakan Biasa</td>
                                            <td class="text-right">5.000.000</td>
                                            <td class="text-right">1</td>
                                            <td class="text-right">5.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total</th>
                                            <th class="text-right">4</th>
                                            <th class="text-right">30.000.000</th> 
                                            <th></th>
                                        </tr>
                                    </tfoot> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<look-up-table
      lookup-id="lookup_supplier"
      ajax-url="{{ route('finance.pembelian-pakan.get_supplier') }}"
      columns="supplierColumns"
      page-length="8"
      on-select="selectSupplier(row)">
</look-up-table>

<!--begin::Modal-->
<div class="modal fade" id="m_pakan" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pilih Pakan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label for="id_pakan">Pakan</label>
                    <select class="form-control"
                            id="id_pakan"
                            ng-model="input.id_pakan"
                            name="id_pakan"
                            ng-options="x.id_pakan as (x.kode_pakan + ' - ' + x.nama_pakan) for x in pakan">
                        <option value="">--Pilih pakan--</option>
                    </select>
                </div>
                <div class="form-group m-form__group">
                    <label for="harga">Harga Per Kg</label>
                    <input type="text" class="form-control text-right" input-currency name="harga" id="harga" ng-model="harga" ng-change="hitungSubtotal()">
                </div>
                <div class="form-group m-form__group">
                    <label for="jumlah">Jumlah (Kg)</label>
                    <input type="text" class="form-control text-right" input-currency name="jumlah" id="jumlah" ng-model="jumlah" value=1 ng-change="hitungSubtotal()">
                </div>
                <div class="form-group m-form__group">
                    <label for="subtotal">Subtotal</label>
                    <input type="text" class="form-control text-right" input-currency name="subtotal" id="subtotal" ng-model="subtotal" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" ng-click="simpanPakan()">Simpan</button>
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
                            <td>PT. PAKAN JAYA</td>
                        </tr>
                        <tr>
                            <td>SUP0001</td>
                            <td>Sudarsono</td>
                            <td>bukit mutiara jaya, semarang</td>
                            <td>+62 8264 9782 6786, 024 5082 3347</td>
                            <td>supriyadi@gmail.com</td>
                            <td>PT. NUSANTARA PAKAN</td>
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
<script src="{{ url('/') }}/template/assets/src/select2.min.js"></script>
<script src="{{ url('/') }}/template/assets/src/jquery.validate.min.js"></script>
<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}

<!--end::Page Resources -->
@endsection



