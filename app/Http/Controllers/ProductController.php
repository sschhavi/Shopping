<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;
use Auth;

class ProductController extends Controller
{
    public function index()
    {
        $allproducts = Product::all();

        $userid = Auth::user()->id;

        $cart = Cart::where('user_id','=',$userid)->count();

        $getproduct = Cart::where('user_id','=',$userid)->get();

        return view('products', compact('allproducts','cart','getproduct'));
    }

    public function cart()
    {
        $userid = Auth::user()->id;

        $getproduct = Cart::where('user_id','=',$userid)->get();

        return view('cart', compact('getproduct'));
    }
    
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        /*$cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        }  else {
            $cart[$id] = [
                "name" => $product->name,
                "image" => $product->image,
                "price" => $product->price,
                "quantity" => 1
            ];
        }

        session()->put('cart', $cart);*/

        $cart = new Cart();
        $cart["user_id"] = Auth::user()->id;
        $cart["product_id"] = $product->id;
        $cart["name"] = $product->name;
        $cart["image"] = $product->image;
        $cart["price"] = $product->price;
        $cart["quantity"] = "1";
        $cart->save();

        return redirect()->back()->with('success', 'Product add to cart successfully!');
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity)
        {
            /*$cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);

            session()->flash('success', 'Cart Successfully Updated!');*/

            $cart = Cart::findorfail($request->id);
            $cart->quantity = $request->quantity;
            $cart->save();

            session()->flash('success', 'Product Successfully Updated!');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) 
        {
            /*$cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }*/

            $cart = Cart::where('id','=',$request->id)->delete();
            session()->flash('success', 'Product Successfully Removed!');
        }
    }
}
