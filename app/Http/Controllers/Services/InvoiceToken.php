<?php

namespace App\Http\Controllers\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Api\Auth\BaseController as BaseController;

class InvoiceToken extends BaseController
{

    public function generateTokenNo()
    {
        // Get the current date in the desired format
        $curr_date = Carbon::now()->format('Y-m-d');

        // Query the number of rows with the current date
        $querytoken = DB::table('orders')
            ->whereDate('sale_date', $curr_date)
            ->count();
        // Return the incremented token
        return str_pad($querytoken + 1, 6, '0', STR_PAD_LEFT);
    }
    public function generateSaleNo($outlet_id)
    {
        // Query the number of sales for the given outlet_id
        $sale_no = DB::table('orders')
            ->where('outlet_id', $outlet_id)
            ->count();

        // Increment the count and pad it with leading zeros
        $sale_no = str_pad($sale_no + 1, 6, '0', STR_PAD_LEFT);

        return $sale_no;
    }
}