<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    //

    public function index(){
        return view('administrator.inventories');
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Inventory::with(['item'])
            ->whereHas('item', function($q) use ($req){
                $q->where('item_name', 'like', '%' . $req->item . '%');
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


}
