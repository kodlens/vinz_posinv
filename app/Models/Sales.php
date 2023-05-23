<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;


    protected $table = 'sales';
    protected $primaryKey = 'sales_id';
    protected $fillable = [
        'sales_date', 'customer', 'total_sales', 'sys_user_id'
    ];


    public function sales_details(){
        return $this->hasMany(SalesDetail::class, 'sales_id', 'sales_id')
            ->leftJoin('items', 'sales_details.item_id', 'items.item_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'sys_user_id');
    }


   

    

}
