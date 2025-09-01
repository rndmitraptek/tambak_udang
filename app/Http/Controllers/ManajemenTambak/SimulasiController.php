<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SimulasiController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.simulasi.index');
    }
}
