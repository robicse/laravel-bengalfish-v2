<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\CustomerRewardPointCategory;
use App\Models\Core\Setting;
use App\Models\Core\WithdrawCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WithdrawCategoryController extends Controller
{
    public function __construct(WithdrawCategory $WithdrawCategory,Setting $setting)
    {
        $this->WithdrawCategory = $WithdrawCategory;
        $this->myVarSetting = new SiteSettingController($setting);

    }

    public function display(Request $request){

//        $get_customer_reward_point_category_infos = DB::table('customer_reward_point_categories')
//            ->select(
//                'name',
//                'from_point',
//                'to_point',
//                'get_point',
//                'on_amount'
//            )
//            ->where('name',"General Customer")
//            ->first();
//        dd($get_customer_reward_point_category_infos->from_point);
        $title = array('pageTitle' => 'List Withdraw Category');
        $result = array();
        $message = array();
        $withdrawCategories = WithdrawCategory::orderBy('created_at', 'DESC')
            ->paginate(7);

        $result['withdrawCategories'] = $withdrawCategories;
        //get function from other controller
        $result['currency'] = $this->myVarSetting->getSetting();

        return view("admin.withdraw_category.index", $title)->with('result', $result)->with('withdrawCategories',$withdrawCategories);
    }

    public function add(Request $request){

        $title = array('pageTitle' => 'Add Withdraw Category');
        $result = array();
        $message = array();
        $result['message'] = $message;

        return view("admin.withdraw_category.add", $title)->with('result', $result);
    }

    public function insert(Request $request){
        //dd($request->all());
        $one_point_to_tk = $request->one_point_to_tk;
        $minimum_withdraw_point = $request->minimum_withdraw_point;
        $per_month_withdraw_point_limit = $request->per_month_withdraw_point_limit;

        $validator = Validator::make(
            array(
                'one_point_to_tk'    => $request->one_point_to_tk,
            ),
            array(
                'one_point_to_tk'    => 'required',
            )
        );

        //check validation
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            //insert record
            $insert_id = $this->WithdrawCategory->addWithdrawCategory($one_point_to_tk,$minimum_withdraw_point,$per_month_withdraw_point_limit);
            if($insert_id){
                return redirect('admin/customer_reward_point_category/display')->with('success', 'Successfully Added Withdraw Category');
            }

        }

    }

    public function edit($id)
    {
        $title = array('pageTitle' => 'Edit Withdraw Category');
        $result = array();
        $message = array();
        $result['message'] = $message;
        //coupon
        $withdraw_category = $this->WithdrawCategory->getWithdrawCategory($id);
        $result['withdraw_category'] = $withdraw_category;

        return view("admin.withdraw_category.edit", $title)->with('result', $result);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $one_point_to_tk = $request->one_point_to_tk;
        $minimum_withdraw_point = $request->minimum_withdraw_point;
        $per_month_withdraw_point_limit = $request->per_month_withdraw_point_limit;


        $validator = Validator::make(
            array(
                'one_point_to_tk'    => $request->one_point_to_tk,
            ),
            array(
                'one_point_to_tk'    => 'required',
            )
        );

        //check validation
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            //update record
            $this->WithdrawCategory->updateWithdrawCategory($id,$one_point_to_tk,$minimum_withdraw_point,$per_month_withdraw_point_limit);
            return redirect('admin/withdraw_category/display')->with('success', 'Successfully Updated Withdraw Category');
        }
    }


    public function delete(Request $request){

        //$deletecoupon = DB::table('coupons')->where('coupans_id', '=', $request->id)->delete();
        DB::table('withdraw_categories')->where('id', '=', $request->id)->update([
            'status' => 0
        ]);
        return redirect()->back()->withErrors(['Withdraw Category Soft Deleted']);

    }
}
