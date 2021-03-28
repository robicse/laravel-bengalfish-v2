<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerWithdrawRequest extends Model
{
    public function updateCustomerWithdrawRequest($customer_id,$customer_withdraw_request_id,$request_point,$request_amount,$received_point,$received_amount,$request_payment_by,$payment_by_number,$transaction_id){

        $update_id = DB::table('customer_withdraw_requests')->where('id', '=', $customer_withdraw_request_id)->update([
            'received_point'  	 				 =>   $received_point,
            'received_amount'				     =>   $received_amount,
            'payment_status'				     =>   'Paid',
            'transaction_id'	 			     =>   $transaction_id
        ]);

        $user_info = DB::table('users')->where('id', $customer_id)->first();
        $previous_current_withdraw_point = $user_info->current_reward_point;
        $previous_current_withdraw_amount = $user_info->current_reward_amount;

        DB::table('users')->where('id', '=', $customer_id)->update([
            'current_withdraw_point' => $previous_current_withdraw_point + $received_point,
            'current_withdraw_amount' => $previous_current_withdraw_amount + $received_amount
        ]);
        return $update_id;
    }
}
