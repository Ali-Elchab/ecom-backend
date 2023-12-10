<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function insert_product(Request $req)
    {
        // $product = new Product;
        // $product->name = $req->name;
        // $product->description =$req->description;
        // $product->price = $req->price;
        // $product->stock = $req->stock;
        // $product->image_url = $req->image_url;
        // $product->sold = $req->sold;
        // $product->save();

        $req->validate([
            "name" => "unique:products|min:3|max:8"
        ]);
        $product_2 = Product::insert([
            "name" => $req->name,
            "description" => $req->description,
            "price" => $req->price,
            "stock" => $req->stock,
            "image_url" => $req->image_url,

        ]);
    }
    public function update_product(Request $req)
    {
        $id_product = $req->id_product;
        $product = Product::find($id_product);

        $product_other_way = Product::where('id', $req->id_product)->first();

        $product_other_way_2 = Product::where('id', $req->id_product)->get()[0];
        $product->name = $req->name;
        $product->save();

        $product_other_way_2->update([
            "description" => $req->description ?? $product_other_way_2->description
        ]);


        return response()->json([
            "product1" => $product,
            "product2" => $product_other_way,
            "products" => $product_other_way_2
        ]);
    }
    public function get_products()
    {
        $products = Product::all();
        return response()->json([
            "products" => $products
        ]);
    }
}
