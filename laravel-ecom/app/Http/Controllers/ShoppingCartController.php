<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use App\Models\Product;

class ShoppingCartController extends Controller
{
    public function addToCart(Request $request, $userId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $shoppingCart = ShoppingCart::firstOrCreate(['user_id' => $userId]);

        // Check if the product already exists in the shopping cart
        $existingProduct = $shoppingCart->products()->where('product_id', $product->id)->first();

        if ($existingProduct) {
            // Update the quantity of the existing product in the cart
            $existingProduct->pivot->quantity += $request->quantity;
            $existingProduct->pivot->save();
        } else {
            // Attach the product to the shopping cart with the given quantity
            $shoppingCart->products()->attach($product->id, ['quantity' => $request->quantity]);
        }

        return response()->json(['message' => 'Product added to cart successfully']);
    }

    public function removeFromCart(Request $request, $userId)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $shoppingCart = ShoppingCart::where('user_id', $userId)->firstOrFail();

        $shoppingCart->products()->detach($request->product_id);

        return response()->json(['message' => 'Product removed from cart successfully']);
    }

    public function viewCart($userId)
    {
        $shoppingCart = ShoppingCart::with('products')->where('user_id', $userId)->firstOrFail();

        return response()->json(['shopping_cart' => $shoppingCart]);
    }
}
