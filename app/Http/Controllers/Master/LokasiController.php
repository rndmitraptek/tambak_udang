<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupLokasi;

class LokasiController extends Controller
{
    //
    public function index()
    {
        return view('feature.master.lokasi.index');
    }

    public function data(Request $request)
    {
        $columns = ['kode', 'nama', 'alamat'];
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'kode';
        $orderDir = $request->input('order.0.dir', 'asc');
        
        $query = SetupLokasi::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode', 'ilike', "%{$search}%")
                ->orWhere('nama', 'ilike', "%{$search}%")
                ->orWhere('alamat', 'ilike', "%{$search}%");
            });
        }

        $total = SetupLokasi::count();
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
                'alamat' => $row->alamat,
                'actions' => '
                <a href="javascript:void(0)" onclick="editLokasi(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" onclick="deleteLokasi(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Hapus"><i class="m--font-danger la la-remove"></i></a>',
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $result,
        ]);
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
