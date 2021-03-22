<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\CustomerRewardPointCategory;
use App\Models\Core\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class CustomerRewardPointCategoryController extends Controller
{
    public function __construct(CustomerRewardPointCategory $CustomerRewardPointCategory,Setting $setting)
    {
        $this->CustomerRewardPointCategory = $CustomerRewardPointCategory;
        $this->myVarSetting = new SiteSettingController($setting);

    }

    public function display(Request $request){

        $title = array('pageTitle' => 'List Customer Reward Point Category');
        $result = array();
        $message = array();
//        $customerRewardPointCategories = CustomerRewardPointCategory::sortable()
//            ->orderBy('created_at', 'DESC')
//            ->paginate(7);
        $customerRewardPointCategories = CustomerRewardPointCategory::orderBy('created_at', 'DESC')
            ->paginate(7);

        $result['customerRewardPointCategories'] = $customerRewardPointCategories;
        //get function from other controller
        $result['currency'] = $this->myVarSetting->getSetting();

        return view("admin.customer_reward_point_category.index", $title)->with('result', $result)->with('customerRewardPointCategories',$customerRewardPointCategories);
    }

    public function add(Request $request){

        $title = array('pageTitle' => 'Add Customer Reward Point Category');
        $result = array();
        $message = array();
        $result['message'] = $message;

        return view("admin.customer_reward_point_category.add", $title)->with('result', $result);
    }

    public function insert(Request $request){
        //dd($request->all());
        $name = $request->name;
        $from_point = $request->from_point;
        $to_point = $request->to_point;
        $get_point = $request->get_point;
        $on_amount = $request->on_amount;


//        $validator = Validator::make(
//            array(
//                'name'    => 'required',
//                'from_point'    => 'required',
//                'to_point'    => 'required',
//                'get_point'    => 'required',
//                'on_amount'    => 'required'
//            )
//        );
        $validator = Validator::make(
            array(
                'name'    => $request->name,
            ),
            array(
                'name'    => 'required',
            )
        );
        //dd($request->all());
        //check validation
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            //check coupon already exist
            $customerRewardPointCategory = $this->CustomerRewardPointCategory->customerRewardPointCategory($name);

            if(count($customerRewardPointCategory)>0) {
                return redirect()->back()->withErrors('Customer Reward Point Category Name Already Exists!')->withInput();
            }else{

                //insert record
                $insert_id = $this->CustomerRewardPointCategory->addCustomerRewardPointCategory($name,$from_point,$to_point,$get_point,$on_amount);
                if($insert_id){
                    return redirect('admin/customer_reward_point_category/display')->with('success', 'Successfully Added Customer Reward Point Category');
                }
            }
        }

    }

    public function edit($id)
    {
        $title = array('pageTitle' => 'Edit Customer Reward Point Category');
        $result = array();
        $message = array();
        $result['message'] = $message;
        //coupon
        $customer_reward_point_category = $this->CustomerRewardPointCategory->getCustomerRewardPointCategory($id);
        $result['customer_reward_point_category'] = $customer_reward_point_category;

        return view("admin.customer_reward_point_category.edit", $title)->with('result', $result);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $from_point = $request->from_point;
        $to_point = $request->to_point;
        $get_point = $request->get_point;
        $on_amount = $request->on_amount;


        $validator = Validator::make(
            array(
                'name'    => $request->name,
            ),
            array(
                'name'    => 'required',
            )
        );

        //check validation
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            //check coupon already exist
            $customerRewardPointCategory = $this->CustomerRewardPointCategory->customerRewardPointCategory($name);

            if(count($customerRewardPointCategory)>1) {
                return redirect()->back()->withErrors('Customer Reward Point Category Name Already Exists!')->withInput();
            }else{
                //update record
                $update_id = $this->CustomerRewardPointCategory->updateCustomerRewardPointCategory($id,$name,$from_point,$to_point,$get_point,$on_amount);
                return redirect('admin/customer_reward_point_category/display')->with('success', 'Successfully Added Customer Reward Point Category');
            }
        }
    }


    public function delete(Request $request){

        //$deletecoupon = DB::table('coupons')->where('coupans_id', '=', $request->id)->delete();
        DB::table('customer_reward_point_categories')->where('id', '=', $request->id)->update([
            'status' => 0
        ]);
        return redirect()->back()->withErrors(['Customer Reward Point Category Soft Deleted']);

    }
}
