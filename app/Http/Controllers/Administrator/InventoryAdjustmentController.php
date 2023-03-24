<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryAdjustment;



class InventoryAdjustmentController extends Controller
{
    //

    public function index(){
        return view('administrator.inventory-adjustment');
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = InventoryAdjustment::with(['item'])
            ->whereHas('item', function($q) use ($req){
                $q->where('item_name', 'like', '%' . $req->item . '%');
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function create(Request $req){

        $req->validate([
            'item_id' => ['required'],
            'adjusted_option' => ['required'],

        ],[
            'item_id.required' => 'Please select item.'
        ]);

        InventoryAdjustment::create([
            
        ]);
    }




}
