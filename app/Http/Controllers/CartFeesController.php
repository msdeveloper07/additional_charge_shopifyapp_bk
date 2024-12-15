<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use DB;
use App\Models\Fees;

class CartFeesController extends Controller
{
    public function createCartFees(Request $request) {
      $validatedData = $request->validate([
          'fees_name' => 'required',
          'fees_type' => 'required',
          'price' => 'required'
      ]);
      $target_url = explode("?shop=", $request->store_url);
      $shop = $target_url[1];
      $currency = $this->getStoreCurrency($shop);
      if($request->fees_type == "fixed"){
        $product = $this->createFixProductFromApp($shop,$request->fees_name,$request->price);
        $product_decode = json_decode($product);
      }
      // echo"<pre>"; print_r($product_decode->product_id);  die($product_decode->variant_id);
      $values = array('fees_name' => $request->fees_name, 'fees_type' => $request->fees_type, 'currency' => @$currency, 'price' => $request->price, 'store' => $shop);
  		$id = DB::table('fees')->insertGetId($values);
  		if(!empty($id)){
        if(!empty($product_decode)){
          $update = DB::table('fees') ->where('id', $id)->limit(1)->update([
            'product_id' => $product_decode->product_id, 
            'variant_id' => $product_decode->variant_id
          ]); 
        }
  			return response()->json([
  				'success' => true,
  				'message' => 'Fees created.!'
  			], 200);
  		}else{
  			return response()->json([
  				'success' => false,
  				'message' => 'Something went wrong.! Please try again.!'
  			], 422);
  		}
    }

    public function getStoreCurrency($shop){
      $access_token = DB::table('users')->where('name',$shop)->value('password');
      $admin_url = "https://".$shop."/admin/api/2021-10/shop.json";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$admin_url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
      curl_setopt($ch, CURLOPT_POSTFIELDS, '');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $header = array(
          'X-Shopify-Access-Token: '.$access_token,
          'Content-Type: application/json',
          'Host: '.$shop
      );
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      $server_output = curl_exec ($ch);
      $result = json_decode($server_output);
      if(!empty($result->error)){
        if (array_key_exists("errors",$result)){
          $result = '';
          return $result;
        }
      }
      return @$result->shop->enabled_presentment_currencies[0];
    }

    public function createFixProductFromApp($shop,$fees_name,$price){
      //echo"<pre>"; print_r($shop); die;
      $host = $shop;
      $shop = "https://".$shop;
      $fees_preice = $price;
      $access_token = DB::table('users')->where('name', $host)->value('password');
      $product['title'] = $fees_name;
      $product['body_html'] = "";
      $product['vendor'] = "Additional Charge";
      $product['product_type'] = "Additional Charge";
      $product['handle'] = $fees_name;
      $product['template_suffix'] = '';
      $product['status'] = 'active';
      $product['published_at'] = '';
      $product['tags'] = array('Additional Charge');
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
      return $this->updateFixProductVariantApp($product_id,$variant_id,$fees_preice,$host,$shop,$access_token,$fees_name);
    }


    public function updateFixProductVariantApp($product_id,$variant_id,$fees_preice,$host,$shop,$access_token,$fees_name){
      $product['id'] = $product_id;
      $product['title'] = $fees_name;
      $variant['id'] = $variant_id;
      $variant['product_id'] = $product_id;
      $variant['title'] = $fees_name;
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
      $price['currency_code'] = $this->getStoreCurrency($shop);
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
      // echo"<pre>"; print_r($product_data); die('UPDATEER');
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
      $data['currency_code'] = $this->getStoreCurrency($shop);
      return json_encode($data);
    }



    public function getAllFees(Request $request){
      $target_url = explode("?shop=", $request->store_url);
      $exp_shop = $target_url[1];
      $shop = explode("&", $exp_shop);
      $store_url = $shop[0];
      
      ## Read value
      $draw = $request->get('draw');
      $start = $request->get("start");
      $rowperpage = $request->get("length"); // Rows display per page

      $columnIndex_arr = $request->get('order');
      $columnName_arr = $request->get('columns');
      $order_arr = $request->get('order');
      $search_arr = $request->get('search');

      $columnIndex = $columnIndex_arr[0]['column']; // Column index
      $columnName = $columnName_arr[$columnIndex]['data']; // Column name
      $columnSortOrder = $order_arr[0]['dir']; // asc or desc
      $searchValue = $search_arr['value']; // Search value

      // Total records
      $totalRecords = Fees::where('store',$store_url)->get()->count();
      $totalRecordswithFilter = Fees::where('store',$store_url)->where('fees_name', 'like', '%' .$searchValue . '%')->get()->count();

      // Fetch records
      $records = Fees::where('store',$store_url)->orderBy($columnName,$columnSortOrder)
        ->where('fees.fees_name', 'like', '%' .$searchValue . '%')
        ->select('fees.*')
        ->skip($start)
        ->take($rowperpage)
        ->get();

      $data_arr = array();
      foreach($records as $record){
          $fees_name = $record->fees_name;
          $fees_type = $record->fees_type;
          $price = $record->price;
          $action = '<button id="btnDel" onclick="myFunction('.$record->id.')" data-rowid="'.$record->id.'" class="btn btn-xs btn-danger"><i class="fa-solid fa-trash-can"></i></button>';

          $data_arr[] = array(
            "fees_name" => $fees_name,
            "fees_type" => $fees_type,
            "price" => $price,
            "action" => $action
          );
      }

       $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordswithFilter,
          "aaData" => $data_arr
       );

       echo json_encode($response);
       exit;
    }

    public function deleteFees(Request $request){
      $id = $request->id;
      $item = Fees::findOrFail($id);
      $item->delete();
      $data = array();
      $data['success'] = true;
      $data['message'] = 'deleted';
      return json_encode($data);
    }
}

?>