<?php

namespace App\Http\Controllers;

use App\Models\SetupSiklus;
use App\Models\SetupSiklusPetak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view('feature.dashboard.index');
    }

    public function siklus(){
        $siklus = SetupSiklus::with('lokasi')->where('status','OPEN')->get();
        foreach($siklus as $key=>$data){
            $siklus[$key]->data = DB::select("
            SELECT CONCAT('(',id_simulasi,') ',tanggal_simulasi) as x,sum(total_pendapatan) as pendapatan,sum(total_biaya) as biaya,sum(laba_rugi) as y FROM transaksi_simulasi_detail 
            where siklus_id = ?
            group by id_simulasi,tanggal_simulasi order by id_simulasi",[$data->id_siklus]);
        }
        return response()->json(['success'=>true,'data'=>$siklus,'message'=>'']);
    }

    public function dashboard_petak($uuid_siklus){
        return view('feature.dashboard_petak.index')->with([
            'uuid_siklus'=>$uuid_siklus
        ]);
    }

    public function siklus_petak($uuid_siklus){
        // dd($uuid_siklus);
        $siklus = SetupSiklus::where('uuid', $uuid_siklus)->first();
        // dd($siklus);
        $petak = SetupSiklusPetak::with('petak.blok')->where('siklus_id',$siklus->id_siklus)->get();
        // dd($petak);
        foreach($petak as $key=>$data){
            $petak[$key]->data = DB::select("
            SELECT CONCAT('(',id_simulasi,') ',tanggal_simulasi) as x,sum(total_pendapatan) as pendapatan,sum(total_biaya) as biaya,sum(laba_rugi) as y FROM transaksi_simulasi_detail 
            where petak_id = ?
            group by id_simulasi,tanggal_simulasi order by id_simulasi",[$data->petak_id]);
        }
        return response()->json(['success'=>true,'data'=>$petak,'message'=>'']);
    }
}
