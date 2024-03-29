<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\AdminControllers\SiteSettingController;
use App\Http\Controllers\Controller;
use App\Models\Core\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class OrdersController extends Controller
{
    //
    public function __construct(Setting $setting)
    {
        $this->myVarsetting = new SiteSettingController($setting);

    }

    //add listingOrders
    public function display(Request $request)
    {

        $title = array('pageTitle' => Lang::get("labels.ListingOrders"));
        //$language_id                            =   $request->language_id;
        $language_id = '1';

        $message = array();
        $errorMessage = array();


//        $orders = DB::table('orders')->latest('date_purchased')
//            ->where('customers_id', '!=', '')->get();

        if(empty($request->direction)){
            $orders = DB::table('orders')
                ->latest('orders_id')
                ->where('customers_id', '!=', '')
                ->get();
        }else{
            $orders = DB::table('orders')
                //->latest('date_purchased')
                    ->orderBy('orders_id',$request->direction)
                ->where('customers_id', '!=', '')
                ->get();
        }


        $index = 0;
        $total_price = array();

        foreach ($orders as $orders_data) {
            $orders_products = DB::table('orders_products')->sum('final_price');

            $orders[$index]->total_price = $orders_products;

            $orders_status_history = DB::table('orders_status_history')
                ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
                ->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
                ->select('orders_status_description.orders_status_name', 'orders_status_description.orders_status_id')
                ->where('orders_status_description.language_id', '=', $language_id)
                ->where('orders_id', '=', $orders_data->orders_id)
                ->where('role_id', '<=', 2)
                ->orderby('orders_status_history.date_added', 'DESC')->limit(1)->get();//
//orders_id
            $orders[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
            $orders[$index]->orders_status = $orders_status_history[0]->orders_status_name;
            $index++;

        }

        $ordersData['message'] = $message;
        $ordersData['errorMessage'] = $errorMessage;
        $ordersData['orders'] = $orders;
        $ordersData['currency'] = $this->myVarsetting->getSetting();


        return view("admin.Orders.index", $title)->with('listingOrders', $ordersData);
    }

    //view order detail
    public function vieworder(Request $request)
    {

        $title = array('pageTitle' => Lang::get("labels.ViewOrder"));
        $language_id = '1';
        $orders_id = $request->id;

        $message = array();
        $errorMessage = array();

        DB::table('orders')->where('orders_id', '=', $orders_id)
            ->where('customers_id', '!=', '')->update(['is_seen' => 1]);

        $order = DB::table('orders')
            ->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
            ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
            ->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
            ->where('orders_status_description.language_id', '=', $language_id)
            ->where('role_id', '<=', 2)
            ->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();//orders_status_history.date_added

        foreach ($order as $data) {
            $orders_id = $data->orders_id;

            $orders_products = DB::table('orders_products')
                ->join('products', 'products.products_id', '=', 'orders_products.products_id')
                ->LeftJoin('image_categories', function ($join) {
                    $join->on('image_categories.image_id', '=', 'products.products_image')
                        ->where(function ($query) {
                            $query->where('image_categories.image_type', '=', 'THUMBNAIL')
                                ->where('image_categories.image_type', '!=', 'THUMBNAIL')
                                ->orWhere('image_categories.image_type', '=', 'ACTUAL');
                        });
                })
                ->select('orders_products.*', 'image_categories.path as image')
                ->where('orders_products.orders_id', '=', $orders_id)->get();
            $i = 0;
            $total_price = 0;
            $total_tax = 0;
            $product = array();
            $subtotal = 0;
            foreach ($orders_products as $orders_products_data) {
                $product_attribute = DB::table('orders_products_attributes')
                    ->where([
                        ['orders_products_id', '=', $orders_products_data->orders_products_id],
                        ['orders_id', '=', $orders_products_data->orders_id],
                    ])
                    ->get();

                $orders_products_data->attribute = $product_attribute;
                $product[$i] = $orders_products_data;
                $total_price = $total_price + $orders_products[$i]->final_price;

                $subtotal += $orders_products[$i]->final_price;

                $i++;
            }
            $data->data = $product;
            $orders_data[] = $data;
        }

        $orders_status_history = DB::table('orders_status_history')
            ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
            ->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
            ->where('orders_status_description.language_id', '=', $language_id)
            ->where('role_id', '<=', 2)
            ->orderBy('orders_status_history.date_added', 'desc')
            ->where('orders_id', '=', $orders_id)->get();

        $orders_status = DB::table('orders_status')
            ->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
            ->where('orders_status_description.language_id', '=', $language_id)->where('role_id', '<=', 2)->get();

        $ordersData['message'] = $message;
        $ordersData['errorMessage'] = $errorMessage;
        $ordersData['orders_data'] = $orders_data;
        $ordersData['total_price'] = $total_price;
        $ordersData['orders_status'] = $orders_status;
        $ordersData['orders_status_history'] = $orders_status_history;
        $ordersData['subtotal'] = $subtotal;

        //get function from other controller
        $ordersData['currency'] = $this->myVarsetting->getSetting();

        return view("admin.Orders.vieworder", $title)->with('data', $ordersData);
    }

    //update order
    public function updateOrder(Request $request)
    {

            $orders_status = $request->orders_status;
            $comments = $request->comments;
            $orders_id = $request->orders_id;
            $old_orders_status = $request->old_orders_status;
            $date_added = date('Y-m-d h:i:s');

            //get function from other controller

            $setting = $this->myVarsetting->getSetting();

            $status = DB::table('orders_status')->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
                ->where('orders_status_description.language_id', '=', 1)->where('role_id', '<=', 2)->where('orders_status_description.orders_status_id', '=', $orders_status)->get();

            if ($old_orders_status == $orders_status) {
                return redirect()->back()->with('error', Lang::get("labels.StatusChangeError"));
            } else {

                //orders status history
                $orders_history_id = DB::table('orders_status_history')->insertGetId(
                    ['orders_id' => $orders_id,
                        'orders_status_id' => $orders_status,
                        'date_added' => $date_added,
                        'customer_notified' => '1',
                        'comments' => $comments,
                    ]);

                $orders = DB::table('orders')->where('orders_id', '=', $orders_id)
                    ->where('customers_id', '!=', '')->get();

                $customers_id = $orders[0]->customers_id;
                $order_price = $orders[0]->order_price;

                if ($orders_status == '2') {

                    $orders_products = DB::table('orders_products')->where('orders_id', '=', $orders_id)->get();

                    foreach ($orders_products as $products_data) {
                        DB::table('products')->where('products_id', $products_data->products_id)->update([
                            'products_quantity' => DB::raw('products_quantity - "' . $products_data->products_quantity . '"'),
                            'products_ordered' => DB::raw('products_ordered + 1'),
                        ]);
                    }


                    /*Reward Point Start*/
                    // get current point info of customer

                    $one_point_to_tk = DB::table('withdraw_categories')
                        ->latest('id')
                        ->pluck('one_point_to_tk')
                        ->first();


                    $get_customer_current_point_infos = DB::table('users')
                        ->select(
                            'membership_category',
                            'current_reward_point',
                            'current_reward_amount',
                            'current_withdraw_point',
                            'current_withdraw_amount'
                        )
                        ->where('id',$customers_id)
                        ->first();

                    $get_customer_reward_point_category_infos = DB::table('customer_reward_point_categories')
                        ->select(
                            'name',
                            'from_point',
                            'to_point',
                            'get_point',
                            'on_amount'
                        )
                        ->where('name',$get_customer_current_point_infos->membership_category)
                        ->first();

                    if($order_price >= $get_customer_reward_point_category_infos->on_amount){

                        $filter_point = $order_price/$get_customer_reward_point_category_infos->on_amount;
                        $get_point = $get_customer_reward_point_category_infos->get_point * (int)$filter_point;

                        DB::table('customer_reward_points')->insertGetId(
                            [	'customer_id' => $customers_id,
                                'order_id' => $orders_id,
                                'order_price'  =>  $order_price,
                                'get_reward_point' => $get_point,
                                'get_reward_point_amount'  => $one_point_to_tk*$get_point,
                                'created_at'	             =>   date('Y-m-d H:i:s'),
                                'updated_at'	             =>   date('Y-m-d H:i:s')
                            ]);

                        // update customer current point

                        $current_reward_point = $get_point + $get_customer_current_point_infos->current_reward_point;
                        $current_reward_amount = ($one_point_to_tk*$get_point) + $get_customer_current_point_infos->current_reward_amount;

                        $membership_category = DB::table('customer_reward_point_categories')
                            ->where('from_point','<=',$current_reward_point)
                            ->where('to_point','>=',$current_reward_point)
                            ->pluck('name')
                            ->first();

                        DB::table('users')->where('id', '=', $customers_id)->update([
                            'membership_category' => $membership_category,
                            'current_reward_point' => $current_reward_point,
                            'current_reward_amount' => $current_reward_amount
                        ]);
                    }

                    /*Reward Point End*/
                }



                $data = array();
                $data['customers_id'] = $customers_id;
                $data['orders_id'] = $orders_id;
                $data['status'] = $status[0]->orders_status_name;

                return redirect()->back()->with('message', Lang::get("labels.OrderStatusChangedMessage"));
            }



    }

    //deleteorders
    public function deleteOrder(Request $request)
    {
        DB::table('orders')->where('orders_id', $request->orders_id)->delete();
        DB::table('orders_products')->where('orders_id', $request->orders_id)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.OrderDeletedMessage")]);
    }

    //view order detail
    public function invoiceprint(Request $request)
    {

        $title = array('pageTitle' => Lang::get("labels.ViewOrder"));
        $language_id = '1';
        $orders_id = $request->id;

        $message = array();
        $errorMessage = array();

        DB::table('orders')->where('orders_id', '=', $orders_id)
            ->where('customers_id', '!=', '')->update(['is_seen' => 1]);

        $order = DB::table('orders')
            ->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
            ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
            ->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
            ->where('orders_status_description.language_id', '=', $language_id)->where('role_id', '<=', 2)
            ->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();

        foreach ($order as $data) {
            $orders_id = $data->orders_id;

            $orders_products = DB::table('orders_products')
                ->join('products', 'products.products_id', '=', 'orders_products.products_id')
                ->select('orders_products.*', 'products.products_image as image')
                ->where('orders_products.orders_id', '=', $orders_id)->get();
            $i = 0;
            $total_price = 0;
            $total_tax = 0;
            $product = array();
            $subtotal = 0;
            foreach ($orders_products as $orders_products_data) {

                //categories
                $categories = DB::table('products_to_categories')
                    ->leftjoin('categories', 'categories.categories_id', 'products_to_categories.categories_id')
                    ->leftjoin('categories_description', 'categories_description.categories_id', 'products_to_categories.categories_id')
                    ->select('categories.categories_id', 'categories_description.categories_name', 'categories.categories_image', 'categories.categories_icon', 'categories.parent_id')
                    ->where('products_id', '=', $orders_products_data->orders_products_id)
                    ->where('categories_description.language_id', '=', $language_id)->get();

                $orders_products_data->categories = $categories;

                $product_attribute = DB::table('orders_products_attributes')
                    ->where([
                        ['orders_products_id', '=', $orders_products_data->orders_products_id],
                        ['orders_id', '=', $orders_products_data->orders_id],
                    ])
                    ->get();

                $orders_products_data->attribute = $product_attribute;
                $product[$i] = $orders_products_data;
                $total_price = $total_price + $orders_products[$i]->final_price;

                $subtotal += $orders_products[$i]->final_price;

                $i++;
            }
            $data->data = $product;
            $orders_data[] = $data;
        }

        $orders_status_history = DB::table('orders_status_history')
            ->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
            ->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
            ->where('orders_status_description.language_id', '=', $language_id)->where('role_id', '<=', 2)
            ->orderBy('orders_status_history.date_added', 'desc')
            ->where('orders_id', '=', $orders_id)->get();

        $orders_status = DB::table('orders_status')->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
            ->where('orders_status_description.language_id', '=', $language_id)->where('role_id', '<=', 2)->get();

        $ordersData['message'] = $message;
        $ordersData['errorMessage'] = $errorMessage;
        $ordersData['orders_data'] = $orders_data;
        $ordersData['total_price'] = $total_price;
        $ordersData['orders_status'] = $orders_status;
        $ordersData['orders_status_history'] = $orders_status_history;
        $ordersData['subtotal'] = $subtotal;

        //get function from other controller

        $ordersData['currency'] = $this->myVarsetting->getSetting();

        return view("admin.Orders.invoiceprint", $title)->with('data', $ordersData);

    }

    public function commentsOrder(Request $request){
        //dd($request->all());
        $orders_status_history_id = $request->orders_status_history_id;
        $order_comments = $request->order_comments;
        DB::table('orders_status_history')
            ->where('orders_status_history_id', '=', $orders_status_history_id)
            ->update(['comments' => $order_comments]);

        return redirect()->back()->with('message', 'Comments updated.');
    }

}
