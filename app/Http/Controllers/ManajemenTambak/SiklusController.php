<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SetupSiklus;
use App\Models\SetupSiklusPetak;
use App\Models\SetupLokasi;
use App\Models\SetupPetak;
use Yajra\DataTables\Facades\DataTables;

class SiklusController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.siklus.index');
    }

    // Lokasi list untuk dropdown
    public function lokasiList()
    {
        $lokasi = SetupLokasi::select('id', 'nama')->get();
        return response()->json($lokasi);
    }

    // Petak list untuk datatable (filter by lokasi_id)
    public function petakList(Request $request)
    {
        $query = SetupPetak::with('blok')
            ->when($request->lokasi_id, function ($q) use ($request) {
                $q->where('lokasi_id', $request->lokasi_id);
            })
            ->select('id', 'nama', 'blok_id', 'luas', 'keterangan');

        return DataTables::of($query)
            ->addColumn('nama_blok', fn($row) => $row->blok->nama ?? '-')
            ->addColumn('nama_petak', fn($row) => $row->nama)
            ->make(true);
    }

    // Data untuk tabel siklus
    public function data()
    {
        $query = SetupSiklus::with('lokasi')->select('setup_siklus.*');

        return DataTables::of($query)
            ->addColumn('nama_lokasi', fn($row) => $row->lokasi->nama ?? '-')
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editSiklus(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteSiklus(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Hapus">
                        <i class="m--font-danger la la-remove"></i>
                    </a>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    // Store baru
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'lokasi_id' => 'required',
                'nama' => 'required',
                'tanggal_mulai' => 'required|date',
            ]);

            $siklus = SetupSiklus::create([
                'lokasi_id' => $request->lokasi_id,
                'nama' => $request->nama,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'catatan' => $request->catatan,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // simpan petak kalau ada
            if ($request->petak_id && is_array($request->petak_id)) {
                $siklus->petak()->sync($request->petak_id);
            }

            DB::commit();
            return response()->json(['success' => true, 'data' => $siklus]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Show untuk edit
    public function show($uuid)
    {
        $siklus = SetupSiklus::with('lokasi', 'petak')->where('uuid', $uuid)->first();
        return response()->json($siklus);
    }

    // Update
    public function update(Request $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $siklus = SetupSiklus::where('uuid', $uuid)->firstOrFail();

            $siklus->update([
                'lokasi_id' => $request->lokasi_id,
                'nama' => $request->nama,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'catatan' => $request->catatan,
                'updated_by' => 1,
            ]);

            // simpan petak kalau ada
            SetupSiklusPetak::where('siklus_id', $siklus->id)->forceDelete();
            if ($request->petak_id && is_array($request->petak_id)) {
                $siklus->petak()->sync($request->petak_id);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Delete
    public function delete($uuid)
    {
        $siklus = SetupSiklus::where('uuid', $uuid)->firstOrFail();
        $siklus->delete();

        return response()->json(['success' => true]);
    }
}
