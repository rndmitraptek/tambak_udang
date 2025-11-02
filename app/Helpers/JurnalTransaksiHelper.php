<?php

namespace App\Helpers;

use App\Models\Akuntansi\JurnalDetailModel;
use App\Models\Akuntansi\JurnalModel;
use App\Models\nomorCounter;
use App\Models\SetupCoa;

class JurnalTransaksiHelper
{
    public static function penaburan_benur($data)
    {
        $cek = JurnalModel::where('reff_id',$data['reff_id'])
        ->where('reff_trans',$data['reff_trans'])->first();
        if($cek){
            JurnalDetailModel::where('id_jurnal',$cek->id_jurnal)->delete();
            $cek->delete();
        }
        $jurnal = JurnalModel::create([
            'tanggal'   =>$data['tanggal'],
            'no_bukti'  =>$data['no_bukti'],
            'reff_id'   =>$data['reff_id'],
            'reff_trans'=>$data['reff_trans'],
            'keterangan'=>$data['keterangan']
        ]);
        // Transaksi Penaburan Benur
        // 11401	 PERSEDIAAN BENUR
        // 	    21201	 HUTANG USAHA - BENUR 
        // 53102	 PEMBELIAN BENUR 				
        // 	    11401	 PERSEDIAAN BENUR
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>21,
            'kode_coa'  =>'11401',
            'nama_coa'  =>'PERSEDIAAN BENUR',
            'debit'     =>$data['nominal'],
            'kredit'    =>0
        ]);
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>80,
            'kode_coa'  =>'21201',
            'nama_coa'  =>'HUTANG USAHA - BENUR',
            'debit'     =>0,
            'kredit'    =>$data['nominal']
        ]);
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>154,
            'kode_coa'  =>'53102',
            'nama_coa'  =>'PEMBELIAN BENUR',
            'debit'     =>$data['nominal'],
            'kredit'    =>0
        ]);
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>21,
            'kode_coa'  =>'11401',
            'nama_coa'  =>'PERSEDIAAN BENUR',
            'debit'     =>0,
            'kredit'    =>$data['nominal']
        ]);
    }
    
    public static function penaburan_pakan($data){
        $cek = JurnalModel::where('reff_id',$data['reff_id'])
        ->where('reff_trans',$data['reff_trans'])->first();
        if($cek){
            JurnalDetailModel::where('id_jurnal',$cek->id_jurnal)->delete();
            $cek->delete();
        }
        $jurnal = JurnalModel::create([
            'tanggal'   =>$data['tanggal'],
            'no_bukti'  =>$data['no_bukti'],
            'reff_id'   =>$data['reff_id'],
            'reff_trans'=>$data['reff_trans'],
            'keterangan'=>$data['keterangan']
        ]);
        // Transaksi Penaburan Pakan
        // 53203	PENGGUNAAN PAKAN
        //     11402	 PERSEDIAAN PAKAN UDANG 
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>160,
            'kode_coa'  =>'53203',
            'nama_coa'  =>'PENGGUNAAN PAKAN',
            'debit'     =>$data['nominal'],
            'kredit'    =>0
        ]);
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>22,
            'kode_coa'  =>'11402',
            'nama_coa'  =>'PERSEDIAAN PAKAN UDANG',
            'debit'     =>0,
            'kredit'    =>$data['nominal']
        ]);
    }

    public static function retur_pakan($data){
        $cek = JurnalModel::where('reff_id',$data['reff_id'])
        ->where('reff_trans',$data['reff_trans'])->first();
        if($cek){
            JurnalDetailModel::where('id_jurnal',$cek->id_jurnal)->delete();
            $cek->delete();
        }
        $jurnal = JurnalModel::create([
            'tanggal'   =>$data['tanggal'],
            'no_bukti'  =>$data['no_bukti'],
            'reff_id'   =>$data['reff_id'],
            'reff_trans'=>$data['reff_trans'],
            'keterangan'=>$data['keterangan']
        ]);
        // 21202/1	 HUTANG USAHA - PAKAN / KAS / BANK
	    //     11402	 PERSEDIAAN PAKAN UDANG
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>81,
            'kode_coa'  =>'21202',
            'nama_coa'  =>'HUTANG USAHA - PAKAN',
            'debit'     =>$data['nominal'],
            'kredit'    =>0
        ]);
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>22,
            'kode_coa'  =>'11402',
            'nama_coa'  =>'PERSEDIAAN PAKAN UDANG',
            'debit'     =>0,
            'kredit'    =>$data['nominal']
        ]);
    }

    public static function panen($data){
        $cek = JurnalModel::where('reff_id',$data['reff_id'])
        ->where('reff_trans',$data['reff_trans'])->first();
        if($cek){
            JurnalDetailModel::where('id_jurnal',$cek->id_jurnal)->delete();
            $cek->delete();
        }
        $jurnal = JurnalModel::create([
            'tanggal'   =>$data['tanggal'],
            'no_bukti'  =>$data['no_bukti'],
            'reff_id'   =>$data['reff_id'],
            'reff_trans'=>$data['reff_trans'],
            'keterangan'=>$data['keterangan']
        ]);
        // PANEN tunai
        // 111/112		KAS / BANK
        //         41100		PENJUALAN UDANG
        // PANEN piutang
        // 113 	Piutang
        //         41100		PENJUALAN UDANG

        // Hutang / Tunai
        if($data['tunai']){
            JurnalDetailModel::create([
                'id_jurnal' =>$jurnal->id_jurnal,
                'id_coa'    =>$data['id_coa'],
                'kode_coa'  =>$data['kode_coa'],
                'nama_coa'  =>$data['nama_coa'],
                'debit'     =>0,
                'kredit'    =>$data['nominal']
            ]);
        }else{
            JurnalDetailModel::create([
                'id_jurnal' =>$jurnal->id_jurnal,
                'id_coa'    =>15,
                'kode_coa'  =>'11301',
                'nama_coa'  =>'PIUTANG USAHA',
                'debit'     =>0,
                'kredit'    =>$data['nominal']
            ]);
        }
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>$data['id_coa_penjualan'],
            'kode_coa'  =>$data['kode_coa_penjualan'],
            'nama_coa'  =>$data['nama_coa_penjualan'],
            'debit'     =>0,
            'kredit'    =>$data['nominal_penjualan']
        ]);
    }

    public static function pembayaran_piutang($data){
        $jurnal = JurnalModel::create([
            'tanggal'   =>$data['tanggal'],
            'no_bukti'  =>$data['no_bukti'],
            'reff_id'   =>$data['reff_id'],
            'reff_trans'=>$data['reff_trans'],
            'keterangan'=>$data['keterangan']
        ]);
        // PEMBAYARAN PIUTANG
        // 111/112		KAS / BANK
        //         113 	Piutang
        // KAS / BANK
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>$data['id_coa'],
            'kode_coa'  =>$data['kode_coa'],
            'nama_coa'  =>$data['nama_coa'],
            'debit'     =>$data['nominal'],
            'kredit'    =>0
        ]);
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>15,
            'kode_coa'  =>'11301',
            'nama_coa'  =>'PIUTANG USAHA',
            'debit'     =>0,
            'kredit'    =>$data['nominal']
        ]);
    }

    public static function pembelian_barang_activa($data){
        $cek = JurnalModel::where('reff_id',$data['reff_id'])
        ->where('reff_trans',$data['reff_trans'])->first();
        if($cek){
            JurnalDetailModel::where('id_jurnal',$cek->id_jurnal)->delete();
            $cek->delete();
        }
        $jurnal = JurnalModel::create([
            'tanggal'   =>$data['tanggal'],
            'no_bukti'  =>$data['no_bukti'],
            'reff_id'   =>$data['reff_id'],
            'reff_trans'=>$data['reff_trans'],
            'keterangan'=>$data['keterangan']
        ]);
        // pembelian barang active maka terbentuk akun
        // 12000		Aset Tetap
        //         21202/1	 	HUTANG USAHA - PAKAN / KAS / BANK
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>$data['id_coa'],
            'kode_coa'  =>$data['kode_coa'],
            'nama_coa'  =>$data['nama_coa'],
            'debit'     =>$data['nominal'],
            'kredit'    =>0
        ]);
        JurnalDetailModel::create([
            'id_jurnal' =>$jurnal->id_jurnal,
            'id_coa'    =>83,
            'kode_coa'  =>'21299',
            'nama_coa'  =>'HUTANG USAHA - LAINNYA',
            'debit'     =>0,
            'kredit'    =>$data['nominal']
        ]);
    }
}