<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WithdrawCategory extends Model
{
    public $timestamps = true;
    public function addWithdrawCategory($one_point_to_tk,$minimum_withdraw_point,$per_month_withdraw_point_limit){
        $insert_id = DB::table('withdraw_categories')->insertGetId([
            'one_point_to_tk'  	 				 =>   $one_point_to_tk,
            'minimum_withdraw_point'			 =>   $minimum_withdraw_point,
            'per_month_withdraw_point_limit'	 =>   $per_month_withdraw_point_limit,
            'created_at'	                     =>   date('Y-m-d H:i:s'),
            'updated_at'	                     =>   date('Y-m-d H:i:s')
        ]);
        return $insert_id;
    }

    public function getWithdrawCategory($id){

        $customerRewardPointCategory = DB::table('withdraw_categories')->where('id', '=', $id)->first();


        return $customerRewardPointCategory;
    }

    public function updateWithdrawCategory($id,$one_point_to_tk,$minimum_withdraw_point,$per_month_withdraw_point_limit){
        //insert record
        $update_id = DB::table('withdraw_categories')->where('id', '=', $id)->update([
            'one_point_to_tk'  	 				 =>   $one_point_to_tk,
            'minimum_withdraw_point'			 =>   $minimum_withdraw_point,
            'per_month_withdraw_point_limit'	 =>   $per_month_withdraw_point_limit,
            'updated_at'	                     =>   date('Y-m-d H:i:s')
        ]);
        return $update_id;
    }
}
