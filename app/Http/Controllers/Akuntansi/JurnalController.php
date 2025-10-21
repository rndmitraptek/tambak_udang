<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\Akuntansi\JurnalDetailModel;
use App\Models\Akuntansi\JurnalModel;
use App\Models\SetupCoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JurnalController extends Controller
{
    //
    public function index()
    {
        return view('feature.akuntansi.jurnal.index');
    }

    public function buku_besar()
    {
        return view('feature.akuntansi.buku_besar.index');
    }


    public function get_coa(Request $request){
        $query = SetupCoa::select(['id_coa','uuid','kode_coa','nama_coa','tipe_coa','pos_laporan','saldo_normal']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(kode_coa)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(nama_coa)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function insert(Request $req){
        DB::beginTransaction();
        try {
            $req->validate([
                'no_bukti' => 'required',
                'tanggal'    => 'required',
                'keterangan'   => 'required',
            ]);
            $data = $req->all();
            $insert = JurnalModel::create($data);
            foreach($req->detail as $d){
                $detail = $d;
                $detail['id_jurnal']    = $insert->id_jurnal;
                $insert_detail = JurnalDetailModel::create($detail);
            }
            DB::commit();
            return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
        }catch(\Exception $err) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>$err->getMessage()]);
        }
    }

    public function jurnal_umum(Request $req){
        $data = JurnalDetailModel::join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
        ->whereBetween('jurnal.tanggal', [$req->tanggal_mulai, $req->tanggal_selesai])
        ->select('jurnal_detail.*', 'jurnal.tanggal', 'jurnal.keterangan', 'jurnal.no_bukti')
        ->get();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_buku_besar(Request $req){
        // Hitung saldo awal (sebelum tanggal mulai)
        $saldo_awal = JurnalDetailModel::join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
            ->where('jurnal_detail.kode_coa', $req->kode_coa)
            ->where('jurnal.tanggal', '<', $req->tanggal_mulai)
            ->select(
                DB::raw('SUM(jurnal_detail.debit) as total_debit'),
                DB::raw('SUM(jurnal_detail.kredit) as total_kredit')
            )
            ->first();
        if($req->saldo_normal=='Debit'){
            $saldo_awal_value = $saldo_awal->total_debit - $saldo_awal->total_kredit;
        }else{
            $saldo_awal_value =  $saldo_awal->total_kredit - $saldo_awal->total_debit;
        }

        $jurnal = JurnalDetailModel::join('jurnal', 'jurnal.id_jurnal', '=', 'jurnal_detail.id_jurnal')
        ->whereBetween('jurnal.tanggal', [$req->tanggal_mulai, $req->tanggal_selesai])
        ->where('jurnal_detail.kode_coa', 'like', $req->kode_coa.'%')
        ->select('jurnal_detail.*', 'jurnal.tanggal', 'jurnal.keterangan', 'jurnal.no_bukti')
        ->orderBy('jurnal.tanggal', 'asc')
        ->get();

        $data[] = [
            'kode_coa'      => '',
            'nama_coa'      => '',
            'tanggal'       => $req->tanggal_mulai,
            'no_bukti'      => '-',
            'keterangan'    => 'Saldo Awal tanggal '.$req->tanggal_mulai,
            'debit'         => ($req->saldo_normal=='Debit')?$saldo_awal_value:0,
            'kredit'        => ($req->saldo_normal=='Kredit')?$saldo_awal_value:0,
            'saldo'         => $saldo_awal_value
        ];
        $saldo = $saldo_awal_value;
        foreach( $jurnal as  $key=>$val){
            if($req->saldo_normal=='Debit'){
                $saldo = ($saldo + $val->debit) - $val->kredit;
            }else{
                $saldo = ($saldo + $val->kredit) - $val->debit;
            }
            $data[]=[
                'kode_coa'      => $val->kode_coa,
                'nama_coa'      => $val->nama_coa,
                'tanggal'       => $val->tanggal,
                'no_bukti'      => $val->no_bukti,
                'keterangan'    => $val->keterangan,
                'debit'         => $val->debit,
                'kredit'        => $val->kredit,
                'saldo'         => $saldo
            ];
        }
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }
}
