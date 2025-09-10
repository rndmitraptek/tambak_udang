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

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:setup_coa,kode',
            'nama' => 'required',
            'tipe' => 'required',
            'pos_laporan' => 'required',
            'kode_parent' => 'nullable',
            'saldo_normal' => 'nullable',
        ]);

        $coa = SetupCoa::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'pos_laporan' => $request->pos_laporan,
            'kode_parent' => $request->kode_parent,
            'saldo_normal' => $request->saldo_normal,
            'created_by' => 1,
            'updated_by' => 1,
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
            'kode' => 'required|unique:setup_benur,kode,' . $coa->id,
            'nama' => 'required',
            'tipe' => 'required',
            'pos_laporan' => 'required',
            'kode_parent' => 'nullable',
            'saldo_normal' => 'nullable',
        ]);

        $coa->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tipe' => $request->tipe,
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
