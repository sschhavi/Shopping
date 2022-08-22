<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe;
use Auth;
use DB;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Order_shipping;
use App\Models\Order_status;


class StripePaymentController extends Controller
{
    public function stripe(Request $request)
    {
    	$price = $request->price;
        return view('stripe',compact('price'));
    }

    public function stripePost(Request $request)
    {
    	$userid = Auth::user()->id;

    	$cart = Cart::where('user_id','=',$userid)->get();

    	$finalArray = array();
    	$totalamt = 0;

    	foreach ($cart as $key => $value) 
    	{	
    		$tmt = $value->price*$value->quantity;
    		$totalamt = $totalamt+$tmt;

    		$order_item = new Order_item();
    		$order_item->user_id = $userid;
    		$order_item->product_id = $value->product_id;
    		$order_item->product_name = $value->name;
    		$order_item->product_qty = $value->quantity;
    		$order_item->product_price = $value->price;
    		$order_item->product_total = $value->price*$value->quantity;
    		$order_item->description = $value->description;
    		array_push($finalArray, $order_item);
    	}

    	$order = new Order();
    	$order->user_id = $userid;
    	$order->amount = $totalamt;
    	$order->status = 'Success';
    	$order->save();

    	$order_item = $order->order_item()->saveMany($finalArray);

    	$order_shipping  = new Order_shipping();
    	$order_shipping->user_id = $userid;
    	$order_shipping->name = $request->customer_name;
    	$order_shipping->address = $request->customer_address;
    	$order_shipping->mobile = $request->customer_mobile;
    	$order_shipping->city = $request->city;
    	$order_shipping->state = $request->state;
    	$order_shipping->zipcode = $request->zipcode;
    	$order->Order_shipping()->save($order_shipping);

    	Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $pay = Stripe\Charge::create ([
            "amount" => $totalamt,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Test payment from iQuincesoft.com." 
        ]);

		$ord_status="Order Failed";
        if($pay)
        {
        	$payment = new Payment();
        	$payment->user_id = $userid;
        	$payment->txn_id = $pay->balance_transaction;
        	$payment->status = $pay->status;
        	$payment->amount = $pay->amount;
        	$order->Payment()->save($payment);

        	$ord_status = "Order Confirm";
        }
       
    	$order_status = new Order_status();
    	$order_status->user_id = $userid;
    	$order_status->status = $ord_status;
    	$order->Order_status()->save($order_status);
        

        $cartdelete = Cart::where('user_id','=',$userid)->delete();
  
        Session::flash('success', 'Payment successful!');
          
        return back();
    }
}
