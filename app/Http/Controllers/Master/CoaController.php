<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupCoa;
use Yajra\DataTables\Facades\DataTables;

class CoaController extends Controller
{
    //
    public function index()
    {
        return view('feature.master.coa.index');
    }

    public function data(Request $request)
    {
        $query = SetupCoa::query();

        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editCoa(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteCoa(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Hapus">
                        <i class="m--font-danger la la-remove"></i>
                    </a>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function parentList()
    {
        $coas = SetupCoa::get();
        return response()->json(['success' => true, 'data' => $coas]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_coa' => 'required|unique:setup_coa,kode_coa',
            'nama_coa' => 'required',
            'tipe_coa' => 'required',
            'pos_laporan' => 'required',
            'kode_parent' => 'nullable',
            'saldo_normal' => 'nullable',
        ]);

        $coa = SetupCoa::create([
            'kode_coa' => $request->kode_coa,
            'nama_coa' => $request->nama_coa,
            'tipe_coa' => $request->tipe_coa,
            'pos_laporan' => $request->pos_laporan,
            'kode_parent' => $request->kode_parent,
            'saldo_normal' => $request->saldo_normal,
        ]);

        return response()->json(['success' => true, 'data' => $coa]);
    }

    public function show($uuid)
    {
        $coa = SetupCoa::where('uuid', $uuid)->firstOrFail();
        return response()->json($coa);
    }

    public function update(Request $request, $uuid)
    {
        $coa = SetupCoa::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'kode_coa' => 'required|unique:setup_coa,kode_coa,' . $coa->id_coa . ',id_coa',
            'nama_coa' => 'required',
            'tipe_coa' => 'required',
            'pos_laporan' => 'required',
            'kode_parent' => 'nullable',
            'saldo_normal' => 'nullable',
        ]);

        $coa->update([
            'kode_coa' => $request->kode_coa,
            'nama_coa' => $request->nama_coa,
            'tipe_coa' => $request->tipe_coa,
            'pos_laporan' => $request->pos_laporan,
            'kode_parent' => $request->kode_parent,
            'saldo_normal' => $request->saldo_normal,
        ]);

        return response()->json(['success' => true, 'data' => $coa]);
    }

    public function destroy($uuid)
    {
        $coa = SetupCoa::where('uuid', $uuid)->firstOrFail();
        $coa->delete();
        return response()->json(['success' => true]);
    }
}
