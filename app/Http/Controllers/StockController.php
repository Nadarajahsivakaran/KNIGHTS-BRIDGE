<?php

namespace App\Http\Controllers;

use App\Models\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class StockController extends Controller
{

    public function productBySeller($id)
    {

        try {
            $seller = decrypt($id);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }

        try {

            $product_list = stock::where('seller_id', $seller)
                ->join('products', 'products.id', 'stocks.product_id')
                ->groupBy('stocks.product_id')
                ->select('products.product_name')
                ->get();

            if (count($product_list) > 0) {
                return response()->json(['success' => $product_list], 200);
            } else {
                return response()->json(['success' => "There are no records"], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    public function sellereByProduct($id)
    {

        try {
            $product = decrypt($id);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }

        try {
            $sellers = stock::where('product_id', $product)
                ->join('sellers', 'sellers.id', 'stocks.seller_id')
                ->groupBy('stocks.seller_id')
                ->select('stocks.seller_id', 'sellers.seller_name')
                ->get();

            if (count($sellers) > 0) {
                return response()->json(['success' => $sellers], 200);
            } else {
                return response()->json(['success' => "There are no records"], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }
}
