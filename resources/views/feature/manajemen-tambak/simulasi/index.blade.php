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
                                <button ng-click="tambah_simulasi()" class="btn btn-sm btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
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
                                <table class="table table-borderless">
                                    <tbody>
                                    <tr>
                                        <th scope="row" style="width: 80px; padding:.25rem;">Lokasi</th>
                                        <td style="padding:.25rem;"><% item.nama_lokasi %></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="padding:.25rem;">Siklus</th>
                                        <td style="padding:.25rem;"><% item.nama_siklus %></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="padding:.25rem;">Tanggal</th>
                                        <td style="padding:.25rem;"><% item.tanggal_simulasi | date:'dd/MM/yyyy' %></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" style="padding:.25rem;">Catatan</th>
                                        <td style="padding:.25rem;"><% item.catatan %></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <hr>
                            </div>
                            <div class="m-alert m-alert--outline alert alert-danger" role="alert" ng-if="list_simulasi.length == 0">
                                Belum ada data simulasi, silahkan klik tombol "Tambah Simulasi"
                            </div>

                            <!-- tombol prev next -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-sm btn-primary" 
                                    ng-click="prevPage()" 
                                    ng-disabled="page==1">Prev</button>

                            <span>Halaman <% page %> dari <% total_page %></span>

                            <button class="btn btn-sm btn-primary" 
                                    ng-click="nextPage()" 
                                    ng-disabled="page*per_page>=total_records">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption col-sm-4">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                <% judul.nama_lokasi %> - <% judul.nama_siklus %>, Tanggal Simulasi <% detail.tanggal_simulasi | date:'dd/MM/yyyy' %>
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools col-sm-8">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--right" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#m_portlet_base_demo_1_tab_content" role="tab" aria-selected="false">
                                    <i class="flaticon-line-graph"></i> Simulasi Petak
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_portlet_base_demo_2_tab_content" role="tab" aria-selected="false">
                                    <i class="flaticon-list-3"></i> Pendapatan Petak
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
                                <div ng-repeat="kolam in detail.simulasi" class="col-lg-4 p-4">
                                    <div ng-class="{'row py-2 box-laba':kolam.laba_rugi > 0,'row py-2 box-rugi':kolam.laba_rugi < 0}">
                                         <div class="col-lg-6 mb-2">
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.detail_biaya.petak.blok.nama_blok %> - <% kolam.detail_biaya.petak.nama_petak %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <span ng-class="{'m-badge m-badge--success m-badge--wide':kolam.laba_rugi > 0,'m-badge m-badge--danger m-badge--wide':kolam.laba_rugi < 0}" style="font-size:15px;font-weight:bold"><% kolam.laba_rugi > 0 ? 'LABA' : 'RUGI' %></span>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p style="margin-bottom:0px">BIOMASA</P>
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.detail_pendapatan.biomassa | currency %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p style="margin-bottom:0px">HARGA /KG</P>
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.detail_pendapatan.harga_per_kg | currency %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p style="margin-bottom:0px">PENDAPATAN</P>
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.total_pendapatan | currency %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p style="margin-bottom:0px">BIAYA</P>
                                            <p style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.total_biaya_all | currency %></p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p ng-class="{'m--font-success':kolam.laba_rugi > 0,'m--font-danger':kolam.laba_rugi < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="margin-bottom:0px">LABA</P>
                                            <p ng-class="{'m--font-success':kolam.laba_rugi > 0,'m--font-danger':kolam.laba_rugi < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.laba_rugi | currency %></p>
                                        </div>
                                        <div class="col-lg-6">
                                            <p style="margin-bottom:0px">HPP /KG</P>
                                            <span ng-class="{'m-badge m-badge--success m-badge--wide':kolam.laba_rugi > 0,'m-badge m-badge--danger m-badge--wide':kolam.laba_rugi < 0}" style="font-size:15px;font-weight:bold"><% kolam.hpp_per_kg | currency %></span>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p ng-class="{'m--font-success':kolam.laba_rugi > 0,'m--font-danger':kolam.laba_rugi < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="margin-bottom:0px">DOC</P>
                                            <p ng-class="{'m--font-success':kolam.laba_rugi > 0,'m--font-danger':kolam.laba_rugi < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;"><% kolam.detail_biaya.doc | currency %> hari</p>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <p ng-class="{'m--font-success':kolam.laba_rugi > 0,'m--font-danger':kolam.laba_rugi < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="margin-bottom:0px">FCR</P>
                                            <p ng-class="{'m--font-success':kolam.laba_rugi > 0,'m--font-danger':kolam.laba_rugi < 0}" ng-style="{'color': isWarning ? 'red' : 'black'}" style="font-size: 1.2rem;font-weight: 500;margin-bottom:.25rem;">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- TAB PENDAPATAN --}}
                        <div class="tab-pane" id="m_portlet_base_demo_2_tab_content" role="tabpanel" ng-if="judul != null">
                            <button type="button" ng-click="save_pendapatan()" class="btn btn-primary btn-sm mb-2"><i class="la la-save"></i> Save</button>
                            <table class="table table-striped- table-bordered table-hover table-checkable" >
                                <thead>
                                    <tr>
                                        <th>Blok</th>
                                        <th>Nama Petak</th>
                                        <th  style="width: 150px;">Harga /KG</th>
                                        <th  style="width: 150px;">Biomassa</th>
                                        <th>Pendapatan</th>
                                        <th>Pendapatan Actual (Panen)</th>
                                    </tr>
                                </thead>
                                <tbody ng-if="detail.pendapatan.length ==0">
                                    <tr ng-repeat="detail in detail.simulasi" >
                                        <td class="align-middle"><% detail.detail_biaya.petak.blok.nama_blok %></td>
                                        <td class="align-middle"><% detail.detail_biaya.petak.nama_petak %></td>
                                        <td><input type="text" ng-model="detail.harga_per_kg" input-currency class="form-control text-right"></td>
                                        <td><input type="text" ng-model="detail.biomassa" input-currency class="form-control text-right" ></td>
                                        <td><input type="text" ng-model="detail.pendapatan_simulasi" input-currency class="form-control text-right" ></td>
                                        <td><input type="text" ng-model="detail.pendapatan_actual_partial" input-currency class="form-control text-right" readonly value="<% detail.pendapatan_actual_partial %>" style="background-color: rgb(225, 213, 213);"></td>
                                    </tr>
                                <tbody ng-if="detail.pendapatan.length >0">
                                    <tr ng-repeat="detail in detail.pendapatan">
                                        <td class="align-middle"><% detail.petak.blok.nama_blok %></td>
                                        <td class="align-middle"><% detail.petak.nama_petak %></td>
                                        <td><input type="text" ng-model="detail.harga_per_kg" input-currency class="form-control text-right" value="<% detail.harga_per_kg %>"></td>
                                        <td><input type="text" ng-model="detail.biomassa" input-currency class="form-control text-right" value="<% detail.biomassa %>"></td>
                                        <td><input type="text" ng-model="detail.pendapatan_simulasi" input-currency class="form-control text-right" value="<% detail.pendapatan_simulasi %>"></td>
                                        <td><input type="text" ng-model="detail.pendapatan_actual_partial" input-currency class="form-control text-right" readonly value="<% detail.pendapatan_actual_partial %>" style="background-color: rgb(225, 213, 213);"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        {{-- TAB BIAYA SIMULASI--}}
                        <div class="tab-pane" id="m_portlet_base_demo_3_tab_content" role="tabpanel" ng-if="judul != null">
                            <button type="button" ng-click="add_biaya()" class="btn btn-primary btn-sm mb-2"><i class="la la-plus"></i> Tambah Biaya Simulasi</button>
                            <h1>BIAYA SIMULASI</h1>
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="viewtabel" >
                                <thead>
                                    <tr>
                                        <th>Blok</th>
                                        <th>Nama Petak</th>
                                        <th>Status</th>
                                        <th>Luas</th>
                                        <th>Biaya</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loading Row -->
                                    <tr ng-if="loadingBiayaSimulasi">
                                        <td colspan="6" class="text-center">
                                            <i class="fa fa-spinner fa-spin"></i> Sedang memuat data...
                                        </td>
                                    </tr>
                                    <tr ng-repeat="item in biayaSimulasiList">
                                        <td><% item.petak.blok.nama_blok %></td>
                                        <td><% item.petak.nama_petak %></td>
                                        <td><% item.status_panen %></td>
                                        <td class="text-right"><% item.petak.luas_petak | currency %></td>
                                        <td class="text-right">Rp <% item.biaya_simulasi | currency %></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" 
                                                    ng-click="viewDetail(item.detail_biaya)">View Detail</button>
                                        </td>
                                    </tr>
                                    <!-- Total biaya semua petak -->
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold">Total Biaya</td>
                                        <td class="text-right font-weight-bold">
                                            Rp <% tabBiayaTotalBiayaSimulasi() | currency %>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            </br>

                        {{-- TAB BIAYA ACTUAL --}}
                            <h1>BIAYA ACTUAL</h1>
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="viewtabelactual">
                                <thead>
                                    <tr>
                                        <th>Blok</th>
                                        <th>Nama Petak</th>
                                        <th>Luas</th>
                                        <th>Biaya</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody ng-if="simulasi.id_simulasi !=null">
                                    <tr ng-repeat="item in detail.simulasi">
                                        <td><% item.detail_biaya.petak.blok.nama_blok %></td>
                                        <td><% item.detail_biaya.petak.nama_petak %></td>
                                        <td class="text-right"><% item.detail_biaya.petak.luas_petak | currency %></td>
                                        <td class="text-right">Rp <% item.total_biaya_real | currency %></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" 
                                                    ng-click="viewDetail(item.detail_biaya.detail_biaya_actual)">View Detail</button>
                                        </td>
                                    </tr>
                                    <!-- Total biaya semua petak -->
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">Total Biaya</td>
                                        <td class="text-right font-weight-bold">
                                            Rp <% tabBiayaTotalBiayaActual() | currency %>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                {{-- <tbody>
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
                                </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--begin::Modal Simulasi-->
<div class="modal fade" id="m_create_simulasi" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Tanggal Simulasi</label>
                                <input type="text" class="form-control datepicker" id="tanggal_simulasi" ng-model="simulasi.tanggal_simulasi" autocomplete="off" required>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="exampleSelect1">Lokasi</label>
                                <select class="form-control" 
                                        id="selectLokasi" 
                                        ng-model="simulasi.lokasi"
                                        ng-options="lok.id_lokasi as lok.nama_lokasi for lok in lokasiList">
                                    <option value="0">--Pilih Lokasi--</option>
                                </select>
                            </div>
                            <div class="form-group m-form__group">
                                <label for="exampleSelect1">Siklus</label>
                                {{-- <select class="form-control" id="exampleSelect1" ng-model="simulasi.siklus">
                                    <option value=""></option>
                                    <option value="2025-06-01 sd 2025-09-31">2025-06-01 sd 2025-09-31</option>
                                    <option value="2025-10-01 sd 2025-12-31">2025-10-01 sd 2025-12-31</option>
                                </select> --}}
                                <select class="form-control" 
                                        id="selectSiklus" 
                                        ng-model="simulasi.siklus"
                                        ng-options="sk.id_siklus as sk.nama_siklus for sk in siklusList">
                                    <option value="0">--Pilih Siklus--</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="form-control-label" >Catatan</label>
                                <textarea class="form-control" id="catatan" ng-model="simulasi.catatan"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-8" style="border-left: 1px solid #ccc;">
                            <h4>Petak Dalam Siklus</h4>
                            <div class="m-alert m-alert--outline alert alert-accent alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                <strong>Info!</strong> Biaya di ambil dari transaksi biaya actual sampai dengan tanggal simulasi yang di input <% simulasi.tanggal_simulasi | date:'dd/MM/yyyy' %>.
                            </div>
                            <table class="table table-striped- table-bordered table-hover table-checkable" ng-if="simulasi.siklus != ''">
                                <thead>
                                    <tr>
                                        <th>Blok</th>
                                        <th>Nama Petak</th>
                                        <th>Status</th>
                                        <th>Luas</th>
                                        <th>Biaya</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in petakList">
                                        <td><% item.petak.blok.nama_blok %></td>
                                        <td><% item.petak.nama_petak %></td>
                                        <td><% item.status_panen %></td>
                                        <td class="text-right"><% item.petak.luas_petak | currency %></td>
                                        <td class="text-right">Rp <% item.biaya_simulasi | currency %></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" 
                                                    ng-click="viewDetail(item.detail_biaya)">View Detail</button>
                                        </td>
                                    </tr>
                                    <!-- Total biaya semua petak -->
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold">Total Biaya</td>
                                        <td class="text-right font-weight-bold">
                                            Rp <% totalBiayaSimulasi() | currency %>
                                        </td>
                                        <td></td>
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


{{-- modal detail biaya json --}}
<div class="modal fade" id="modalDetailBiaya" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="max-width: 70vw;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Biaya Petak</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-sm table-striped- table-bordered table-hover">
          <thead>
            <tr>
              <th>Nama Biaya</th>
              <th>No Transaksi</th>
              <th>Tipe</th>
              <th>Biaya Petak</th>
              <th>Biaya / Hari</th>
              <th>Hari Simulasi</th>
              <th>Biaya Hitung</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="b in selectedDetailBiaya">
              <td><% b.nama_biaya %></td>
              <td><% b.no_transaksi || '-' %></td>
              <td><% b.tipe_perhitungan %></td>
              <td class="text-right">Rp <% b.nominal_petak | currency %></td>
              <td class="text-right">Rp <% b.biaya_per_hari | currency %></td>
              <td class="text-right"><% b.hari_sampai_simulasi %> hari</td>
              <td class="text-right">Rp <% b.biaya_hitung | currency %></td>
            </tr>
            <!-- Total biaya_hitung -->
            <tr>
              <td colspan="6" class="text-right font-weight-bold">Total Biaya Hitung</td>
              <td class="text-right font-weight-bold">
                Rp <% totalBiayaDetail() | currency %>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!--begin::Biaya-->
{{-- <div class="modal fade" id="m_create_biaya" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div> --}}

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



