<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.po.index');
    }
}
