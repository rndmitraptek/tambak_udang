@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <style>
        .box-laba{
            background-color: #f1fffc;
            border: 2px solid;
            border-color: #34bfa3;
            border-radius:10px
        }
        .box-rugi{
            background-color: #ffe6eaff;
            border: 2px solid;
            border-color: #f4516c;
            border-radius:10px
        }
    </style>
@endsection
@section('ctrl')
@include('feature.manajemen-tambak.simulasi.script')
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
        <div class="col-lg-3">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Simulasi
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button ng-click="tambah_simulasi()" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-bar-chart"></i>
                                        <span>Tambah Simulasi</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div ng-repeat="item in list_simulasi" ng-click="get_detail(item,$index)" class="m-alert m-alert--outline m-alert--outline alert alert-info" style="cursor: pointer;">
                                <p style="margin-bottom:0px">Siklus</P>
                                <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% item.siklus %></p>
                                <p style="margin-bottom:0px">Tanggal</P>
                                <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% item.tanggal_simulasi | date:'dd/MM/yyyy' %></p>
                                <p style="margin-bottom:0px">Catatan</P>
                                <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% item.catatan %></p>
                            </div>
                            <div class="m-alert m-alert--outline alert alert-info" role="alert" ng-if="list_simulasi.length == 0">
                                Belum ada data simulasi, silahkan klik tombol "Tambah Simulasi"
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                <% detail.siklus %>, Tanggal Simulasi <% detail.tanggal_simulasi | date:'dd/MM/yyyy' %>
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--right" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#m_portlet_base_demo_1_tab_content" role="tab" aria-selected="false">
                                    <i class="flaticon-line-graph"></i> Simulasi Kolam
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_portlet_base_demo_2_tab_content" role="tab" aria-selected="false">
                                    <i class="flaticon-list-3"></i> Pendapatan Kolam
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_portlet_base_demo_3_tab_content" role="tab" aria-selected="true">
                                    <i class="flaticon-coins"></i> Biaya Simulasi
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        {{-- TAB SIMULASI --}}
                        <div class="tab-pane active show" id="m_portlet_base_demo_1_tab_content" role="tabpanel">
                            <div class="row">
                                <div ng-repeat="kolam in detail.kolam" class="col-lg-4 p-4">
                                    <div ng-class="{'row py-2 box-laba':kolam.laba > 0,'row py-2 box-rugi':kolam.laba < 0}">
                                         <div class="col-lg-6 mb-2">
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.area %> - <% kolam.nama_kolam %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <span ng-class="{'m-badge m-badge--success m-badge--wide':kolam.laba > 0,'m-badge m-badge--danger m-badge--wide':kolam.laba < 0}" style="font-size:15px;font-weight:bold"><% kolam.laba > 0 ? 'LABA' : 'RUGI' %></span>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p style="margin-bottom:0px">BIOMASA</P>
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.biomassa | currency %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p style="margin-bottom:0px">HARGA /KG</P>
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.harga_per_kg | currency %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p style="margin-bottom:0px">PENDAPATAN</P>
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.pendapatan | currency %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p style="margin-bottom:0px">BIAYA</P>
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.biaya | currency %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p ng-class="{'m--font-success':kolam.laba > 0,'m--font-danger':kolam.laba < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="margin-bottom:0px">LABA</P>
                                            <p ng-class="{'m--font-success':kolam.laba > 0,'m--font-danger':kolam.laba < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.laba | currency %></p>
                                        </div>
                                        <div class="col-lg-6">
                                            <p style="margin-bottom:0px">HPP /KG</P>
                                            <span ng-class="{'m-badge m-badge--success m-badge--wide':kolam.laba > 0,'m-badge m-badge--danger m-badge--wide':kolam.laba < 0}" style="font-size:15px;font-weight:bold"><% kolam.hpp_per_kg | currency %></span>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p ng-class="{'m--font-success':kolam.laba > 0,'m--font-danger':kolam.laba < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="margin-bottom:0px">DOC</P>
                                            <p ng-class="{'m--font-success':kolam.laba > 0,'m--font-danger':kolam.laba < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;">45 hari</p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p ng-class="{'m--font-success':kolam.laba > 0,'m--font-danger':kolam.laba < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="margin-bottom:0px">FCR</P>
                                            <p ng-class="{'m--font-success':kolam.laba > 0,'m--font-danger':kolam.laba < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;">245</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- TAB PENDAPATAN --}}
                        <div class="tab-pane" id="m_portlet_base_demo_2_tab_content" role="tabpanel">
                            <button type="button" ng-click="save_pendapatan()" class="btn btn-primary btn-sm mb-2"><i class="la la-save"></i> Save</button>
                            <table class="table table-striped- table-bordered table-hover table-checkable" ng-if="simulasi.siklus != ''">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Nama Kolam</th>
                                        <th  style="width: 150px;">harga_per_kg</th>
                                        <th  style="width: 150px;">biomassa</th>
                                        <th>pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="detail in detail.kolam">
                                        <td class="align-middle"><% detail.area %></td>
                                        <td class="align-middle"><% detail.nama_kolam %></td>
                                        <th><input type="text" ng-model="detail.harga_per_kg" input-currency class="form-control text-right"></th>
                                        <th><input type="text" ng-model="detail.biomassa" input-currency class="form-control text-right" ></th>
                                        <th><input type="text" ng-model="detail.pendapatan" input-currency class="form-control text-right" ></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- TAB BIAYA --}}
                        <div class="tab-pane" id="m_portlet_base_demo_3_tab_content" role="tabpanel">
                            <button type="button" ng-click="add_biaya()" class="btn btn-primary btn-sm mb-2"><i class="la la-plus"></i> Tambah Biaya Simulasi</button>
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="viewtabel">
                                <thead>
                                    <tr>
                                        <th>No Transaksi</th>
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
                                        <td>2025-10-31</td>
                                        <td>Biaya Gaji Pegawai Sekuro</td>
                                        <td>25.000.000</td>
                                        <td>pembayaran gaji pegawai sekuro bulan oktober </td>
                                        <td nowrap></td>
                                    </tr>
                                    <tr>
                                        <td>TR2025082900002</td>
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
            </div>
        </div>
    </div>
