<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\Inventory;
use App\Models\SalesItemSerial;


use Auth;


class PointOfSaleController extends Controller
{
    //

    public function index(){
        return view('administrator.point-of-sale');
    }


    public function store(Request $req){
        //return $req;

        $req->validate([
            'orders.*' => ['required'],
            'orders.*.item_id' => ['required'],
            'orders.*.price' => ['required'],
            'orders.*.qty' => ['size:1'],
        ],[
            'orders.*.item_id.required' => 'Please select item',
            'orders.*.price.required' => 'Please fill out the price',
            'orders.*.price.size' => 'Add some quantity.',
        ]);

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

                // $data[] = [
                //     'sales_id' => $sales->sales_id,
                //     'item_id' => $item['item_id'],
                //     'item_name' => $item['item_name'],
                //     'qty' => $item['qty'],
                //     'price' => $item['price'],
                // ];

                $salesDetails = SalesDetail::create([
                    'sales_id' => $sales->sales_id,
                    'item_id' => $item['item_id'],
                    'item_name' => $item['item_name'],
                    'remarks' => $item['remarks'],
                    'qty' => $item['qty'],
                    'price' => $item['price']
                ]);

                //naa ge input nga serial...
                if(sizeOf($item['serials']) > 0){
                    for($i = 0; $i < sizeOf($item['serials']); $i++){
                        //$item['serials'][$i]
                        SalesItemSerial::create([
                            'sales_detail_id' => $salesDetails->sales_detail_id,
                            'sales_id' => $sales->sales_id,
                            'item_id' => $item['item_id'],
                            'serial' => $item['serials'][$i]
                        ]);
                    }
                }
                

            }
        }

        //SalesDetail::insert($data);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
}
