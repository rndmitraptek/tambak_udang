@extends('layout')
@section('css')
	<link href="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <style>
        .fs-large{
            font-size: large;
        }
    </style>
@endsection
@section('ctrl')
@include('feature.akuntansi.neraca.script')
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
                                Neraca
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-row align-items-end">
                        <!-- Start Date -->
                        <div class="col-md-2 mb-3">
                            <label for="startDate">Tanggal</label>
                            <div class="input-group date">
                                <input type="text" id="tanggal" name="tanggal" class="form-control general_datepicker" placeholder="yyyy-mm-dd" autocomplete="off"/>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Tombol Search -->
                        <div class="col-md-2 mb-3">
                            <button type="button" ng-click="get_neraca()" class="btn btn-primary btn-block">
                                <i class="la la-search"></i> Search
                            </button>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h2>Neraca</h2>
                            <h4>Periode S/D <% tanggal %></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-sm m-table m-table--head-bg-brand">
                                <thead>
                                    <tr>
                                        <th style="width: 150px">COA</th>
                                        <th>Keterangan</th>
                                        <th style="width: 250px" class="text-center"></th>
                                        <th style="width: 250px" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="i in neraca">
                                        <td class="m--font-bold" ng-class="{'m--font-boldest': i.tipe_coa=='header' ||i.tipe_coa=='footer' }"><% i.kode_coa %></td>
                                        <td class="m--font-bold" ng-class="{'m--font-boldest': i.tipe_coa=='header' ||i.tipe_coa=='footer','fs-large':i.tipe_coa=='footer'}" ><% i.nama_coa %></td>
                                        <td class="text-right"><a class="m--font-bold" ng-show="i.tipe_coa=='detail' && i.saldo !=0"><% i.saldo | currency:'' %></a></td>
                                        <td class="text-right "><a class="m--font-boldest" ng-class="{'fs-large':i.tipe_coa=='footer'}"  ng-show="(i.tipe_coa=='header' || i.tipe_coa=='footer') && i.saldo !=0"><% i.saldo | currency:'' %></a></td>
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

@endsection

@section('js')
<!--begin::Page Vendors -->
<script src="{{ url('/') }}/template/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>

<!--end::Page Vendors -->

<!--begin::Page Resources -->
{{-- <script src="{{ url('/') }}/template/assets/demo/default/custom/crud/datatables/basic/scrollable.js" type="text/javascript"></script> --}}

<!--end::Page Resources -->
@endsection



