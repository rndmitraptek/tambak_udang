<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupPakan;
use Yajra\DataTables\Facades\DataTables;

class PakanController extends Controller
{
    public function index()
    {
        return view('feature.master.pakan.index');
    }

    public function data(Request $request)
    {
        $query = SetupPakan::query()
            ->orderBy('id_pakan', 'desc');

        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editPakan(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deletePakan(\''.$row->uuid.'\')" 
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
            'kode_pakan' => 'required|unique:setup_pakan,kode_pakan',
            'nama_pakan' => 'required',
            'jenis_pakan' => 'required',
            'merk_pakan' => 'required',
            'satuan_pakan' => 'required',
            'harga_pakan' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $pakan = SetupPakan::create([
            'kode_pakan' => $request->kode_pakan,
            'nama_pakan' => $request->nama_pakan,
            'jenis_pakan' => $request->jenis_pakan,
            'merk_pakan' => $request->merk_pakan,
            'satuan_pakan' => $request->satuan_pakan,
            'harga_pakan' => $request->harga_pakan,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $pakan]);
    }

    public function show($uuid)
    {
        $pakan = SetupPakan::where('uuid', $uuid)->firstOrFail();
        return response()->json($pakan);
    }

    public function update(Request $request, $uuid)
    {
        $pakan = SetupPakan::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'kode_pakan' => 'required|unique:setup_pakan,kode_pakan,' . $pakan->id_pakan . ',id_pakan',
            'nama_pakan' => 'required',
            'jenis_pakan' => 'required',
            'merk_pakan' => 'required',
            'satuan_pakan' => 'required',
            'harga_pakan' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $pakan->update([
            'kode_pakan' => $request->kode_pakan,
            'nama_pakan' => $request->nama_pakan,
            'jenis_pakan' => $request->jenis_pakan,
            'merk_pakan' => $request->merk_pakan,
            'satuan_pakan' => $request->satuan_pakan,
            'harga_pakan' => $request->harga_pakan,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $pakan]);
    }

    public function destroy($uuid)
    {
        $pakan = SetupPakan::where('uuid', $uuid)->firstOrFail();
        $pakan->delete();
        return response()->json(['success' => true]);
    }
}