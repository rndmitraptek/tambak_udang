<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SetupBiaya;
use App\Models\SetupBiayaLokasi;
use App\Models\SetupLokasi;
use App\Models\SetupPetak;
use App\Models\SetupCoa;
use Yajra\DataTables\Facades\DataTables;

class SetupBiayaController extends Controller
{
    public function index()
    {
        return view('feature.akuntansi.setup-biaya.index');
    }

    public function coaList()
    {
        return SetupCoa::select('id', 'kode', 'nama')->get();
    }

    public function lokasiList()
    {
        return SetupLokasi::select('id', 'nama')->get();
    }

    public function petakList()
    {
        // return SetupPetak::select('id', 'nama')->get();
        return SetupPetak::select(
            'setup_petak.id',
            'setup_petak.nama as nama_petak',
            'setup_lokasi.nama as nama_lokasi',
            'setup_blok.nama as nama_blok'
        )
        ->join('setup_lokasi', 'setup_lokasi.id', '=', 'setup_petak.lokasi_id')
        ->join('setup_blok', 'setup_blok.id', '=', 'setup_petak.blok_id')
        ->get();
    }

    public function data(Request $request)
    {
        $query = SetupBiaya::with(['lokasi', 'coa']);
        return DataTables::of($query)
            ->addColumn('nama_lokasi', function($row) {
                return $row->lokasi->count() 
                    ? $row->lokasi->pluck('nama')->implode(', ') 
                    : '-';
            })
            ->addColumn('coa', function($row) {
                return $row->coa ? $row->coa->kode . ' - ' . $row->coa->nama : '-';
            })
            ->addColumn('periode', function($row) {
                return $row->periode ? 'Ya' : 'Tidak';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editBiaya(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteBiaya(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Hapus">
                        <i class="m--font-danger la la-remove"></i>
                    </a>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'kode' => 'required|unique:setup_biaya,kode',
                'nama' => 'required',
                'kelompok' => 'required',
                'coa_id' => 'required|exists:setup_coa,id',
                'nominal' => 'nullable|numeric',
            ]);
            $biaya = SetupBiaya::create([
                'kode'       => $request->kode,
                'nama'       => $request->nama,
                'kelompok'   => $request->kelompok,
                'petak_id'   => $request->kelompok == 'Perpetak' ? $request->petak_id : null,
                'periode'    => $request->periode ?? false,
                'nominal'    => $request->nominal,
                'coa_id'     => $request->coa_id,
                'catatan'    => $request->catatan,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // simpan lokasi
            if (in_array($request->kelompok, ['Gabungan', 'Perlokasi'])) {
                foreach ($request->lokasi ?? [] as $lokasiId) {
                    SetupBiayaLokasi::create([
                        'biaya_id' => $biaya->id,
                        'lokasi_id'  => $lokasiId,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // public function show($uuid)
    // {
    //     $biaya = SetupBiaya::where('uuid', $uuid)->firstOrFail();
    //     return response()->json($biaya);
    // }
    public function show($uuid)
    {
        $biaya = SetupBiaya::with(['lokasi:id,nama', 'coa:id,kode,nama', 'petak:id,nama'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return response()->json([
            'uuid'      => $biaya->uuid,
            'kode'      => $biaya->kode,
            'nama'      => $biaya->nama,
            'kelompok'  => $biaya->kelompok,
            'periode'   => $biaya->periode,
            'nominal'   => $biaya->nominal,
            'coa_id'    => $biaya->coa_id,
            'catatan'   => $biaya->catatan,
            'petak_id'  => $biaya->petak_id,
            // array lokasi
            'lokasi'    => $biaya->lokasi->map(function($l) {
                return [
                    'id'   => $l->id,
                    'nama' => $l->nama,
                ];
            }),
        ]);
    }

    public function update(Request $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $biaya = SetupBiaya::where('uuid', $uuid)->firstOrFail();
            $request->validate([
                'kode' => 'required|unique:setup_biaya,kode,' . $biaya->id,
                'nama' => 'required',
                'kelompok' => 'required',
                'coa_id' => 'required|exists:setup_coa,id',
                'nominal' => 'nullable|numeric',
            ]);
            $biaya->update([
                'kode'       => $request->kode,
                'nama'       => $request->nama,
                'kelompok'   => $request->kelompok,
                'petak_id'   => $request->kelompok == 'Perpetak' ? $request->petak_id : null,
                'periode'    => $request->periode ?? false,
                'nominal'    => $request->nominal,
                'coa_id'     => $request->coa_id,
                'catatan'    => $request->catatan,
                'updated_by' => 1,
            ]);

            // refresh lokasi
            SetupBiayaLokasi::where('biaya_id', $biaya->id)->forceDelete();
            if (in_array($request->kelompok, ['Gabungan', 'Perlokasi'])) {
                foreach ($request->lokasi ?? [] as $lokasiId) {
                    SetupBiayaLokasi::create([
                        'biaya_id' => $biaya->id,
                        'lokasi_id'  => $lokasiId,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($uuid)
    {
        $biaya = SetupBiaya::where('uuid', $uuid)->firstOrFail();
        $biaya->delete();
        return response()->json(['success' => true]);
    }
}