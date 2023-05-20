<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryAdjustment;
use App\Models\Inventory;



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

    public function create(){
        return view('administrator.inventory-adjustment-create');
    }


    public function store(Request $req){

        //return $req;

        $req->validate([
            'item_id' => ['required'],
            'adjustment' => ['required'],

        ],[
            'item_id.required' => 'Please select item.'
        ]);

        $adjustmentType = $req->adjustment;
        $itemId = $req->item_id;

        

        $inv = Inventory::where('item_id', $req->item_id);

        //check if item is in inventory
        //if not found, insert the item in inventories table
        if($inv->exists()){
            $inv = $inv->first();
            $curQty = $inv->qty;
        }else{
            //not found, insert
            Inventory::create(['item_id' => $req->item_id, 'qty' => 0]);
            $curQty = 0;
        }
       
        InventoryAdjustment::create([
            'item_id' => $itemId,
            'adjustment' => $adjustmentType,
            'adjusted_qty' => $req->adjusted_qty,
            'current_qty' => $curQty,
            'remarks' => $req->remarks,
            'datetime_adjusted' => date('Y-m-d H:i:s')
        ]);

        if($adjustmentType == 'ADD'){
            Inventory::where('item_id', $itemId)
                ->increment('qty', $req->adjusted_qty);
        }else{
            Inventory::where('item_id', $itemId)
                ->decrement('qty', $req->adjusted_qty);
        }
        

        return response()->json([
            'status' => 'saved'
        ], 200);
    }




}
