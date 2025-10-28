@extends('layout')
@section('css')
	<link href="<% url('/') %>/template/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('ctrl')
@include('feature.master.coa.script')
@endsection

@section('content')
<!-- BEGIN: Subheader -->

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
                                COA
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <button id="btnTambah" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air">
                                    <span>
                                        <i class="la la-plus"></i>
                                        <span>Tambah COA</span>
                                    </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <table class="table table-sm table-striped- table-bordered table-hover m-table m-table--head-bg-info" id="viewtabel">
                        <thead>
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Tipe Akun</th>
                            <th>Pos Laporan</th>
                            <th>Saldo Normal</th>
                            <th>Kode Parent</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        {{-- <tbody>
                        <tr ng-repeat="akun in coa | filter:searchText">
                            <td style="width:150px"><%akun.kode_akun%></td>
                            <td><%akun.nama_akun%></td>
                            <td><%akun.tipe_akun%></td>
                            <td><%akun.pos_laporan%></td>
                            <td><%akun.saldo_normal%></td>
                            <td><%akun.kode_parent || '-'%></td>
                            <td style="width:140px">
                                <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-edit m--font-warning"></i></a>
                                <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-remove m--font-danger"></i></a>
                                <button ng-click="tambah()" ng-show="akun.kode_parent" href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-plus m--font-primary"></i></button>
                            </td>
                        </tr>
                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="m_create" tabindex="-1" role="dialog" aria-labelledby="modalCoaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalCoaLabel">Tambah / Edit Data COA</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <form id="formCoa" autocomplete="off">
        <div class="modal-body">
          
          <!-- hidden id -->
          <input type="hidden" id="uuid" name="uuid">

          <div class="form-group row">
            <label class="col-md-4 col-form-label">Kode COA <span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="kode_coa" name="kode_coa" placeholder="Masukkan kode COA">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">Nama COA <span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="nama_coa" name="nama_coa" placeholder="Masukkan nama COA">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">Tipe COA <span class="text-danger">*</span></label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="tipe_coa" name="tipe_coa" placeholder="Masukkan tipe COA">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">Pos Laporan <span class="text-danger">*</span></label>
            <div class="col-md-8">
              <select class="form-control" id="pos_laporan" name="pos_laporan">
                <option value="">-- Pilih Pos Laporan --</option>
                <option value="Neraca">Neraca</option>
                <option value="Laba Rugi">Laba Rugi</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">Saldo Normal <span class="text-danger">*</span></label>
            <div class="col-md-8">
              <select class="form-control" id="saldo_normal" name="saldo_normal">
                <option value="">-- Pilih Saldo Normal --</option>
                <option value="Debit">Debit</option>
                <option value="Kredit">Kredit</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-form-label">Kode Parent <span class="text-danger">*</span></label>
            <div class="col-md-8">
              <select class="form-control" id="kode_parent" name="kode_parent">
                <option value="">-- Pilih Parent --</option>
                <!-- Data parent COA diisi via AJAX -->
              </select>
            </div>
          </div>

        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="la la-times"></i> Tutup
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="la la-save"></i> Simpan
          </button>
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
<script src="{{ url('/') }}/template/assets/src/jquery.validate.min.js"></script>
<!--end::Page Vendors -->

<!--end::Page Resources -->
@endsection



