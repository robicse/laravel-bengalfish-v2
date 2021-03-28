<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\CustomerRewardPointCategory;
use App\Models\Core\CustomerWithdrawRequest;
use App\Models\Core\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerWithdrawRequestController extends Controller
{
    public function __construct(CustomerWithdrawRequest $CustomerWithdrawRequest,Setting $setting)
    {
        $this->CustomerWithdrawRequest = $CustomerWithdrawRequest;
        $this->myVarSetting = new SiteSettingController($setting);

    }

    public function display(Request $request){

        $title = array('pageTitle' => 'List Customer Reward Point Withdraw Request');
        $result = array();
        $message = array();
//        $customerRewardPointCategories = CustomerRewardPointCategory::sortable()
//            ->orderBy('created_at', 'DESC')
//            ->paginate(7);
        $withdrawRequestLists = CustomerWithdrawRequest::orderBy('id', 'DESC')
            ->paginate(7);

        $result['withdrawRequestLists'] = $withdrawRequestLists;
        //get function from other controller
        $result['currency'] = $this->myVarSetting->getSetting();

        return view("admin.customer_withdraw_request.index", $title)->with('result', $result)->with('withdrawRequestLists',$withdrawRequestLists);
    }

    public function insert(Request $request)
    {
        //dd($request->all());
        $customer_id = $request->customer_id;
        $customer_withdraw_request_id = $request->customer_withdraw_request_id;
        $request_point = $request->request_point;
        $request_amount = $request->request_amount;
        $received_point = $request->received_point;
        $received_amount = $request->received_amount;
        $request_payment_by = $request->request_payment_by;
        $payment_by_number = $request->payment_by_number;
        $transaction_id = $request->transaction_id;

        $validator = Validator::make(
            array(
                'received_amount'    => $request->received_amount,
            ),
            array(
                'received_amount'    => 'required',
            )
        );
        //dd($request->all());
        //check validation
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            //insert record
            $insert_id = $this->CustomerWithdrawRequest->updateCustomerWithdrawRequest($customer_id,$customer_withdraw_request_id,$request_point,$request_amount,$received_point,$received_amount,$request_payment_by,$payment_by_number,$transaction_id);
            if($insert_id){
                return redirect('admin/customer_reward_point_withdraw/display')->with('success', 'Successfully Updated Paid Amount.');
            }
        }
    }
}
