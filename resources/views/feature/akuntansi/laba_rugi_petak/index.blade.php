@extends('layout')
@section('css')
<style>
    .lr-table th, .lr-table td { vertical-align: middle !important; white-space: nowrap; }
    .lr-table tfoot td { font-weight: 700; background: #f7f8fa; }
    .lr-link { cursor: pointer; text-decoration: underline dotted; }
    .lr-link:hover { text-decoration: underline; }
    .lr-minus { color: #f4516c !important; }
    .lr-plus { color: #34bfa3 !important; }
</style>
@endsection
@section('ctrl')
@include('feature.akuntansi.laba_rugi_petak.script')
@endsection

@section('content')
<div class="m-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Laporan Laba Rugi Per Petak
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label>Lokasi</label>
                            <select class="form-control" ng-model="form.lokasi_id" ng-change="loadSiklus()"
                                    ng-options="l.id_lokasi as l.nama_lokasi for l in lokasiList">
                                <option value="">--Pilih Lokasi--</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Siklus</label>
                            <select class="form-control" ng-model="form.siklus_id"
                                    ng-options="s.id_siklus as (s.nama_siklus + ' (' + (s.status || '-') + ')') for s in siklusList">
                                <option value="">--Pilih Siklus--</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="button" ng-click="getSummary()" class="btn btn-primary btn-block">
                                <i class="la la-search"></i> Tampilkan
                            </button>
                        </div>
                    </div>

                    <div ng-if="report">
                        <hr/>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h3>Laporan Laba Rugi Per Petak</h3>
                                <h5><% report.siklus.nama_lokasi %> &mdash; Siklus <% report.siklus.nama_siklus %></h5>
                                <span class="m--font-metal">Periode <% report.siklus.tanggal_mulai || '-' %> s/d <% report.siklus.tanggal_selesai || '-' %></span>
                            </div>
                        </div>
                        <div class="row mt-3 mb-2">
                            <div class="col-md-3 ml-auto">
                                <input type="text" class="form-control form-control-sm" ng-model="form.cari" placeholder="Cari blok / petak...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover lr-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width:40px">No</th>
                                        <th>Blok</th>
                                        <th>Petak</th>
                                        <th class="text-right">Luas (m&sup2;)</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-right">Biomassa Panen (kg)</th>
                                        <th class="text-right">Pendapatan</th>
                                        <th class="text-right">Biaya</th>
                                        <th class="text-right">Laba / Rugi</th>
                                        <th class="text-right">HPP / kg</th>
                                        <th class="text-right">Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="p in report.petak | filter:cariPetak">
                                        <td class="text-center"><% $index + 1 %></td>
                                        <td><% p.nama_blok %></td>
                                        <td><% p.nama_petak %></td>
                                        <td class="text-right"><% p.luas_petak | number:0 %></td>
                                        <td class="text-center">
                                            <span class="m-badge m-badge--wide"
                                                  ng-class="{'m-badge--success': p.status_panen=='FINAL', 'm-badge--info': p.status_panen=='AKTIF' || p.status_panen=='PARTIAL', 'm-badge--metal': p.status_panen=='-'}"><% p.status_panen %></span>
                                        </td>
                                        <td class="text-right"><% p.biomassa | number:2 %></td>
                                        <td class="text-right"><a class="lr-link m--font-info" ng-click="openCoa('pendapatan', p)"><% p.total_pendapatan | number:0 %></a></td>
                                        <td class="text-right"><a class="lr-link m--font-danger" ng-click="openCoa('biaya', p)"><% p.total_biaya | number:0 %></a></td>
                                        <td class="text-right m--font-bold" ng-class="p.laba_rugi < 0 ? 'lr-minus' : 'lr-plus'"><% p.laba_rugi | number:0 %></td>
                                        <td class="text-right"><% p.hpp_per_kg | number:0 %></td>
                                        <td class="text-right" ng-class="{'lr-minus': p.margin < 0}"><% p.margin | number:2 %>%</td>
                                    </tr>
                                    <tr ng-if="!report.petak.length">
                                        <td colspan="11" class="text-center m--font-metal">Tidak ada data petak pada siklus ini</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-center">TOTAL</td>
                                        <td class="text-right"><% report.total.luas_petak | number:0 %></td>
                                        <td></td>
                                        <td class="text-right"><% report.total.biomassa | number:2 %></td>
                                        <td class="text-right"><a class="lr-link m--font-info" ng-click="openCoa('pendapatan', null)"><% report.total.total_pendapatan | number:0 %></a></td>
                                        <td class="text-right"><a class="lr-link m--font-danger" ng-click="openCoa('biaya', null)"><% report.total.total_biaya | number:0 %></a></td>
                                        <td class="text-right" ng-class="report.total.laba_rugi < 0 ? 'lr-minus' : 'lr-plus'"><% report.total.laba_rugi | number:0 %></td>
                                        <td class="text-right"><% report.total.hpp_per_kg | number:0 %></td>
                                        <td class="text-right" ng-class="{'lr-minus': report.total.margin < 0}"><% report.total.margin | number:2 %>%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <small class="m--font-metal">
                            * Klik nilai Pendapatan / Biaya untuk melihat rincian per COA. Klik nilai pada baris TOTAL untuk rekap semua petak.<br/>
                            * Pendapatan diambil dari transaksi panen (actual). Biaya diambil dari seluruh transaksi biaya per petak (termasuk benur &amp; pakan) pada siklus ini.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal rincian per COA --}}
<div class="modal fade" id="m_lr_coa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Rincian <% coaModal.tipe == 'biaya' ? 'Biaya' : 'Pendapatan' %> Per COA
                    <br/><small class="m--font-metal"><% coaModal.label %></small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-hover lr-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width:40px">No</th>
                            <th>Kode COA</th>
                            <th>Nama COA</th>
                            <th class="text-right" ng-if="coaModal.tipe=='pendapatan'">Jumlah (kg)</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">%</th>
                            <th class="text-center" style="width:60px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="c in coaModal.rows">
                            <td class="text-center"><% $index + 1 %></td>
                            <td><% c.kode_coa %></td>
                            <td><% c.nama_coa %></td>
                            <td class="text-right" ng-if="coaModal.tipe=='pendapatan'"><% c.jumlah | number:2 %></td>
                            <td class="text-right"><a class="lr-link" ng-click="openDetail(c)"><% c.total | number:0 %></a></td>
                            <td class="text-right"><% (coaModal.total ? c.total / coaModal.total * 100 : 0) | number:2 %>%</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-info m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="Rincian" ng-click="openDetail(c)"><i class="la la-search"></i></button>
                            </td>
                        </tr>
                        <tr ng-if="!coaModal.rows.length">
                            <td colspan="7" class="text-center m--font-metal">Tidak ada data</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-center">TOTAL</td>
                            <td class="text-right" ng-if="coaModal.tipe=='pendapatan'"><% coaModal.jumlah | number:2 %></td>
                            <td class="text-right"><% coaModal.total | number:0 %></td>
                            <td class="text-right"><% coaModal.rows.length ? 100 : 0 | number:2 %>%</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal rincian transaksi per COA --}}
