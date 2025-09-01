@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.finance.po.script')
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
                                Penaburan Benur
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-map-marker"></i>
                                        <span>Buat Penaburan Benur</span>
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
                                <th>No Penaburan Benur</th>
                                <th>Tanggal Penaburan Benur</th>
                                <th>No PO</th>
                                <th>Lokasi</th>
                                <th>Total Bruto</th>
                                <th>Total Neto</th>
                                <th>Total Actual</th>
                                <th>Keterangan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PB202508001</td>
                                <td>2025-08-26</td>
                                <td>PO202508003</td>
                                <td>Sekuro</td>
                                <td>23.000.000</td>
                                <td>30.000.000</td>
                                <td>31.000.000</td>
                                <td>Penaburan </td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td>PB202508002</td>
                                <td>2025-08-26</td>
                                <td>PO202508003</td>
                                <td>Sekuro</td>
                                <td>12.000.000</td>
                                <td>13.000.000</td>
                                <td>21.000.000</td>
                                <td>Penaburan </td>
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
                                Buat Penaburan Benur
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
                                        <span>Kembali ke List </span>
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
                                    <label for="recipient-name" class="form-control-label">No Penaburan Benur</label>
                                    <input type="text" class="form-control" id="recipient-name" value="PO202508003" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Tanggal PO</label>
                                    <input type="date" class="form-control" id="recipient-name" value="2025-08-26">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label>Pillih PO</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search for...">
                                        <div class="input-group-append">
                                            <button ng-click="cari_supplier()" class="btn btn-info" type="button"><i class="la la-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="exampleSelect1">Lokasi</label>
                                    <input type="text" class="form-control" id="recipient-name" value="Sekuro" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <label for="exampleTextarea">Keterangan</label>
                                    <textarea class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="#" class="btn btn-outline-primary btn-sm m-btn m-btn--icon mb-2">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Benur</span>
                                    </span>
                                </a>
                                <table class="table table-striped- table-bordered table-hover table-checkable">
                                    <thead>
                                    <tr>
                                            <th colspan="3" class="text-center">Item</th>
                                            <th colspan="3" class="text-center">Bruto</th>
                                            <th colspan="3" class="text-center">Neto</th>
                                            <th colspan="3" class="text-center">Actual</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                        <tr>
                                            <th>Kolam</th>
                                            <th>Kode Benur</th>
                                            <th>Jenis Benur</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th style="width:40px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Kolam 001</td>
                                            <td>BEN001</td>
                                            <td>Benur Super</td>
                                            <td class="text-right">2.000</td>
                                            <td class="text-right">10.000</td>
                                            <td class="text-right">20.000.000</td>
                                           <td class="text-right">1.000</td>
                                            <td class="text-right">20.000</td>
                                            <td class="text-right">20.000.000</td>
                                            <td class="text-right">2.000</td>
                                            <td class="text-right">10.000</td>
                                            <td class="text-right">20.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Kolam 002</td>
                                            <td>BEN002</td>
                                            <td>Benur Medium</td>
                                            <td class="text-right">5.000</td>
                                            <td class="text-right">1.000</td>
                                            <td class="text-right">5.000.000</td>
                                            <td class="text-right">5.000</td>
                                            <td class="text-right">1.000</td>
                                            <td class="text-right">5.000.000</td>
                                            <td class="text-right">5.000</td>
                                            <td class="text-right">1.000</td>
                                            <td class="text-right">5.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Kolam 003</td>
                                            <td>BEN003</td>
                                            <td>Benur Biasa</td>
                                            <td class="text-right">1.000</td>
                                            <td class="text-right">5.000</td>
                                            <td class="text-right">5.000.000</td>
                                            <td class="text-right">1.000</td>
                                            <td class="text-right">5.000</td>
                                            <td class="text-right">5.000.000</td>
                                            <td class="text-right">1.000</td>
                                            <td class="text-right">5.000</td>
                                            <td class="text-right">5.000.000</td>
                                            <td ><a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">Total</th>
                                            <th class="text-right">16.000</th>
                                            <th class="text-right">30.000.000</th> 
                                            <th  class="text-right">Total</th>
                                            <th class="text-right">26.000</th>
                                            <th class="text-right">30.000.000</th> 
                                            <th  class="text-right">Total</th>
                                            <th class="text-right">16.000</th>
                                            <th class="text-right">30.000.000</th> 
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
                <h5 class="modal-title" id="exampleModalLabel">Data PO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <label>Cari PO</label>
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
                                <th>No PO</th>
                                <th>Tanggal PO</th>
                                <th>Supplier</th>
                                <th>Lokasi</th>
                                <th>Jumlah Item</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PO202508002</td>
                                <td>2025-08-26</td>
                                <td>Supriyadi - PT. BENUR JAYA</td>
                                <td>Sekuro</td>
                                <td>3</td>
                                <td>30.000.000</td>
                                <td>Benur untuk di kirim ke sekuro </td>
                                <td nowrap></td>
                            </tr>
                            <tr>
                                <td>PO202508001</td>
                                <td>2025-08-26</td>
                                <td>Sudarsono - PT. NUSANTARA UDANG</td>
                                <td>Bandengan</td>
                                <td>4</td>
                                <td>35.000.000</td>
                                <td>benur yang kualitas tinggi</td>
                                <td nowrap></td>
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



