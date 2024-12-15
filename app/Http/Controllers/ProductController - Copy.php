<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use DB;

class ProductController extends Controller
{
    public function createHandlingProduct(Request $request) {
    	$decimalPrice = sprintf('%.2f', $request->cart_total / 100);
    	$calculate_price = $decimalPrice*20/100;
    	$fees_preice = round($calculate_price, 2);
		// print_r($fees_preice);	die;
    	$shop = $request->store_url;
    	$host = preg_replace("(^https?://)", "", $shop);
    	$access_token = DB::table('users')->where('name', $host)->value('password');
    	$product['title'] = 'Handling Fees 20%';
    	$product['body_html'] = "<strong>Handling Fees!</strong>";
    	$product['vendor'] = "Additional Charge";
    	$product['product_type'] = "Additional Charge";
    	$product['handle'] = 'handling_fees';
    	$product['template_suffix'] = '';
    	$product['status'] = 'active';
    	$product['published_at'] = '';
    	$product['tags'] = array();
    	$product_array['product'] = $product;
    	$jsonDecode = json_encode($product_array);
		$product_data = $jsonDecode;

		$productUrl = $shop.'/admin/api/2021-10/products.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$productUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $product_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$header = array(
			'X-Shopify-Access-Token: '.$access_token,
			'Content-Type: application/json',
			'Host: '.$host
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec ($ch);
		$res_arrayV = json_decode($server_output);
		$productresulT=$res_arrayV;
		if(empty($productresulT->product->id)){
			if (array_key_exists("errors",$res_arrayV)){
				if(array_key_exists("product",$res_arrayV->errors)){
					return response()->json([
						'success' => false,
						'message' => $res_arrayV->errors->product
					], 422);
				}
			}
		}
		$product_id = $productresulT->product->id;
		$variant_id = $productresulT->product->variants[0]->id;
		return $this->updateProductVariant($product_id,$variant_id,$fees_preice,$host,$shop,$access_token);  
    }

    public function updateProductVariant($product_id,$variant_id,$fees_preice,$host,$shop,$access_token){
    	$product['id'] = $product_id;
    	$product['title'] = 'Handling Fees 20%';
    	$variant['id'] = $variant_id;
    	$variant['product_id'] = $product_id;
    	$variant['title'] = "Handling Fees 20%";
    	$variant['price'] = $fees_preice;
    	$variant['position'] = 1;
    	$variant['inventory_policy'] = 'continue';
    	$variant['compare_at_price'] = '';
    	$variant['fulfillment_service'] = 'manual';
    	$variant['inventory_management'] = 'shopify';
    	$cost['cost'] = $fees_preice;
    	$vt['inventoryItem'] = $cost;
    	$variant['inventoryItem'] = $vt;
    	$price['amount'] = $fees_preice;
    	$price['currency_code'] = 'INR';
    	$pr['price'] = $price;
    	$pr['compare_at_price'] = '';
    	$variant['presentment_prices'] = array($pr);
    	$variant['inventory_item_id'] = $variant_id;
    	$variant['$variant_id'] = 5;
    	$variant['$old_inventory_quantity'] = 5;
    	$product_array['product'] = $product;
    	$product_array['product']['variants'] = array($variant);
    	$jsonDecode = json_encode($product_array);
		$product_data = $jsonDecode;
		// echo"<pre>"; print_r($product_data);	die('UPDATEER');
		$productUrl = $shop.'/admin/api/2021-10/products/'.$product_id.'.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$productUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $product_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$header = array(
			'X-Shopify-Access-Token: '.$access_token,
			'Content-Type: application/json',
			'Host: '.$host
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec ($ch);
		$res_arrayV = json_decode($server_output);
		$productresulT=$res_arrayV;
		$data = array();
		$data['product_id'] = $product_id;
		$data['variant_id'] = $variant_id;
		$data['additional_fees'] = $fees_preice;
		$data['currency_code'] = "INR";
		return json_encode($data);
    }

    public function getFixProductsForCart(Request $request){
    	// echo"<pre>"; print_r($request->all());	die;
    	$store_url = $request->store_url;
    	if(!empty($store_url)){
    		$host = preg_replace("(^https?://)", "", $store_url);
    		$products = DB::table('fees')->where('store', $host)->where('fees_type','fixed')->get();
    		$pro_array = array();
    		if($products->count()){
    			foreach ($products as $key => $product) {
    				$data = array();
    				$data['variant_id'] = $product->variant_id;
    				$pro_array[] = $data;
    			}
    		}
    		$array = array();
    		$array['variants'] = $pro_array;
    		return json_encode($array);
    	}
    }
}

?>