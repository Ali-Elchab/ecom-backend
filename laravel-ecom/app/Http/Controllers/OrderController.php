<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create_order(Request $request)
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

    public function add_product_to_order(Request $request, $orderId)
    {
        $order = Order::find($orderId);

        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|integer|min:1',
        ]);

        $product = Product::find($data['product_id']);

        $order->products()->attach($product->id, ['amount' => $data['amount']]);
        $order->amount += $product->price * $data['amount'];
        $order->save();

        return response()->json(['message' => 'Product added to order successfully', 'order' => $order]);
    }

    public function get_order($orderId)
    {
        $order = Order::with('products')->find($orderId);
        return response()->json(['order' => $order]);
    }

    public function edit_order(Request $request, $orderId)
    {
        $order = Order::find($orderId);

        $data = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'amount.*' => 'required|integer|min:1',
        ]);

        $order->products()->detach();
        $totalAmount = 0;

        foreach ($data['product_id'] as $index => $productId) {
            $amount = $data['amount'][$index];

            $prod = Product::find($productId);
            $order->products()->attach($prod->id, ['amount' => $amount]);
            $totalAmount += $prod->price * $amount;
        }

        $order->amount = $totalAmount;
        $order->save();

        return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
    }

    public function delete_order($orderId)
    {
        $order = Order::find($orderId);
        $order->products()->detach();
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
