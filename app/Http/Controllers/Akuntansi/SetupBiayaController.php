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
        $data = DB::select("SELECT * FROM setup_coa where LENGTH(kode_coa)=5 AND (LEFT(kode_coa, 1)='5' OR LEFT(kode_coa, 1)='6') AND RIGHT(kode_coa, 1) <> '0' order by kode_coa",[]);
        // SetupCoa::whereRaw("LENGTH(kode_coa)=5 AND (LEFT(kode_coa, 1)='5' OR LEFT(kode_coa, 1)='6') AND RIGHT(kode_coa, 1) <> '0'")->get();
        return $data;
    }

    public function coaListKas()
    {
        $data = DB::select("SELECT * FROM setup_coa where LENGTH(kode_coa)=5 AND LEFT(kode_coa, 3) in ('112','111') AND RIGHT(kode_coa, 1) <> '0' OR kode_coa='11501' order by kode_coa",[]);
        // SetupCoa::whereRaw("LENGTH(kode_coa)=5 AND (LEFT(kode_coa, 1)='5' OR LEFT(kode_coa, 1)='6') AND RIGHT(kode_coa, 1) <> '0'")->get();
        return $data;
    }

    public function lokasiList()
    {
        return SetupLokasi::select('id_lokasi', 'nama_lokasi')->get();
    }

    public function petakList()
    {
        // return SetupPetak::select('id', 'nama')->get();
        return SetupPetak::select(
            'setup_petak.id_petak',
            'setup_petak.nama_petak',
            'setup_lokasi.nama_lokasi',
            'setup_blok.nama_blok'
        )
        ->join('setup_lokasi', 'setup_lokasi.id_lokasi', '=', 'setup_petak.lokasi_id')
        ->join('setup_blok', 'setup_blok.id_blok', '=', 'setup_petak.blok_id')
        ->get();
    }

    public function data(Request $request)
    {
        $query = SetupBiaya::with(['lokasi', 'coa'])->orderBy('id_biaya', 'desc');
        return DataTables::of($query)
            ->addColumn('nama_lokasi', function($row) {
                return $row->lokasi->count() 
                    ? $row->lokasi->pluck('nama_lokasi')->implode(', ') 
                    : '-';
            })
            ->addColumn('coa', function($row) {
                return $row->coa ? $row->coa->kode_coa . ' - ' . $row->coa->nama_coa : '-';
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
                'kode_biaya' => 'required|unique:setup_biaya,kode_biaya',
                'nama_biaya' => 'required',
                'kelompok_biaya' => 'required',
                'coa_id' => 'required|exists:setup_coa,id_coa',
                'nominal_biaya' => 'nullable|numeric',
            ]);
            $biaya = SetupBiaya::create([
                'kode_biaya'       => $request->kode_biaya,
                'nama_biaya'       => $request->nama_biaya,
                'kelompok_biaya'   => $request->kelompok_biaya,
                'petak_id'   => $request->kelompok_biaya == 'Perpetak' ? $request->petak_id : null,
                'periode_biaya'    => $request->periode_biaya ?? false,
                'nominal_biaya'    => $request->nominal_biaya,
                'coa_id'     => $request->coa_id,
                'catatan'    => $request->catatan,
            ]);

            // simpan lokasi
            if (in_array($request->kelompok_biaya, ['Gabungan', 'Perlokasi'])) {
                $lokasiIds = array_unique($request->lokasi ?? []);
                foreach ($lokasiIds as $lokasiId) {
                    SetupBiayaLokasi::create([
                        'biaya_id' => $biaya->id_biaya,
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
        $biaya = SetupBiaya::with(['lokasi:id_lokasi,nama_lokasi', 'coa:id_coa,kode_coa,nama_coa', 'petak:id_petak,nama_petak'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return response()->json([
            'uuid'      => $biaya->uuid,
            'kode_biaya'      => $biaya->kode_biaya,
            'nama_biaya'      => $biaya->nama_biaya,
            'kelompok_biaya'  => $biaya->kelompok_biaya,
            'periode_biaya'   => $biaya->periode_biaya,
            'nominal_biaya'   => $biaya->nominal_biaya,
            'coa_id'    => $biaya->coa_id,
            'catatan'   => $biaya->catatan,
            'petak_id'  => $biaya->petak_id,
            // array lokasi
            'lokasi'    => $biaya->lokasi->map(function($l) {
                return [
                    'id_lokasi'   => $l->id_lokasi,
                    'nama_lokasi' => $l->nama_lokasi,
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
                'kode_biaya' => 'required|unique:setup_biaya,kode_biaya,' . $biaya->id_biaya . ',id_biaya',
                'nama_biaya' => 'required',
                'kelompok_biaya' => 'required',
                'coa_id' => 'required|exists:setup_coa,id_coa',
                'nominal_biaya' => 'nullable|numeric',
            ]);
            $biaya->update([
                'kode_biaya'       => $request->kode_biaya,
                'nama_biaya'       => $request->nama_biaya,
                'kelompok_biaya'   => $request->kelompok_biaya,
                'petak_id'   => $request->kelompok_biaya == 'Perpetak' ? $request->petak_id : null,
                'periode_biaya'    => $request->periode_biaya ?? false,
                'nominal_biaya'    => $request->nominal_biaya,
                'coa_id'     => $request->coa_id,
                'catatan'    => $request->catatan,
            ]);

            // refresh lokasi
            SetupBiayaLokasi::where('biaya_id', $biaya->id_biaya)->forceDelete();
            if (in_array($request->kelompok_biaya, ['Gabungan', 'Perlokasi'])) {
                $lokasiIds = array_unique($request->lokasi ?? []);
                foreach ($lokasiIds as $lokasiId) {
                    SetupBiayaLokasi::create([
                        'biaya_id' => $biaya->id_biaya,
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