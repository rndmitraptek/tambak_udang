<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupBlok;
use App\Models\SetupLokasi;
use Yajra\DataTables\Facades\DataTables;

class BlokController extends Controller
{
    //
    public function index()
    {
        return view('feature.master.blok.index');
    }

    public function lokasiList()
    {
        return SetupLokasi::select('id', 'nama')->get();
    }

    public function data(Request $request)
    {
        $query = SetupBlok::with('lokasi');

        return DataTables::of($query)
            ->addColumn('nama_lokasi', function ($row) {
                return $row->lokasi ? $row->lokasi->nama : '';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editBlok(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteBlok(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Hapus">
                        <i class="m--font-danger la la-remove"></i>
                    </a>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show($uuid)
    {
        $blok = SetupBlok::where('uuid', $uuid)->firstOrFail();
        return response()->json($blok);
    }

    public function update(Request $request, $uuid)
    {
        $blok = SetupBlok::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'lokasi_id' => 'required|exists:setup_lokasi,id',
            'nama' => 'required',
            'keterangan' => 'nullable',
        ]);

        $blok->update([
            'lokasi_id' => $request->lokasi_id,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $blok]);
    }

    public function destroy($uuid)
    {
        $blok = SetupBlok::where('uuid', $uuid)->firstOrFail();
        $blok->delete();
        return response()->json(['success' => true]);
    }
}
