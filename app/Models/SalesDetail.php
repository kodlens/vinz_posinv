<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;
    

    protected $table = 'sales_details';
    protected $primaryKey = 'sales_detail_id';
    protected $fillable = [
        'sales_id', 'item_id', 'item_name',
        'qty', 'remarks', 'price'
    ];


    public function item(){
        return $this->hasOne(Item::class, 'item_id', 'item_id');
    }

    
}