<div class="modal fade" id="m_lr_detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 90vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Rincian <% detailModal.coa.kode_coa %> - <% detailModal.coa.nama_coa %>
                    <br/><small class="m--font-metal"><% coaModal.label %></small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="max-height: 65vh;">
                    {{-- rincian biaya --}}
                    <table class="table table-sm table-bordered table-hover lr-table" ng-if="coaModal.tipe=='biaya'">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th>Tanggal</th>
                                <th>No Transaksi</th>
                                <th ng-if="!coaModal.petak">Blok / Petak</th>
                                <th>Nama Biaya</th>
                                <th>Keterangan</th>
                                <th>Periode Biaya</th>
                                <th class="text-right">%</th>
                                <th class="text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="d in detailModal.rows">
                                <td class="text-center"><% $index + 1 %></td>
                                <td><% d.tanggal %></td>
                                <td><% d.no_transaksi %></td>
                                <td ng-if="!coaModal.petak"><% d.nama_blok %> / <% d.nama_petak %></td>
                                <td><% d.nama_biaya %></td>
                                <td style="white-space: normal;"><% d.keterangan %></td>
                                <td><% d.tanggal_mulai %> s/d <% d.tanggal_selesai %></td>
                                <td class="text-right"><% d.persentase | number:2 %></td>
                                <td class="text-right"><% d.nominal | number:0 %></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="<% coaModal.petak ? 7 : 8 %>" class="text-center">TOTAL</td>
                                <td class="text-right"><% detailModal.total | number:0 %></td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- rincian pendapatan --}}
                    <table class="table table-sm table-bordered table-hover lr-table" ng-if="coaModal.tipe=='pendapatan'">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width:40px">No</th>
                                <th>Tanggal Panen</th>
                                <th>No Panen</th>
                                <th ng-if="!coaModal.petak">Blok / Petak</th>
                                <th>Jenis Panen</th>
                                <th>Customer</th>
                                <th>Item</th>
                                <th class="text-right">Jumlah (kg)</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="d in detailModal.rows">
                                <td class="text-center"><% $index + 1 %></td>
                                <td><% d.tanggal %></td>
                                <td><% d.no_panen %></td>
                                <td ng-if="!coaModal.petak"><% d.nama_blok %> / <% d.nama_petak %></td>
                                <td><% d.jenis_panen %></td>
                                <td><% d.nama_customer %></td>
                                <td><% d.nama_item %></td>
                                <td class="text-right"><% d.jumlah | number:2 %></td>
                                <td class="text-right"><% d.harga | number:0 %></td>
                                <td class="text-right"><% d.nominal | number:0 %></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="<% coaModal.petak ? 6 : 7 %>" class="text-center">TOTAL</td>
                                <td class="text-right"><% detailModal.jumlah | number:2 %></td>
                                <td></td>
                                <td class="text-right"><% detailModal.total | number:0 %></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
