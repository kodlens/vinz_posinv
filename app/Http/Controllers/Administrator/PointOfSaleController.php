<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PointOfSaleController extends Controller
{
    //

    public function index(){
        return view('administrator.point-of-sale');
    }
}
