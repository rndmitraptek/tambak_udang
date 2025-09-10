<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupSupplier;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        return view('feature.master.supplier.index');
    }

    public function data(Request $request)
    {
        $query = SetupSupplier::query();

        return DataTables::of($query)
            ->addColumn('actions', function ($row) {
                return '
                    <a href="javascript:void(0)" onclick="editSupplier(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteSupplier(\''.$row->uuid.'\')" 
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
            'kode' => 'required|unique:setup_supplier,kode',
            'nama' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'email' => 'nullable|email',
            'nama_perusahaan' => 'nullable',
            'catatan' => 'nullable',
        ]);

        $supplier = SetupSupplier::create([
            'kode'       => $request->kode,
            'nama'       => $request->nama,
            'alamat'     => $request->alamat,
            'telepon'    => $request->telepon,
            'email'      => $request->email,
            'nama_perusahaan' => $request->nama_perusahaan,
            'catatan'    => $request->catatan,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return response()->json(['success' => true, 'data' => $supplier]);
    }

    public function show($uuid)
    {
        $supplier = SetupSupplier::where('uuid', $uuid)->firstOrFail();
        return response()->json($supplier);
    }

    public function update(Request $request, $uuid)
    {
        $supplier = SetupSupplier::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'kode' => 'required|unique:setup_supplier,kode,' . $supplier->id,
            'nama' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'email' => 'nullable|email',
            'nama_perusahaan' => 'nullable',
            'catatan' => 'nullable',
        ]);

        $supplier->update($request->all());

        return response()->json(['success' => true, 'data' => $supplier]);
    }

    public function destroy($uuid)
    {
        $supplier = SetupSupplier::where('uuid', $uuid)->firstOrFail();
        $supplier->delete();
        return response()->json(['success' => true]);
    }
}