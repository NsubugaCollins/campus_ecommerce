<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class StoreController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.show', compact('product'));
    }

    public function category($category)
    {
        $products = Product::where('category', $category)->paginate(12);
        return view('product.category', compact('products', 'category'));
    }
}
