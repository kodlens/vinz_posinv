<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class ItemController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('administrator.item'); //blade.php
    }


    public function getPosItems(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Item::where('item_name', 'like', '%' . $req->itemname . '%')
            ->where('barcode', 'like', '%'. $req->barcode . '%')
            ->where('serial', 'like', '%'. $req->serial . '%')
            ->select('items.item_id','items.barcode', 'items.serial', 'items.brand', 'items.model', 'items.item_name',
                DB::raw('(select qty from inventories where inventories.item_id = items.item_id limit 1) as stock_qty'),
                DB::raw('(select price from stock_ins where stock_ins.item_id = items.item_id order by stock_in_date desc limit 1) as price'),
                DB::raw('(select srp from stock_ins where stock_ins.item_id = items.item_id order by stock_in_date desc limit 1) as srp'),
            )
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }
    public function getItems(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Item::where('item_name', 'like', '%' . $req->itemname . '%')
            ->where('barcode', 'like', '%'. $req->barcode . '%')
            ->where('serial', 'like', '%'. $req->serial . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function show($id){
        return Item::find($id);
    }


    public function store(Request $req){
        //this will create random unique QR code
        //$qr_code = substr(md5(time() . $req->lname . $req->fname), -8);

        $validate = $req->validate([
            'item_name' => ['required', 'unique:items']
        ]);

        $item = Item::create([
            'barcode' => strtoupper($req->barcode),
            'serial' => strtoupper($req->serial),
            'brand' => strtoupper($req->brand),
            'model' => strtoupper($req->model),
            'item_name' => strtoupper($req->item_name),
        ]);

        Inventory::create([
            'item_id' => $item->item_id,
            'qty' => 0,
        ]);

        return response()->json([
            'status' => 'saved'
        ]);
    }

    public function update(Request $req, $id){
        $validate = $req->validate([
            'item_name' => ['required', 'unique:items,item_name,'.$id.',item_id'],
        ]);

        $data = Item::find($id);
        $data->barcode = strtoupper($req->barcode);
        $data->serial = strtoupper($req->serial);
        $data->brand = strtoupper($req->brand);
        $data->model = strtoupper($req->model);
        $data->item_name = strtoupper($req->item_name);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ]);
    }


    public function destroy($id){
        Item::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ]);
    }

}
