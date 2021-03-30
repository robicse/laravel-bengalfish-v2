<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerRewardPointCategory extends Model
{
    public  function CustomerRewardPointCategory($name){

        $customer_reward_point_category_info = DB::table('customer_reward_point_categories')->where('name','=', $name)->get();

        return $customer_reward_point_category_info;
    }

    public function addCustomerRewardPointCategory($name,$from_point,$to_point,$get_point,$on_amount){
        $insert_id = DB::table('customer_reward_point_categories')->insertGetId([
            'name'  	 				 =>   $name,
            'from_point'				 =>   $from_point,
            'to_point'				     =>   $to_point,
            'get_point'	 			     =>   $get_point,
            'on_amount'	 	 			 =>   $on_amount,
            'created_at'	             =>   date('Y-m-d H:i:s'),
            'updated_at'	             =>   date('Y-m-d H:i:s')
        ]);
        return $insert_id;
    }

    public function getCustomerRewardPointCategory($id){

        $customerRewardPointCategory = DB::table('customer_reward_point_categories')->where('id', '=', $id)->first();


        return $customerRewardPointCategory;
    }

    public function updateCustomerRewardPointCategory($id,$name,$from_point,$to_point,$get_point,$on_amount){
        //insert record
        $update_id = DB::table('customer_reward_point_categories')->where('id', '=', $id)->update([
            'name'  	 				 =>   $name,
            'from_point'				 =>   $from_point,
            'to_point'				     =>   $to_point,
            'get_point'	 			     =>   $get_point,
            'on_amount'	 	 			 =>   $on_amount,
            'updated_at'	             =>   date('Y-m-d H:i:s')
        ]);
        return $update_id;
    }
}
