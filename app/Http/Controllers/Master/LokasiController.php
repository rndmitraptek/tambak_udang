<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupLokasi;
use Yajra\DataTables\Facades\DataTables;

class LokasiController extends Controller
{
    //
    public function index()
    {
        return view('feature.master.lokasi.index');
    }

    public function data(Request $request)
    {
        $query = SetupLokasi::query();

        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editLokasi(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteLokasi(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Hapus">
                        <i class="m--font-danger la la-remove"></i>
                    </a>
                ';
            })
            ->rawColumns(['actions']) // biar HTML tombol tidak di-escape
            ->make(true);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:setup_lokasi,kode',
            'nama' => 'required',
            'alamat' => 'nullable',
        ]);

        $lokasi = SetupLokasi::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return response()->json(['success' => true, 'data' => $lokasi]);
    }

    // Tampilkan detail lokasi
    public function show($uuid)
    {
        $lokasi = SetupLokasi::where('uuid', $uuid)->firstOrFail();
        return response()->json($lokasi);
    }

    // Update data lokasi
    public function update(Request $request, $uuid)
    {
        $lokasi = SetupLokasi::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'kode' => 'required|unique:setup_lokasi,kode,' . $lokasi->id,
            'nama' => 'required',
            'alamat' => 'nullable',
        ]);

        $lokasi->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
        ]);

        return response()->json(['success' => true, 'data' => $lokasi]);
    }

    // Soft delete lokasi
    public function destroy($uuid)
    {
        $lokasi = SetupLokasi::where('uuid', $uuid)->firstOrFail();
        $lokasi->delete();
        return response()->json(['success' => true]);
    }
}
