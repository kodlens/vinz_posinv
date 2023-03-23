<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\Inventory;


use Auth;


class PointOfSaleController extends Controller
{
    //

    public function index(){
        return view('administrator.point-of-sale');
    }


    public function store(Request $req){

        $user = Auth::user();

        $salesDate = date('Y-m-d H:i:s', strtotime($req->sales_date));
        
        $sales = Sales::create([
            'sales_date' => $salesDate,
            'customer' => strtoupper($req->customer),
            'total_sales' => $req->total_sales,
            'sys_user_id' => $user->user_id
        ]);

        $data = [];
        foreach($req->orders as $item){
            if($item['item_id'] > 0){
                Inventory::where('item_id', $item['item_id'])
                ->decrement('qty', $item['qty']);
                //deduct from inventory

                $data[] = [
                    'sales_id' => $sales->sales_id,
                    'item_id' => $item['item_id'],
                    'item_name' => $item['item_name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ];
            }
        }

        SalesDetail::insert($data);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
}
