<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembelianPakanController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.pembelian_pakan.index');
    }
}
