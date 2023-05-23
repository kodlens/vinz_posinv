<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use App\Models\Sales;


class DashboardController extends Controller
{
    //

    public function index(){
        return view('administrator.dashboard');
    }

    public function loadTodaySales(){
        $now = date('Y-m-d');

        return Sales::whereDate('sales_date', $now)
            ->sum('total_sales');

        // return DB::select('
        //     SELECT SUM(total_sales) as today_sales FROM sales WHERE DATE(sales_date) = ?
        // ', [$now]);
    }

}
