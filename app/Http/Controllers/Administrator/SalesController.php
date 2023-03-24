<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;



class SalesController extends Controller
{
    //

    public function index(){
        return view('administrator.sales');
    }
    
    public function getData(Request $req){

        $sort = explode('.', $req->sort_by);

        $data = Sales::with(['sales_details', 'user'])
            ->whereHas('sales_details', function($q) use ($req){
                $q->where('item_name', 'like', '%' . $req->item . '%');
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }
}
