<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SetupPetak;
use App\Models\SetupBlok;
use App\Models\SetupLokasi;

class PetakController extends Controller
{
    public function index()
    {
        return view('feature.master.petak.index');
    }

    public function lokasiList()
    {
        return SetupLokasi::select('id', 'nama')->get();
    }

    public function blokListByLokasi($lokasi_id)
    {
        return \App\Models\SetupBlok::where('lokasi_id', $lokasi_id)->select('id', 'nama')->get();
    }

    public function blokList()
    {
        return SetupBlok::select('id', 'nama')->get();
    }

    public function data(Request $request)
    {
        $columns = ['nama_lokasi', 'nama_blok', 'nama', 'luas', 'keterangan'];
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);
        $search = $request->input('search.value', '');

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderColumn = $columns[$orderColumnIndex] ?? 'nama_lokasi';
        $orderDir = $request->input('order.0.dir', 'asc');

        $query = SetupPetak::with(['lokasi', 'blok']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $total = SetupPetak::count();
        $filtered = $query->count();

        $data = $query->orderBy($orderColumn == 'nama_lokasi' ? 'lokasi_id' : ($orderColumn == 'nama_blok' ? 'blok_id' : $orderColumn), $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'nama_lokasi' => $row->lokasi ? $row->lokasi->nama : '',
                'nama_blok' => $row->blok ? $row->blok->nama : '',
                'nama' => $row->nama,
                'luas' => $row->luas,
                'keterangan' => $row->keterangan,
                'actions' => '
                <a href="javascript:void(0)" onclick="editPetak(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" onclick="deletePetak(\''.$row->uuid.'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Hapus"><i class="m--font-danger la la-remove"></i></a>',
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
            'blok_id' => 'required|exists:setup_blok,id',
            'nama' => 'required',
            'luas' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        $petak = SetupPetak::create([
            'lokasi_id' => $request->lokasi_id,
            'blok_id' => $request->blok_id,
            'nama' => $request->nama,
            'luas' => $request->luas,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $petak]);
    }

    public function show($uuid)
    {
        $petak = SetupPetak::where('uuid', $uuid)->firstOrFail();
        return response()->json($petak);
    }

    public function update(Request $request, $uuid)
    {
        $petak = SetupPetak::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'lokasi_id' => 'required|exists:setup_lokasi,id',
            'blok_id' => 'required|exists:setup_blok,id',
            'nama' => 'required',
            'luas' => 'nullable',
            'keterangan' => 'nullable',
        ]);

        $petak->update([
            'lokasi_id' => $request->lokasi_id,
            'blok_id' => $request->blok_id,
            'nama' => $request->nama,
            'luas' => $request->luas,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json(['success' => true, 'data' => $petak]);
    }

    public function destroy($uuid)
    {
        $petak = SetupPetak::where('uuid', $uuid)->firstOrFail();
        $petak->delete();
        return response()->json(['success' => true]);
    }
}