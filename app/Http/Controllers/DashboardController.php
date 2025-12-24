<?php

namespace App\Http\Controllers;

use App\Models\SetupSiklus;
use App\Models\SetupSiklusPetak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $isDashboard=false;
        $user = Auth::user();
        $getRoles = DB::table('role_user')
                ->join('role','role.id_role','role_user.id_role')
                ->select('role.*')
                ->where('role_user.id_user', $user->id_user)->get();

        foreach ($getRoles as $role) {
            if (!empty($role->dashboard) && $role->dashboard == true) {
                $isDashboard = true;
                break; // cukup satu yang true sudah cukup
            }
        }

        return view('feature.dashboard.index', compact('isDashboard'));
    }

    public function siklus(){
        $siklus = SetupSiklus::with('lokasi')->where('status','OPEN')->get();
        foreach($siklus as $key=>$data){
            $siklus[$key]->data = DB::select("
            SELECT CONCAT('(',tsd.id_simulasi,') ',tsd.tanggal_simulasi) as x,sum(tsd.total_pendapatan) as pendapatan,sum(tsd.total_biaya) as biaya,sum(tsd.laba_rugi) as y FROM transaksi_simulasi_detail tsd
            inner join transaksi_simulasi ts on ts.id_simulasi=tsd.id_simulasi
            where ts.siklus_id = ?
			group by tsd.id_simulasi,tsd.tanggal_simulasi order by tsd.id_simulasi",[$data->id_siklus]);
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
            SELECT CONCAT('(',tsd.id_simulasi,') ',tsd.tanggal_simulasi) as x,sum(tsd.total_pendapatan) as pendapatan,sum(tsd.total_biaya) as biaya,sum(tsd.laba_rugi) as y FROM transaksi_simulasi_detail tsd
            inner join transaksi_simulasi ts on ts.id_simulasi=tsd.id_simulasi
            where tsd.petak_id = ?
			group by tsd.id_simulasi,tsd.tanggal_simulasi order by tsd.id_simulasi",[$data->petak_id]);
        }
        return response()->json(['success'=>true,'data'=>$petak,'message'=>'']);
    }
}
