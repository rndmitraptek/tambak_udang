<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiBiayaController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.transaksi-biaya.index');
    }

    public function validasi()
    {
        return view('feature.manajemen-tambak.transaksi-biaya-validasi.index');
    }
}
