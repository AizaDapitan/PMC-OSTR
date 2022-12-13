<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getPublishedProducts()
    {
        $products = Product::where('status', 'Published')->get();
        return $products;
    }
    public function store(Request $request)
    {
    }
}
