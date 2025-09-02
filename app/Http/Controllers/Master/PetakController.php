<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetakController extends Controller
{
    //
    public function index()
    {
        return view('feature.master.petak.index');
    }
}
