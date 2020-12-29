<?php
namespace App\Http\Controllers\API;
use App\Helpers\UserInfo;
use App\Http\Controllers\Web\AlertController;
use App\Models\Web\Order;
use App\Password_Reset_Code;
use App\User;
use App\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use \Firebase\JWT\JWT;
use Intervention\Image\Facades\Image;



class UserController extends Controller
{
    public $successStatus = 200;
    public $authStatus = 401;
    public $failStatus = 402;
    public $ExistsStatus = 403;
    public $validationStatus = 404;


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];

            return response()->json($response, $this-> validationStatus);
        }


//        $credentials = [
//            'email' => $request->email,
//            'password' => $request->password,
//        ];

        if (is_numeric($request->email)){
            $credentials = [
                'phone' => $request->email,
                'password' => $request->password,
            ];
        }else{
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
        }



        if(Auth::attempt($credentials))
        {
            $user = Auth::user();
            //$success['token'] =  $user->createToken('knoprotec')-> accessToken;
            $success['user'] =  $user;
            return response()->json(['success'=>true,'response' => $success], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];

            return response()->json($response, $this-> validationStatus);
        }

        $checkmail=User::where('email',$request->email)->first();
        if(!empty($checkmail)){
            return response()->json(['success'=>false,'response' =>'Email Already Exist'], $this-> ExistsStatus);
        }
        $checkPhone=User::where('phone',$request->phone)->first();
        if(!empty($checkPhone)){
            return response()->json(['success'=>false,'response' =>'Phone Already Exist'], $this-> ExistsStatus);
        }

        $userReg = new User();
        $userReg->first_name = $request->firstName;
        //$userReg->last_name = $request->lastName;
        $userReg->last_name = '';
        $userReg->email = $request->email;
        $userReg->gender = $request->gender;
        $userReg->status = 1;
        $userReg->api_token = Str::random(60);
        $userReg->password = Hash::make($request->password);
        $userReg->role_id = 2;
        $userReg->registration_as = 'phone';
        $userReg->save();

        if($userReg->id){
            //email and notification
            $customers = DB::table('users')->where('id', $userReg->id)->get();
            $myVar = new AlertController();
            $myVar->createUserAlert($customers);

            $success['user'] =  $userReg;
            return response()->json(['success'=>true,'response' =>$userReg], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response'=>'No Inserted!'], $this-> failStatus);
        }

    }

    public function details(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);

            unset($user['country_code']);
            unset($user['avatar']);
            unset($user['phone_verified']);
            unset($user['auth_id_tiwilo']);
            unset($user['created_at']);
            unset($user['updated_at']);

            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
            return response()->json(['success'=>false,'response'=>$user], $this-> successStatus);
        }
    }

    public function password_reset(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        if(Hash::check($request->current_pass, $user->password)){
            //dd("m");
            $rand_pass= $request->new_pass;
            $new_pass=Hash::make($rand_pass);
            $user->password=$new_pass;
            $user->api_token = Str::random(60);
            $update = $user->update();
            if($update){
                return response()->json(['success'=>true,'response' => $user,'api_token' => $user->api_token], $this-> successStatus);
            }else{
                return response()->json(['success'=>true,'response' => $user,'api_token' => 'No Reset Password'], $this-> failStatus);
            }
        }else{
            return response()->json(['success'=>false,'response' => 'current password do not matched'], $this-> failStatus);
        }
    }

    public function shipping_address_post(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $address_book_data = array(
            'user_id' => $request->user_id,
            'customers_id' => $request->user_id,
            'entry_firstname' => $request->entry_firstname,
            'entry_lastname' => $request->entry_lastname,
            'entry_street_address' => $request->entry_street_address,
            'entry_city' => $request->entry_city,
            'entry_postcode' => $request->entry_postcode,
            'entry_phone' => $request->entry_phone,
            'entry_zone_id' => 182,
            'entry_country_id' => 18,
        );
        $address_book_id = DB::table('address_book')->insertGetId($address_book_data);

        //dd($address_book_id);
        $add = DB::table('user_to_address')->insert(
            ['user_id' => $request->user_id, 'address_book_id' => $address_book_id,'is_default' => 0 ]
        );

        if($address_book_id && $add){
            return response()->json(['success'=>true,'response' => $address_book_data], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Inserted Address Book'], $this-> failStatus);
        }
    }

    public function shipping_address_edit(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $address_book_data = array(
            'user_id' => $request->user_id,
            'customers_id' => $request->user_id,
            'entry_firstname' => $request->entry_firstname,
            'entry_lastname' => $request->entry_lastname,
            'entry_street_address' => $request->entry_street_address,
            'entry_city' => $request->entry_city,
            'entry_postcode' => $request->entry_postcode,
            'entry_phone' => $request->entry_phone,
            //'entry_zone_id' => 182,
            //'entry_country_id' => 18,
        );

        $address_book_id = DB::table('address_book')
            ->where('address_book_id', $request->address_book_id)
            ->update($address_book_data);

        if($address_book_id){
            return response()->json(['success'=>true,'response' => $address_book_data], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Updated Address Book'], $this-> failStatus);
        }
    }

    public function shipping_address_delete(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $address_book_id = DB::table('address_book')
            ->where('address_book_id', $request->address_book_id)
            ->delete();

        $add = DB::table('user_to_address')->where('address_book_id', $request->address_book_id)
            ->delete();

        if($address_book_id && $add){
            return response()->json(['success'=>true,'response' => 'Deleted'], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Updated Address Book'], $this-> failStatus);
        }
    }

    public function shipping_address_get(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }


//        $shipping_address = DB::table('user_to_address')
//            ->join('address_book', 'user_to_address.address_book_id', '=', 'address_book.address_book_id')
//            ->where('user_to_address.user_id', $request->user_id)
//            ->get();

        $shipping_address = DB::table('user_to_address')
            ->join('address_book', 'user_to_address.address_book_id', '=', 'address_book.address_book_id')
            ->where('user_to_address.user_id', $request->user_id)
            ->select('address_book.address_book_id','address_book.user_id','address_book.entry_firstname','address_book.entry_lastname','address_book.entry_street_address','address_book.entry_postcode','address_book.entry_city','address_book.entry_phone')
            ->get();

        if($shipping_address){
            return response()->json(['success'=>true,'response' => $shipping_address], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Information Using This User'], $this-> failStatus);
        }
    }

    public function get_shipping_cost(Request $request)
    {
//        $authorization = $request->header('Auth');
//        if (empty($authorization)) {
//            return response()->json(['success' => false, 'response' => 'Unauthorised'], $this-> failStatus);
//        } else {
//            $user = User::find($request->user_id);
//            if ($user->api_token != $authorization) {
//                return response()->json(['success' => false, 'response' => 'Unauthorised'], $this-> failStatus);
//            }
//        }

        $shipping_cost = DB::table('flate_rate')->pluck('flate_rate')->first();
        if($shipping_cost){
            return response()->json(['success'=>true,'response' => $shipping_cost], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Shipping Cost Found.'], $this-> failStatus);
        }


    }

    public function billing_address_post(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $address_book_billing_data = array(
            'user_id' => $request->user_id,
            'customers_id' => $request->user_id,
            'entry_firstname' => $request->entry_firstname,
            'entry_lastname' => $request->entry_lastname,
            'entry_street_address' => $request->entry_street_address,
            'entry_city' => $request->entry_city,
            'entry_postcode' => $request->entry_postcode,
            'entry_phone' => $request->entry_phone,
            'entry_zone_id' => 182,
            'entry_country_id' => 18,
        );
        $address_book_billing_id = DB::table('address_book_billing')->insertGetId($address_book_billing_data);

        $billing_address = DB::table('user_to_address_billing')->insert(
            ['user_id' => $request->user_id, 'address_book_billing_id' => $address_book_billing_id,'is_default' => 0 ]
        );

        if($address_book_billing_id && $billing_address){
            return response()->json(['success'=>true,'response' => $address_book_billing_data], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Inserted Billing Address'], $this-> failStatus);
        }
    }

    public function billing_address_get(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $shipping_address_billing = DB::table('user_to_address_billing')
            ->join('address_book_billing', 'user_to_address_billing.address_book_billing_id', '=', 'address_book_billing.address_book_billing_id')
            ->where('user_to_address_billing.user_id', $request->user_id)
            ->select('address_book_billing.address_book_billing_id','address_book_billing.user_id','address_book_billing.entry_firstname','address_book_billing.entry_lastname','address_book_billing.entry_street_address','address_book_billing.entry_postcode','address_book_billing.entry_city','address_book_billing.entry_phone')
            ->get();

        if($shipping_address_billing){
            return response()->json(['success'=>true,'response' => $shipping_address_billing], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Get Shipping Address'], $this-> failStatus);
        }
    }

    public function billing_address_edit(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $address_book_billing_data = array(
            'user_id' => $request->user_id,
            'customers_id' => $request->user_id,
            'entry_firstname' => $request->entry_firstname,
            'entry_lastname' => $request->entry_lastname,
            'entry_street_address' => $request->entry_street_address,
            'entry_city' => $request->entry_city,
            'entry_postcode' => $request->entry_postcode,
            'entry_phone' => $request->entry_phone,
            //'entry_zone_id' => 182,
            //'entry_country_id' => 18,
        );

        $update = DB::table('address_book_billing')
            ->where('address_book_billing_id', $request->address_book_billing_id)
            ->update($address_book_billing_data);

        if($update){
            return response()->json(['success'=>true,'response' => $address_book_billing_data], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'NO Updated Billing Address'], $this-> failStatus);
        }
    }

    public function billing_address_delete(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $delete_address_book_billing = DB::table('address_book_billing')
            ->where('address_book_billing_id', $request->address_book_billing_id)
            ->delete();

        $delete_user_to_address_billing = DB::table('user_to_address_billing')->where('address_book_billing_id', $request->address_book_billing_id)
            ->delete();

        if($delete_address_book_billing && $delete_user_to_address_billing){
            return response()->json(['success'=>true,'response' => 'Deleted'], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Updated Billing Address'], $this-> failStatus);
        }
    }

    public function profile_update(Request $request)
    {
        //dd($request->all());
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $user=User::find($request->user_id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $update =$user->update();

        if($user){
            return response()->json(['success'=>true,'response' => $user], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Profile Updated'], $this-> failStatus);
        }
    }

    public function order(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $orders=Order::where('customers_id',$request->user_id)
            ->select('orders_id','customers_id','customers_name','customers_street_address','customers_city','customers_postcode','email','delivery_phone','delivery_name','delivery_street_address','delivery_city','delivery_postcode','billing_name','billing_street_address','billing_city','billing_postcode','billing_phone','payment_method','order_price','shipping_cost','shipping_method','coupon_amount','free_shipping')
            ->latest('orders_id')
            ->get();

        //unset unnecessary field
        unset($orders['customers_company']);

        if($orders){
            return response()->json(['success'=>true,'response' => $orders], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Order Found.'], $this-> failStatus);
        }
    }

    public function order_details(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $order_details = DB::table('orders')
            ->join('orders_products', 'orders.orders_id', '=', 'orders_products.orders_id')
            ->select('orders.shipping_cost','orders.shipping_method','orders.shipping_method','orders.date_purchased','orders.payment_method','orders.order_price','orders_products.orders_products_id','orders_products.orders_id','orders_products.products_id','orders_products.products_name','orders_products.products_price','orders_products.final_price','orders_products.products_tax','orders_products.products_quantity')
            ->where('orders_products.orders_id', $request->order_id)
            ->get();

        $orders_status = DB::table('orders')
            ->join('orders_status_history', 'orders.orders_id', '=', 'orders_status_history.orders_id')
            ->join('orders_status_description', 'orders_status_history.orders_status_id', '=', 'orders_status_description.orders_status_id')
            ->where('orders.orders_id', $request->order_id)
            ->select('orders_status_description.orders_status_name as order_status')
            ->latest('orders_status_history.orders_status_history_id')
            ->first();

        if($order_details && $orders_status){
            return response()->json(['success'=>true,'response' => $order_details,'order_status' => $orders_status], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Data Found'], $this-> failStatus);
        }
    }

    public function order_cancel(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $orders_history_id = DB::table('orders_status_history')->insertGetId(
            ['orders_id' => $request->orders_id,
                'orders_status_id' => $request->orders_status_id,
                'date_added' => date("Y-m-d h:i:s"),
                'customer_notified' => '1',
                'comments' => null,
            ]);

        if($orders_history_id){
            return response()->json(['success'=>true,'response' => 'canceled'], $this-> successStatus);
        }else {
            return response()->json(['success'=>true,'response' => 'No Canceled'], $this-> failStatus);
        }
    }
    public function place_order(Request $request)
    {
        //dd($request->all());
        //return response()->json(['success'=>true,'response'=>$request->cart], $this-> failStatus);
//        $cart_items = json_decode($request->cart);
//        foreach($cart_items as $product){
//            $products_name = $product->products_name;
//        }
        //return response()->json(['success'=>true,'response'=>$cart_items], $this-> failStatus);
//        return response()->json(['success'=>true,'response'=>$products_name], $this-> failStatus);
//        return response()->json(['success'=>true,'response'=>'come here'], $this-> failStatus);

        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $orders_id = DB::table('orders')->insertGetId(
            [
                'customers_id' => $request->user_id,
                'customers_name' => $request->delivery_firstname . ' ' . $request->delivery_lastname,
                'customers_street_address' => $request->delivery_street_address,
                'customers_city' => $request->delivery_city,
                'customers_state' => $request->delivery_state,
                'customers_country' => "Bangladesh",
                'email' => $request->email,

                'delivery_name' => $request->delivery_firstname . ' ' . $request->delivery_lastname,
                'delivery_street_address' => $request->delivery_street_address,
                'delivery_city' => $request->delivery_city,
                'delivery_state' => $request->delivery_state,
                'delivery_country' => "Bangladesh",

                'billing_name' => $request->billing_firstname . ' ' . $request->billing_lastname,
                'billing_street_address' => $request->billing_street_address,
                'billing_city' => $request->billing_city,
                'billing_state' => $request->billing_state,
                'billing_country' => "Bangladesh",

                'payment_method' => $request->payment_method_name,
                'last_modified' => date("Y-m-d h:i:s"),
                'date_purchased' => date("Y-m-d h:i:s"),
//                'coupon_code' => $request->coupon_code,
//                'coupon_amount' => $request->coupon_amount,
                'order_price' => $request->total_order_price,
                'shipping_cost' => $request->shipping_cost,
                'shipping_method' => "flateRate",
                'ordered_source' => '1',
                'delivery_phone' => $request->delivery_phone,

            ]);

        $order_insert_id = $orders_id;

        //orders status history
        DB::table('orders_status_history')->insertGetId(
            [
                'orders_id' => $orders_id,
                'orders_status_id' => 1,
                'date_added' => date("Y-m-d h:i:s"),
                'customer_notified' => '1',
            ]);


        foreach ($request->cart as $products) {
            $orders_products_id = DB::table('orders_products')->insertGetId(
                [
                    'orders_id' => $orders_id,
                    'products_id' => $products['products_id'],
                    'products_name' => $products['products_name'],
                    'products_price' => $products['price'],
                    'final_price' => $products['final_price'] * $products['customers_basket_quantity'],
                    'products_quantity' => $products['customers_basket_quantity'],
                ]);
            $inventory_ref_id = DB::table('inventory')->insertGetId([
                'products_id' => $products['products_id'],
                'reference_code' => '',
                'stock' => $products['customers_basket_quantity'],
                'admin_id' => 0,
                'added_date' => time(),
                'purchase_price' => 0,
                'stock_type' => 'out',
            ]);

        }

        if($order_insert_id){
            return response()->json(['success'=>true,'order_id' => $order_insert_id], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'order_id' => 'No Order Placed'], $this-> failStatus);
        }
    }

    public function order_sum_amount(Request $request)
    {
        $authorization = $request->header('Auth');
        if(empty($authorization)){
            return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
        }else{
            $user=User::find($request->user_id);
            if($user->api_token!=$authorization){
                return response()->json(['success'=>false,'response'=>'Unauthorised'], $this-> authStatus);
            }
        }

        $orders = DB::table("orders")->where('customers_id',$request->user_id)->get()->sum('order_price');

        if($orders){
            return response()->json(['success'=>true,'response' => $orders], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Order Found'], $this-> failStatus);
        }
    }

    public function coupon(Request $request)
    {

        $coupons = DB::table("coupons")
            ->where('code',$request->code)
            ->select('code','description','discount_type','amount','expiry_date','product_ids','product_categories')
            ->latest()
            ->first();

        if($coupons){
            return response()->json(['success'=>true,'response' => $coupons], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Coupon Found'], $this-> failStatus);
        }
    }

    public function coupon_by_product_categories(Request $request)
    {
        $arr = [
            $request->product_categories
        ];

        $coupons = DB::table("coupons")
            ->where('code',$request->code)
            ->whereIn('product_categories', $arr)
            ->select('code','description','discount_type','amount','expiry_date','product_ids','product_categories')
            ->latest()
            ->first();
        //dd($coupons);


        if($coupons){
            return response()->json(['success'=>true,'response' => $coupons], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Coupon Found'], $this-> failStatus);
        }
    }

    public function coupon_by_product_ids(Request $request)
    {
        $arr = [
            $request->product_ids
        ];

        $coupons = DB::table("coupons")
            ->where('code',$request->code)
            ->whereIn('product_ids', $arr)
            ->select('code','description','discount_type','amount','expiry_date','product_ids','product_categories')
            ->latest()
            ->first();
        //dd($coupons);


        if($coupons){
            return response()->json(['success'=>true,'response' => $coupons], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Coupon Found'], $this-> failStatus);
        }
    }

    public function coupon_list()
    {

        $coupons = DB::table("coupons")
            ->select('coupans_id','code','description','discount_type','amount','expiry_date','product_ids','product_categories')
            ->latest()
            ->get();


        if($coupons){
            return response()->json(['success'=>true,'response' => $coupons], $this-> successStatus);
        }else{
            return response()->json(['success'=>false,'response' => 'No Coupon Found'], $this-> failStatus);
        }
    }

}
