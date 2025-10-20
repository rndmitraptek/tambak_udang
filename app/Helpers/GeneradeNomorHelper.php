<?php

namespace App\Helpers;

use App\Models\nomorCounter;

class GeneradeNomorHelper
{

    public static function long($keterangan)
    {
        $pecah = explode('-', date('Y-m-d'));
        $master_counter = nomorCounter::where('keterangan',$keterangan)->first();
        if (date('Y-m', strtotime($master_counter->tanggal)) != date('Y-m')) {
            $master_counter->counter = 1;
        }else{
            $master_counter->counter = $master_counter->counter + 1;
        }
        $master_counter->tanggal = date('Y-m-d');
        $nomor = $master_counter->prefix . substr($pecah[0], -2) .$pecah[1] .sprintf('%04s', $master_counter->counter);
        return $nomor;
    }
    
    public static function long_update($keterangan)
    {
        $pecah = explode('-', date('Y-m-d'));
        $master_counter_forupdate = nomorCounter::where('keterangan',$keterangan)->lockForUpdate()->first();
        if (date('Y-m', strtotime($master_counter_forupdate->tanggal)) != date('Y-m')) {
            $master_counter_forupdate->counter = 1;
        }else{
            $master_counter_forupdate->counter = $master_counter_forupdate->counter + 1;
        }
        $master_counter_forupdate->tanggal = date('Y-m-d');
        $master_counter_forupdate->save();
        $nomor = $master_counter_forupdate->prefix . substr($pecah[0], -2) .$pecah[1] .sprintf('%04s', $master_counter_forupdate->counter);
        return $nomor;
    }

    public static function sort($keterangan)
    {
        $master_counter = nomorCounter::where('keterangan',$keterangan)->first();
        $master_counter->counter = $master_counter->counter + 1;
        return $master_counter->prefix.sprintf('%04s', $master_counter->counter);
    }

    public static function sort_update($keterangan)
    {
        $master_counter_forupdate = nomorCounter::where('keterangan',$keterangan)->lockForUpdate()->first();
        $master_counter_forupdate->counter = $master_counter_forupdate->counter + 1;
        $master_counter_forupdate->save();
        // dd($master_counter_forupdate);
        return $master_counter_forupdate->prefix.sprintf('%04s', $master_counter_forupdate->counter);
    }
}