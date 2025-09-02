<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlokController extends Controller
{
    //
    public function index()
    {
        return view('feature.master.blok.index');
    }
}
