<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SetupBiayaController extends Controller
{
    //
    public function index()
    {
        return view('feature.akuntansi.setup-biaya.index');
    }
}
