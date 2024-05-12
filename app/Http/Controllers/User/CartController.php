<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cart;
    protected $product;

    public function __construct()
    {
        $this->cart = new Cart();
        $this->product = new Product();

    }
    public function showCart()
    {
        $check = 'error';
        if (Auth()->check()) {
            $user_id = Auth::id();
            $carts = $this->cart->getAllCarts($user_id);
            $check = 'success';
            return response()->json([
                'status' => $check,
                'carts' => $carts
            ]);
        }
        return response()->json([
            'status' => $check,
            'message' => 'User is not authenticated'
        ], 401);
    }

    public function addToCart(Request $request)
    {
        //
        // if (Auth()->check()) {
        if ($request->isMethod('post')) {
            $product_id = $request->input('id');
            $quantity = 1;
            // $user_id = Auth()->user()->id;
            $user_id = 2;
            $existing_cart_item = Cart::where('product_id', $product_id)
                ->where('user_id', $user_id)
                ->first();
            if ($existing_cart_item) {
                $existing_cart_item->quantity = $existing_cart_item->quantity + 1;
                $existing_cart_item->total_price = number_format($existing_cart_item->unit_price * $existing_cart_item->quantity, 2, '.', '');
                $existing_cart_item->save();
                return response()->json([
                    "status" => "success",
                    "message" => "The product has been added to cart.",
                    "data" => $existing_cart_item, 200
                ]);
            } else {

                $product = $this->product->getProductById($product_id);
                if ($product) {
                    $cart_item = new Cart();
                    $cart_item->product_id = $product_id;
                    $cart_item->quantity = $quantity;
                    $cart_item->user_id =  $user_id;
                    //Giá sau khi giảm giá = Giá gốc - (Giá gốc * (Mức giảm giá / 100))
                    $cart_item->unit_price = $product->price - ($product->price * ($product->discount / 100));
                    $cart_item->total_price = $product->price - ($product->price * ($product->discount / 100)) * $cart_item->quantity;
                    $cart_item->save();
                    return response()->json([
                        "status" => "success",
                        "message" => "The product has been added to cart.",
                        "data" => $cart_item
                    ], 200);
                } else {
                    return response()->json([
                        "status" => "error",
                        "message" => "No product information found.",
                    ], 500);
                }
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'The method not post',
            ]);
        }
    }
}