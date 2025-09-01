<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiklusController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.siklus.index');
    }
}
