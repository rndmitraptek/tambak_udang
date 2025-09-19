<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupBenur;
use Yajra\DataTables\Facades\DataTables;

class BenurController extends Controller
{
    public function index()
    {
        return view('feature.master.benur.index');
    }

    public function data(Request $request)
    {
        $query = SetupBenur::query();

        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editBenur(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteBenur(\''.$row->uuid.'\')" 
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
            'kode_benur' => 'required|unique:setup_benur,kode_benur',
            'kode_supplier' => 'required',
            'jenis_benur' => 'required',
            'harga_benur' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $benur = SetupBenur::create([
            'kode_benur' => $request->kode_benur,
            'kode_supplier' => $request->kode_supplier,
            'jenis_benur' => $request->jenis_benur,
            'harga_benur' => $request->harga_benur,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function show($uuid)
    {
        $benur = SetupBenur::where('uuid', $uuid)->firstOrFail();
        return response()->json($benur);
    }

    public function update(Request $request, $uuid)
    {
        $benur = SetupBenur::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'kode_benur' => 'required|unique:setup_benur,kode_benur,' . $benur->id_benur . ',id_benur',
            'kode_supplier' => 'required',
            'jenis_benur' => 'required',
            'harga_benur' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $benur->update([
            'kode_benur' => $request->kode_benur,
            'kode_supplier' => $request->kode_supplier,
            'jenis_benur' => $request->jenis_benur,
            'harga_benur' => $request->harga_benur,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function destroy($uuid)
    {
        $benur = SetupBenur::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }
}