</div>
<!--begin::Modal Simulasi-->
<div class="modal fade" id="m_create_simulasi" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 80vw;">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Simulasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <h4>Simulasi</h4>
                            <div class="form-group m-form__group">
                                <label for="exampleSelect1">Siklus</label>
                                <select class="form-control" id="exampleSelect1" ng-model="simulasi.siklus">
                                    <option value=""></option>
                                    <option value="2025-06-01 sd 2025-09-31">2025-06-01 sd 2025-09-31</option>
                                    <option value="2025-10-01 sd 2025-12-31">2025-10-01 sd 2025-12-31</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Tanggal Simulasi</label>
                                <input type="date" class="form-control" id="recipient-name" ng-model="simulasi.tanggal_simulasi">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="form-control-label" >Catatan</label>
                                <textarea class="form-control" id="catatan" ng-model="simulasi.catatan"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-8" style="border-left: 1px solid #ccc;">
                            <h4>Kolam Dalam Siklus</h4>
                            <div class="m-alert m-alert--outline alert alert-accent alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                <strong>Info!</strong> Biaya di ambil dari transaksi biaya sampai dengan tanggal simulasi yang di input <% simulasi.tanggal_simulasi | date:'dd/MM/yyyy' %>.
                            </div>
                            <table class="table table-striped- table-bordered table-hover table-checkable" ng-if="simulasi.siklus != ''">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Nama Kolam</th>
                                        <th>Luas</th>
                                        <th>Biaya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in kolam">
                                        <td><% item.area %></td>
                                        <td><% item.nama_kolam %></td>
                                        <td class="text-right"><% item.luas | currency %></td>
                                        <td class="text-right"><% item.biaya | currency %></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button ng-click="add_simulasi()" type="button" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--begin::Biaya-->
<div class="modal fade" id="m_create_biaya" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 80vw;">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Biaya Simulasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                                    <label for="exampleSelect1">Biaya</label>
                                    <select class="form-control" id="exampleSelect1" ng-model="biaya">
                                        <option value="all">Biaya Gaji Pegawai Sekuro</option>
                                        <option value="all">Biaya Gaji Pegawai Semarang	</option>
                                        <option value="periode">Perjalanan Ke Tambak</option>
                                        <option value="perkolam">Biaya Kuras Air</option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group" ng-show="biaya=='perkolam'">
                                    <label for="exampleSelect1">Kolam</label>
                                    <select class="form-control" id="exampleSelect1">
                                        <option >A1 - Kolam 001</option>
                                        <option >A1 - Kolam 002</option>
                                        <option >A2 - Kolam 003</option>
                                    </select>
                                </div>
                                <div class="row" ng-show="biaya=='all'">
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
                                    <input type="text" class="form-control text-right" id="recipient-name" value="0" >
                                </div>
                            </div>
                            <div class="col-lg-8" style="border-left: 1px solid #ccc;">
                                <table class="table table-striped- table-bordered table-hover table-checkable" ng-if="biaya != 'perkolam'">
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
<script>
    $( "body" ).addClass( "m-aside-left--minimize m-brand--minimize m-brand__toggler--active" );
</script>
@endsection



