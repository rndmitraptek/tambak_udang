<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupPakan;
use App\Models\StokPakan;
use App\Models\HistoryKartuStok;
use Yajra\DataTables\Facades\DataTables;

class StokPakanController extends Controller
{
    public function index()
    {
        return view('feature.manajemen-tambak.stok_pakan.index');
    }

    public function data(Request $request)
    {
        $query = StokPakan::select(
                'stok_pakan.id_stok_pakan',
                'stok_pakan.uuid',
                'stok_pakan.pakan_id',
                'stok_pakan.stok',
                'stok_pakan.lokasi_id',
                'stok_pakan.created_at',
                'stok_pakan.updated_at',
                'setup_lokasi.nama_lokasi',
                'setup_pakan.nama_pakan',
                'setup_pakan.kode_pakan',
                'setup_pakan.jenis_pakan',
                'setup_pakan.satuan_pakan',
                'setup_pakan.merk_pakan',
            )
            ->join('setup_pakan', 'setup_pakan.id_pakan', '=', 'stok_pakan.pakan_id')
            ->join('setup_lokasi', 'setup_lokasi.id_lokasi', '=', 'stok_pakan.lokasi_id')
            ->orderBy('setup_pakan.nama_pakan', 'desc');

        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-info m-btn--icon m-btn--icon-only m-btn--pill btn-detail" 
                        data-uuid="'.$row->uuid.'"
                        title="Detail">
                        <i class="la la-eye"></i>
                    </a>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function show($uuid)
    {
        $stok = StokPakan::where('uuid', $uuid)->firstOrFail();

        $history = HistoryKartuStok::with(['pakan', 'lokasi'])
            ->where('id_pakan', $stok->pakan_id)
            ->where('id_lokasi', $stok->lokasi_id)
            ->orderBy('id_kartu', 'desc');

        return datatables()->eloquent($history)
            ->addColumn('pakan', fn($row) => $row->pakan->nama_pakan ?? '-')
            ->addColumn('lokasi', fn($row) => $row->lokasi->nama_lokasi ?? '-')
            ->toJson();
    }

}