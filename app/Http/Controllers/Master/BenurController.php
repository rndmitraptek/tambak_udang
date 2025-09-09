<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupBenur;

class BenurController extends Controller
{
    public function index()
    {
        return view('feature.master.benur.index');
    }

    public function data(Request $request)
    {
        $columns = ['kode', 'kode_supplier', 'jenis', 'harga', 'keterangan'];
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'kode';
        $orderDir = $request->input('order.0.dir', 'asc');

        $query = SetupBenur::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('kode_supplier', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $total = SetupBenur::count();
        $filtered = $query->count();

        $data = $query->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'kode' => $row->kode,
                'kode_supplier' => $row->kode_supplier,
                'jenis' => $row->jenis,
                'harga' => $row->harga,
                'keterangan' => $row->keterangan,
                'actions' => '
                <a href="javascript:void(0)" onclick="editBenur(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" onclick="deleteBenur(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Hapus"><i class="m--font-danger la la-remove"></i></a>',
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $result,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:setup_benur,kode',
            'kode_supplier' => 'required',
            'jenis' => 'required',
            'harga' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $benur = SetupBenur::create([
            'kode' => $request->kode,
            'kode_supplier' => $request->kode_supplier,
            'jenis' => $request->jenis,
            'harga' => $request->harga,
            'keterangan' => $request->keterangan,
            'created_by' => 1,
            'updated_by' => 1,
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
            'kode' => 'required|unique:setup_benur,kode,' . $benur->id,
            'kode_supplier' => 'required',
            'jenis' => 'required',
            'harga' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $benur->update([
            'kode' => $request->kode,
            'kode_supplier' => $request->kode_supplier,
            'jenis' => $request->jenis,
            'harga' => $request->harga,
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