@extends('layout')
@section('css')
@endsection
@section('ctrl')
@include('feature.master.lokasi.script')
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
        <div class="col-lg-6">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Master Lokasi
                            </h3>
                        </div>
                    </div>
                </div>
                <!--begin::Form-->
                <form class="m-form m-form--fit m-form--label-align-right">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <label for="nama_lokasi">Nama Lokasi</label>
                            <input type="text" class="form-control m-input" id="nama_lokasi" placeholder="">
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
        </div>
        <div class="col-lg-6">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon m--hide">
                                <i class="la la-gear"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Data Lokasi
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-2" style="font-size: 16px;">
                        <div style="display: flex; items-align: center;" class="mb-4">
                            <i class="flaticon-map-location mr-3" style="font-size: 20px;"></i>
                            <span class="mt-1">Sekuro , Luas : 1.000 m²</span>
                            <i style="font-size: 20px;" class="la la-plus-circle ml-3 mt-2 m--font-success " data-skin="dark" data-toggle="m-tooltip" data-placement="top" title="" data-original-title="Create Kelompok Kolam"></i>
                            <i style="font-size: 20px;" class="la la-edit ml-3 mt-2 m--font-warning " data-skin="dark" data-toggle="m-tooltip" data-placement="top" title="" data-original-title="Edit"></i>
                            <i style="font-size: 20px;" class="la la-remove ml-3 mt-2 m--font-danger " data-skin="dark" data-toggle="m-tooltip" data-placement="top" title="" data-original-title="Remove"></i>
                        </div>
                        <div class="m-2 ml-5">
                            <div style="display: flex; items-align: center;" class="mb-4">
                                <i class="flaticon-squares-4 mr-3"></i> 
                                <span class="mt-1">B1 , Luas : 1.000 m²</span> 
                                <i style="font-size: 20px;" class="la la-plus-circle ml-3 mt-2 m--font-success " data-skin="dark" data-toggle="m-tooltip" data-placement="top" title="" data-original-title="Create Kelompok Kolam"></i> 
                                <i style="font-size: 20px;" class="la la-edit ml-3 mt-2 m--font-warning " data-skin="dark" data-toggle="m-tooltip" data-placement="top" title="" data-original-title="Edit"></i> 
                                <i style="font-size: 20px;" class="la la-remove ml-3 mt-2 m--font-danger " data-skin="dark" data-toggle="m-tooltip" data-placement="top" title="" data-original-title="Remove"></i> 
                            </div>
                            <div class="m-2 ml-5"> 
                                <div style="display: flex; items-align: center;" class="mb-3"> 
                                <i class="flaticon-graphic-2 mr-3"></i> 
                                <span class="mt-1">B1 - Kolam 1 , Luas : 1.000 m²</span> 
                                <i style="font-size: 20px;" class="la la-edit ml-3 mt-2 m--font-warning " data-skin="dark" data-toggle="m-tooltip" data-placement="top" title="" data-original-title="Edit"></i> 
                                <i style="font-size: 20px;" class="la la-remove ml-3 mt-2 m--font-danger " data-skin="dark" data-toggle="m-tooltip" data-placement="top" title="" data-original-title="Remove"></i> 
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection



