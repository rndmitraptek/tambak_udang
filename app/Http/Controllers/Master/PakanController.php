<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupPakan;

class PakanController extends Controller
{
    public function index()
    {
        return view('feature.master.pakan.index');
    }

    public function data(Request $request)
    {
        $columns = ['kode', 'nama', 'jenis', 'merk', 'satuan', 'harga', 'keterangan'];
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'kode';
        $orderDir = $request->input('order.0.dir', 'asc');

        $query = SetupPakan::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('satuan', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $total = SetupPakan::count();
        $filtered = $query->count();

        $data = $query->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'kode' => $row->kode,
                'nama' => $row->nama,
                'jenis' => $row->jenis,
                'merk' => $row->merk,
                'satuan' => $row->satuan,
                'harga' => $row->harga,
                'keterangan' => $row->keterangan,
                'actions' => '
                <a href="javascript:void(0)" onclick="editPakan(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" onclick="deletePakan(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Hapus"><i class="m--font-danger la la-remove"></i></a>',
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
            'kode' => 'required|unique:setup_pakan,kode',
            'nama' => 'required',
            'jenis' => 'required',
            'merk' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $pakan = SetupPakan::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'merk' => $request->merk,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'keterangan' => $request->keterangan,
            'created_by' => 1,
            'updated_by' => 1,
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
            'kode' => 'required|unique:setup_pakan,kode,' . $pakan->id,
            'nama' => 'required',
            'jenis' => 'required',
            'merk' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        $pakan->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'merk' => $request->merk,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
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