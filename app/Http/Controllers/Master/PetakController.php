<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupPetak;
use App\Models\SetupBlok;
use App\Models\SetupLokasi;
use Yajra\DataTables\Facades\DataTables;

class PetakController extends Controller
{
    public function index()
    {
        return view('feature.master.petak.index');
    }

    public function lokasiList()
    {
        return SetupLokasi::select('id', 'nama')->get();
    }

    public function blokListByLokasi($lokasi_id)
    {
        return \App\Models\SetupBlok::where('lokasi_id', $lokasi_id)->select('id', 'nama')->get();
    }

    public function blokList()
    {
        return SetupBlok::select('id', 'nama')->get();
    }

    public function data(Request $request)
    {
        $query = SetupPetak::with(['lokasi', 'blok']);

        return DataTables::of($query)
            ->addColumn('nama_lokasi', function ($row) {
                return $row->lokasi ? $row->lokasi->nama : '';
            })
            ->addColumn('nama_blok', function ($row) {
                return $row->blok ? $row->blok->nama : '';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editPetak(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deletePetak(\''.$row->uuid.'\')" 
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
        $request->validate([
            'lokasi_id' => 'required|exists:setup_lokasi,id',
            'blok_id' => 'required|exists:setup_blok,id',
            'nama' => 'required',
            'luas' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        $petak = SetupPetak::create([
            'lokasi_id' => $request->lokasi_id,
            'blok_id' => $request->blok_id,
            'nama' => $request->nama,
            'luas' => $request->luas,
            'keterangan' => $request->keterangan,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return response()->json(['success' => true, 'data' => $petak]);
    }

    public function show($uuid)
    {
        $petak = SetupPetak::where('uuid', $uuid)->firstOrFail();
        return response()->json($petak);
    }

    public function update(Request $request, $uuid)
    {
        $petak = SetupPetak::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'lokasi_id' => 'required|exists:setup_lokasi,id',
            'blok_id' => 'required|exists:setup_blok,id',
            'nama' => 'required',
            'luas' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        $petak->update([
            'lokasi_id' => $request->lokasi_id,
            'blok_id' => $request->blok_id,
            'nama' => $request->nama,
            'luas' => $request->luas,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $petak]);
    }

    public function destroy($uuid)
    {
        $petak = SetupPetak::where('uuid', $uuid)->firstOrFail();
        $petak->delete();
        return response()->json(['success' => true]);
    }
}