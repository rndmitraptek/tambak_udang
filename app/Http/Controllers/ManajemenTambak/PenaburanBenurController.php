<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenaburanBenurController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.penaburan-benur.index');
    }
}
