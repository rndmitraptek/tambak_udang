<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BenurController extends Controller
{
    //
    public function index()
    {
        return view('feature.master.benur.index');
    }
}
