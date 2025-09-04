<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupBlok;
use App\Models\SetupLokasi;

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
        $columns = ['nama_lokasi', 'nama', 'keterangan'];
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'nama_lokasi';
        $orderDir = $request->input('order.0.dir', 'asc');

        $query = SetupBlok::with('lokasi');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'ilike', "%{$search}%")
                  ->orWhere('keterangan', 'ilike', "%{$search}%");
            });
        }

        $total = SetupBlok::count();
        $filtered = $query->count();

        $data = $query->orderBy($orderColumn == 'nama_lokasi' ? 'lokasi_id' : $orderColumn, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'nama_lokasi' => $row->lokasi ? $row->lokasi->nama : '',
                'nama' => $row->nama,
                'keterangan' => $row->keterangan,
                'actions' => '
                <a href="javascript:void(0)" onclick="editBlok(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" onclick="deleteBlok(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Hapus"><i class="m--font-danger la la-remove"></i></a>',
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
            'lokasi_id' => 'required|exists:setup_lokasi,id',
            'nama' => 'required',
            'keterangan' => 'nullable',
        ]);

        $blok = SetupBlok::create([
            'lokasi_id' => $request->lokasi_id,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $blok]);
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
