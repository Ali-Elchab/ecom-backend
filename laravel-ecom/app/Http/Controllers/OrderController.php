<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $order = Order::create([
            'user_id' => $data['user_id'],
            'amount' => 0,
        ]);

        return response()->json(['message' => 'Order created successfully', 'order' => $order]);
    }

    public function addProductToOrder(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);

        $order->products()->attach($product->id, ['quantity' => $data['quantity']]);
        $order->amount += $product->price * $data['quantity'];
        $order->save();

        return response()->json(['message' => 'Product added to order successfully', 'order' => $order]);
    }

    public function getOrder($orderId)
    {
        $order = Order::with('products')->findOrFail($orderId);
        return response()->json(['order' => $order]);
    }

    public function editOrder(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $data = $request->validate([
            'products' => 'required|array',
        ]);

        $order->products()->detach();
        $totalAmount = 0;

        foreach ($data['products'] as $product) {
            $prod = Product::findOrFail($product['product_id']);
            $order->products()->attach($prod->id, ['quantity' => $product['quantity']]);
            $totalAmount += $prod->price * $product['quantity'];
        }

        $order->amount = $totalAmount;
        $order->save();

        return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
    }

    public function deleteOrder($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->products()->detach();
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
