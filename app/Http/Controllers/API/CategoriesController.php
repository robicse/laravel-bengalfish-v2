<?php
namespace App\Http\Controllers\API;
use App\Helpers\UserInfo;
use App\Models\Core\Categories;
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
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use \Firebase\JWT\JWT;
use Intervention\Image\Facades\Image;
use App\Models\Web\Products;


class CategoriesController extends Controller
{
     public function __construct(
        Products $products
    ) {
        $this->products = $products;



    }
    public $successStatus = 200;
    public $failStatus = 401;



    /*custom base url*/
    public function custom_live_base_url(){
        //$category_image = $_SERVER['SERVER_NAME'];
        $root = (isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
        return $root;
    }

    public function custom_localhost_base_url(){
        //$category_image = $_SERVER['SERVER_NAME'];
        $root=(isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'];
        $root.= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        return $root;
    }
    /*custom base url*/



    public function category()
    {
        //$category=Categories::all();
        $categories = DB::table('categories')
            ->join('categories_description', 'categories.categories_id', '=', 'categories_description.categories_id')
            ->join('images', 'categories.categories_image', '=', 'images.id')
            ->join('image_categories', 'image_categories.image_id', '=', 'images.id')
            ->where('categories.parent_id',0)
            ->where('categories_description.language_id',1)
            ->select('categories.*', 'images.name as category_image','categories_description.categories_name as categories_name','image_categories.path as category_image')
            ->get();

        $category = [];
        foreach($categories as $data){

            $category_image = $this->custom_live_base_url().'/'.$data->category_image;
            //$category_image = $this->custom_localhost_base_url().$data->category_image;

            $nested_data['categories_id'] = $data->categories_id;
            $nested_data['parent_id'] = $data->parent_id;
            $nested_data['categories_name'] = $data->categories_name;
            $nested_data['categories_slug'] = $data->categories_slug;
            $nested_data['cat_slogan'] = $data->cat_slogan;
            $nested_data['position_sequence'] = $data->position_sequence;
            $nested_data['categories_status'] = $data->categories_status;
            $nested_data['categories_image'] = $category_image;
            $category[] = $nested_data;
        }
        return response()->json(['success'=>true,'response'=>$category], $this->successStatus);
    }

    public function subcategory(Request $request)
    {
        //$category=Categories::all();
        $categories = DB::table('categories')
            ->join('categories_description', 'categories.categories_id', '=', 'categories_description.categories_id')
            ->join('images', 'categories.categories_image', '=', 'images.id')
            ->join('image_categories', 'image_categories.image_id', '=', 'images.id')
            ->where('categories.parent_id',$request->category_id)
            ->where('categories_description.language_id',1)
            ->select('categories.*', 'images.name as category_image','categories_description.categories_name as categories_name','image_categories.path as category_image')
            ->get();

        $category = [];
        foreach($categories as $data){

            $category_image = $this->custom_live_base_url().'/'.$data->category_image;
            //$category_image = $this->custom_localhost_base_url().$data->category_image;

            $nested_data['categories_id'] = $data->categories_id;
            $nested_data['parent_id'] = $data->parent_id;
            $nested_data['categories_name'] = $data->categories_name;
            $nested_data['categories_slug'] = $data->categories_slug;
            $nested_data['cat_slogan'] = $data->cat_slogan;
            $nested_data['position_sequence'] = $data->position_sequence;
            $nested_data['categories_status'] = $data->categories_status;
            $nested_data['categories_image'] = $category_image;
            $category[] = $nested_data;
        }
        return response()->json(['success'=>true,'response'=>$category], $this->successStatus);

    }

    public function categoryByProduct(Request $request)
    {
        $categories = DB::table('products_to_categories')
            ->join('products', 'products_to_categories.products_id', '=', 'products.products_id')
            ->join('products_description', 'products_to_categories.products_id', '=', 'products_description.products_id')
            ->join('image_categories', 'products.products_image', '=', 'image_categories.image_id')
            ->where('products_to_categories.categories_id',$request->category_id)
            ->where('language_id',1)
            ->where('image_categories.image_type', 'ACTUAL')
            ->select('products_to_categories.*','products.*','products_description.*','image_categories.path as image_path')
            ->get();


        $category = [];
        foreach($categories as $data){

            $product_image = $this->custom_live_base_url().'/'.$data->image_path;
            //$product_image = $this->custom_localhost_base_url().$data->image_path;

            $nested_data['categories_id'] = $data->categories_id;
            $nested_data['products_id'] = $data->products_id;
            $nested_data['products_name'] = $data->products_name;
            $nested_data['products_slug'] = $data->products_slug;
            $nested_data['products_description'] = $data->products_description;
            $nested_data['products_price'] = $data->products_price;
            $nested_data['products_weight'] = $data->products_weight;
            $nested_data['products_weight_unit'] = $data->products_weight_unit;
            $nested_data['products_status'] = $data->products_status;
            $nested_data['products_ordered'] = $data->products_ordered;
            $nested_data['products_liked'] = $data->products_liked;
            $nested_data['is_feature'] = $data->is_feature;
            $nested_data['products_min_order'] = $data->products_min_order;
            $nested_data['image_path'] = $product_image;
            $category[] = $nested_data;
        }

        return response()->json(['success'=>true,'response'=>$category], $this->successStatus);

    }
    public function product(Request $request)
    {

        $images = DB::table('products')
            ->join('image_categories', 'products.products_image', '=', 'image_categories.image_id')
            ->where('products.products_id',$request->products_id)
            ->select('image_categories.path as product_image')
            ->get();

        $product = DB::table('products_to_categories')
            ->join('products', 'products_to_categories.products_id', '=', 'products.products_id')
            ->join('products_description', 'products.products_id', '=', 'products_description.products_id')
            ->where('products.products_id',$request->products_id)
            ->where('language_id',1)
            ->first();

        $product_info['products_name'] = $product->products_name;
        $product_info['products_slug'] = $product->products_slug;
        $product_info['products_description'] = $product->products_description;
        $product_info['products_price'] = $product->products_price;
        $product_info['products_weight'] = $product->products_weight;
        $product_info['products_weight_unit'] = $product->products_weight_unit;
        $product_info['products_status'] = $product->products_status;
        $product_info['products_ordered'] = $product->products_ordered;
        $product_info['products_liked'] = $product->products_liked;
        $product_info['is_feature'] = $product->is_feature;
        $product_info['products_min_order'] = $product->products_min_order;



        $image = [];
        foreach($images as $data){
            $product_image = $this->custom_live_base_url().'/'.$data->product_image;
            //$product_image = $this->custom_localhost_base_url().$data->product_image;

            $nested_data['product_image'] = $product_image;

            $image[] = $nested_data;
        }


        return response()->json(['success'=>true,'product'=>$product_info,'image'=>$image], $this->successStatus);

    }
    public function special_product()
    {
        session()->put('language_id', 1);
        $data = array('page_number' => '0', 'type' => 'special', 'limit' => 12, 'min_price' => '', 'max_price' => '');
        $special = $this->products->products($data);

        return response()->json(['success'=>true,'product'=>$special], $this->successStatus);
    }
    public function top_seller_product()
    {
        session()->put('language_id', 1);
        $data = array('page_number' => '0', 'type' => 'topseller', 'limit' => 12, 'min_price' => '', 'max_price' => '');
        $top_seller = $this->products->products($data);

        return response()->json(['success'=>true,'product'=>$top_seller], $this->successStatus);
    }
    public function most_liked()
    {
        session()->put('language_id', 1);
        $data = array('page_number' => '0', 'type' => 'mostliked', 'limit' => 12, 'min_price' => '', 'max_price' => '');
        $most_liked = $this->products->products($data);

        return response()->json(['success'=>true,'product'=>$most_liked], $this->successStatus);
    }

    public function newest_product()
    {
        session()->put('language_id', 1);
        $data = array('page_number' => '0', 'type' => '', 'limit' => 12, 'min_price' => '', 'max_price' => '');
        $newest_products = $this->products->products($data);

        return response()->json(['success'=>true,'product'=>$newest_products], $this->successStatus);
    }

public function productSearch(Request $request)
    {

        $products = DB::table('products')
//            ->join('products', 'products_to_categories.products_id', '=', 'products.products_id')
            ->join('products_description', 'products.products_id', '=', 'products_description.products_id')
            ->select('products_description.products_id','products_description.products_name','products.products_price')
            ->where('products_description.products_name', 'LIKE', '%'. $request->search. '%')
            ->where('language_id',1)
            ->get();

        return response()->json(['success'=>true,'response'=>$products], $this->successStatus);

    }
    public function register(Request $request)
    {
        $userReg = new User();
        $userReg->name = $request->name;
        //$userReg->email = $request->email;
        $userReg->phone = $request->phone;
        $userReg->address = $request->address;
        $userReg->password = Hash::make($request->password);
        $userReg->role_id = 3;
        $userReg->save();

//        $verification = VerificationCode::where('phone',$user->phone)->first();
//        if (!empty($verification)){
//            $verification->delete();
//        }
//        $verCode = new VerificationCode();
//        $verCode->phone = $user->phone;
//        $verCode->code = mt_rand(1111,9999);
//        $verCode->status = 0;
//        $verCode->save();
//        $text = "<#> Dear ".$user->name.", Your Priyojon OTP is: ".$verCode->code." /bCe8bIGKEiT";
//        UserInfo::smsAPI("0".$verCode->phone,$text);

        $success['token'] =  $userReg->createToken('ohmistiry')-> accessToken;
        $success['details'] =  $userReg;
        return response()->json(['success'=>true,'response' =>$success], $this-> successStatus);
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success'=>true,'response' => $user], $this-> successStatus);
    }

    public function vendorRegister(Request $request)
    {
//        $this->validate($request, [
//            'name' =>  'required',
//            //'email' =>  'required|email|unique:users,email',
//            'phone' => 'required|regex:/(01)[0-9]{9}/|unique:users,phone',
//            'password' =>  'required|min:6',
//        ]);

        $vendorReg = new User();
        $vendorReg->name = $request->name;
        //$userReg->email = $request->email;
        $vendorReg->phone = $request->phone;
        $vendorReg->lat = $request->lat;
        $vendorReg->long = $request->long;
        $vendorReg->address = $request->address;
        $vendorReg->password = Hash::make($request->password);
        $vendorReg->role_id = 2;
        $vendorReg->save();

        $vendorDetails = new VendorDetails();
        $vendorDetails->user_id = $vendorReg->id;
        $vendorDetails->category_id = $request->category_id;
        $vendorDetails->shop_name = $request->shop_name;
        $slug = Str::slug($request->shop_name, '-');
        if ($vendorDetails->slug == $slug) {
            $vendorDetails->slug = $slug . '-'.uniqid();
        }else {
            $vendorDetails->slug = $slug;
        }
//        $vendorDetails->address = $request->address;
        $vendorDetails->lat = $request->lat;
        $vendorDetails->lng = $request->long;
        $vendorDetails->address = $request->address;
        $vendorDetails->save();

        $success['token'] =  $vendorReg->createToken('ohmistiry')-> accessToken;
        $success['user'] =  $vendorReg;
        $success['details'] =  $vendorDetails;
        return response()->json(['success'=>true,'response' =>$success], $this-> successStatus);
    }

    public function otp(Request $request)
    {
        $privateKey = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCjPLBAfbHBN6NK
kywT7Vc34lcUwFA2fclUcxgsZozdhe+CKs0b//rpA8lEyfumL1c+1DY9WJmrk/F2
rEDk6AKzDIOGtextuVxuSCXh7bbNyRU7xRaUfDvIZmgF4n4M+aHLpSozP2wEBWPx
MQ4DAxpl8gdritoiwCXBdUwBRv7ZXPvih4zQCRrME9RVcP+v0KUWxu70s/oyAm3i
hXTxRDoLBXNcgmDgZdrnTyf4uQr1rBPpvlvJFVnIuBCToL8rCNDj0hFHHCQPt/Xt
h7Pk4iAPYp83nZZo0goxQ0M0P04nPi8vA4Vyqrj1RGU0mY2HNpghmhOxCK7tVtAz
CVtk3GWZAgMBAAECggEACnoNbOI0W6yUvidSe6fuwl80TBhovYqWtqUZbtYZIemj
/OR+3BZyleyAiTn2fQzKSnXH0mmimUTKSV3WX2q1q6S4t1drSIKOWGSoi8bcRfpr
MrYcVCTiGGNcN055XR9Tf1THL9lY9ywaZUNK/S4Ajnz5dXFIvVS8R52d5nIWTsCD
CHlrwuMEf7PHRwcegjhZbgXJQrTFsk506MyDm+af69PGu+dDixGIFf7/hr0tDc55
LoWoooCSPY/WeFkyOfsTjaKGPrZB83wfk2bKW2PpX8cS+f/zgkNUK/2S+IQULvET
imiHHguzbMJVtIpSNnWtyNp8AnZVs6cFU5r311QFAQKBgQDSq6zh9N3H8DhlfwsO
PyoH9PD8OO+2kBRs1kfEhqMus5LZyudaMfFuj625SPT/IHXOBu62PlLdp12duyuh
BLyNoKn1UZ9RnZXO/IXgGz0XLB+a7nOdq5EX1YBXXMuRSf2RQ3XC1HLLuTLNbYPp
cHkji6Ax1Xvzn1aFykOYYnWKGQKBgQDGXD3IIIzJHXrQ6bKckBRSxE70ZHVY+Ztm
xyrfi0CDWUq7TkMZaBvzf97PZZQCKCo9ABxOyfel60XYTloXL3RSrY25ruNB/aOt
dbGr+Rm8goZrwvfSK5dAstb2XX9KE+ATbhU8r+juCyKfAzrIHf09K1CK55BvKtcD
LZTof1gngQKBgHSKH6MHsEBQdQJAmz8QVhH8v+rnHERjw8jJaP4gdNj64bu1cPIG
OsasmK+s2krgDAsi7p7GL1UKptEAn4v9cMjwHdTliDq/8AWG0BA/QTY2ZG1yh2ar
aCJFKzAo1zvSMIFRTi2vFKn6E05szNIwM/Mj5ERtEpqchGKSl34OyXLRAoGAbaXh
lPXBPQ7pq//y9q6CPXo8dsXtRCC+MWMZJPbx02mRcNaDKtEs2j4t6REqbsLLDms4
OqlUFshmFHrDPBvEPkunkyPEhd7yElmDvB93hYfVTE469VeZRW9WelsWmNlBvRvV
63UF2kgXfDSp0qMZMC34WMBGS90ve5c/wAFctoECgYEAzOyKnNhiOD76qjsZ8sKs
evZr/EgtZo4KPrS1yq7M63uF2ChPEbKUQ3kVZBQHYz71ZBb7u3WlYtFZvDZgXfWB
ZLEUquTG0UXmnLkBrrJOTqszmen9TECmZgX1Z8839GRLDlIaYDQjZHtHMN2h7tah
GC3M3rKqOcjeQAGCd7T9pIo=
-----END PRIVATE KEY-----
EOD;
        $check = VerificationCode::where('code',$request->code)->where('phone',$request->phone)->where('status',0)->first();
        if (!empty($check)) {
            $check->status = 1;
            $check->update();
            $user = User::where('phone',$request->phone)->first();
            $user->status = 1;
            $user->save();

            $payload = array(
                "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
                "iat" => time(),
                "exp" =>  strtotime(date("Y/m/d H:i:s", strtotime("+30 minutes"))),
                "iss" => "firebase-adminsdk-mshz4@priyojon-af835.iam.gserviceaccount.com",
                "sub" => "firebase-adminsdk-mshz4@priyojon-af835.iam.gserviceaccount.com",
                "uid" => $user->id,
            );
            $firebase_token = JWT::encode($payload, $privateKey, 'RS256');

            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['firebase_token'] =$firebase_token;
            $success['details'] =  $user;


            return response()->json(['success'=>$success], $this-> successStatus);

        }else{
            return response()->json(['error'=>'Verification Failed'], $this-> failStatus);
        }
    }
    public function getotpCode(Request $request)
    {
        $code = VerificationCode::where('phone',$request->phone)->first();
        if (!empty($code)) {
            return response()->json(['code'=>$code], $this-> successStatus);
        }else{
            return response()->json(['error'=>'Code Failed'], $this-> failStatus);
        }
    }

    public function profile_edit(Request $request)
    {
//            $this->validate($request,[
//            'full_name' => 'required',
//            'address' => 'required',
//            'city_state' => 'required',
//            'blood_group' => 'required',
//        ]);
        $slug = Str::slug($request->full_name,'-');
        $drSlugCheck = User::where('slug', $slug)->first();
        if(!empty($drSlugCheck)) {
            $slug = $slug.'-'.Str::random(6);
        }
        $user = User::find(Auth::id());
        $user->name = $request->full_name;
        $user->slug =$slug;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->address = $request->address;
        $user->city_state = $request->city_state;
//        $user->lat = $request->lat;
//        $user->lng = $request->lng;
        $user->blood_group = $request->blood_group;

//        $image = $request->file('pro_img');
//        if (isset($image)) {
//            //make unique name for image
//            $currentDate = Carbon::now()->toDateString();
//            //delete old image.....
//            if(Storage::disk('public')->exists('uploads/profile/'.$user->image))
//            {
//                Storage::disk('public')->delete('uploads/profile/'.$user->image);
//            }
//            $imagename = $currentDate . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
////            resize image for category and upload
//            $proImage = Image::make($image)->resize(255, 255)->save($image->getClientOriginalExtension());
//            /* insert watermark at bottom-right corner with 10px offset */
//            $proImage->insert(public_path('/uploads/profile/logo.png'), 'bottom-right', 10, 10);
//            //Storage::disk('public')->put('uploads/profile/' . $imagename, $proImage);
//            $proImage->save(public_path('uploads/profile/'.$imagename));
//        } else {
//            $imagename = $user->image;
//        }
//        $user->image = $imagename;
        $user->update();
        return response()->json(['success'=>$user], $this-> successStatus);
    }

    public function user_appointment(Request $request)
    {
//        $user_app = DB::table('doctors')
//            ->join('users', 'doctors.user_id', '=', 'users.id')
//            ->join('departments', 'doctors.department_id', '=', 'departments.id')
//             ->join('doctor_schedule_time_slots', 'doctors.id', '=', 'doctor_schedule_time_slots.doctor_id')
//            ->select('users.id as user_id', 'users.name as user_name', 'users.slug as user_slug', 'users.image', 'users.phone', 'users.country_code', 'doctors.id as doctor_id','departments.name as department_name','doctor_schedule_time_slots.date as app_date','doctor_schedule_time_slots.time as app_time','doctor_schedule_time_slots.additional_info as additional_info')
//           ->where('doctor_schedule_time_slots.user_id','=',$request->user_id)
//            ->get();

        $user_app = DB::table('doctors')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->join('doctor_schedule_time_slots', 'doctors.id', '=', 'doctor_schedule_time_slots.doctor_id')
            ->join('clinics', 'clinics.id', '=', 'doctor_schedule_time_slots.clinic_id')
            ->select('users.id as user_id', 'users.name as user_name', 'users.slug as user_slug', 'users.image', 'users.phone', 'users.country_code', 'doctors.id as doctor_id','clinics.id as clinic_id','clinics.name as clinic_name','clinics.address as clinic_address','doctor_schedule_time_slots.date as app_date','doctor_schedule_time_slots.time as app_time','doctor_schedule_time_slots.additional_info as additional_info')
            ->where('doctor_schedule_time_slots.user_id','=',$request->user_id)
            ->get();

        return response()->json(['user_app' => $user_app], $this->successStatus);
    }
    public function user_home_appointment(Request $request)
    {
        $user_home_app = DB::table('doctors')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->join('doctor_at_home_schedule_time_slots', 'doctors.id', '=', 'doctor_at_home_schedule_time_slots.doctor_id')
            ->select('users.id as user_id', 'users.name as user_name', 'users.slug as user_slug', 'users.image', 'users.phone', 'users.country_code', 'doctors.id as doctor_id','doctor_at_home_schedule_time_slots.date as app_date','doctor_at_home_schedule_time_slots.time as app_time','doctor_at_home_schedule_time_slots.additional_info as additional_info')
            ->where('doctor_at_home_schedule_time_slots.user_id','=',$request->user_id)
            ->get();

        return response()->json(['user_home_app' => $user_home_app], $this->successStatus);
    }

    public function reset_pass_check_mobile(Request $request) {
        //dd('shdj');
        $user=\App\User::where('phone',$request->phone)->where('country_code',$request->country)->first();

        if(!empty($user)){

            $verification = \App\Password_Reset_Code::where('phone',$user->phone)->first();
            if (!empty($verification)){
                $verification->delete();
            }
            $verCode = new Password_Reset_Code();
            $verCode->phone = $user->phone;
            $verCode->code = mt_rand(1111,9999);
            $verCode->status = 0;
            $verCode->save();

            //dd('shdj');

            $text = "<#> Dear ".$user->name.", Your Password Reset Verification Code is ".$verCode->code." /bCe8bIGKEiT";
            UserInfo::smsAPI("0".$verCode->phone,$text);
            return response()->json(['status'=>$user], $this->successStatus);

        }else{
            $content="oops!! No User Found With This Phone Number.Please Sign Up First.";
            return response()->json(['status'=>$content], $this->successStatus);
        }
    }

    public function check_verification(Request $request) {
        $verification = \App\Password_Reset_Code::where('phone',$request->phone)->where('code',$request->code)->first();
        if (!empty($verification)){
            $user=\App\User::where('phone',$request->phone)->first();
            $rand_pass= $request->new_pass;
            $new_pass=Hash::make($rand_pass);
            $user->password=$new_pass;
            $user->update();
            $verification->status = 1;
            $verification->update();
            return response()->json(['status'=>'success'], $this->successStatus);
        }else{
            return response()->json(['status'=>'failed'], $this->successStatus);
        }
    }

    public function reset_password(Request $request) {
        $user=User::find(Auth::id());
        if (Hash::check($request->current_password, $user->password))
        {
            $newPass=Hash::make($request->new_password);
            $user->password=$newPass;
            $user->update();
            return response()->json(['status'=>'success'], $this->successStatus);

        }else{
            return response()->json(['status'=>'failed'], $this->failStatus);
        }
//        $userPass=bycrpt($user->password);

    }

    public function web_slide() {
        $slider_home= \App\Slider::where('type','front')->get();
        return response()->json(['success'=>$slider_home], $this->successStatus);
    }
    public function gc_slide() {
        $slider_gc= \App\Slider::where('type','goodkart')->get();
        return response()->json(['success'=>$slider_gc], $this->successStatus);
    }
    public function show_all_bloodDonor(Request $request)
    {
        $donors=User::where('is_donor',1)
            ->where('id','>',$request->last_id)
            ->take($request->limit)
            ->get();
        return response()->json(['success'=>$donors], $this->successStatus);
    }
    public function show_all_bloodDonor_type(Request $request)
    {
        $donors=User::where('is_donor',1)
            ->where('blood_group',$request->blood_type)
            ->where('id','>',$request->last_id)
            ->take($request->limit)
            ->get();
        return response()->json(['success'=>$donors], $this->successStatus);
    }

    public function all_like(Request $request)
    {
        if($request->type=="doctor"){
            $check=\App\User_like::where('doctor_id',$request->doctor_user_id)->where('user_id',Auth::user()->id)->first();
            if($check==NULL){
                $like=new \App\User_like();
                $like->user_id=Auth::user()->id;
                $like->doctor_id=$request->doctor_user_id;
                $like->save();
                $status=true;
            }
            else{
                $check->delete();
                $status=false;
            }
            return response()->json(['status'=>$status], $this->successStatus);
        }
        elseif($request->type=="cg"){
            $check=\App\User_caregiver_like::where('caregiver_id',$request->cg_user_id)->where('user_id',Auth::user()->id)->first();
            if($check==NULL){

                $like=new \App\User_caregiver_like();
                $like->user_id=Auth::user()->id;
                $like->caregiver_id=$request->cg_user_id;
                $like->save();
                $status=true;
            }
            else{
                $check->delete();
                $status=false;
            }
            return response()->json(['status'=>$status], $this->successStatus);
        }
        elseif ($request->type=="question"){
            $check=\App\User_question_like::where('question_id',$request->q_id)->where('user_id',Auth::user()->id)->first();
            if($check==NULL){
                $like=new \App\User_question_like();
                $like->user_id=Auth::user()->id;
                $like->question_id=$request->q_id;
                $like->save();
                $q_like=\App\AskQuestion::find($request->q_id);
                $q_like->question_like=$q_like->question_like+1;
                $q_like->update();
                $status=true;
            }
            else{
                $check->delete();
                $status=false;
                $q_like=\App\AskQuestion::find($request->q_id);
                if($q_like->question_like==0){
                    $q_like->question_like=0;
                }
                else{
                    $q_like->question_like=$q_like->question_like-1;
                }
                $q_like->update();
            }
            return response()->json(['status'=>$status], $this->successStatus);
        }
    }
    public function caregiver_like(Request $request)
    {

        $check=\App\User_caregiver_like::where('caregiver_id',$request->cg_user_id)->where('user_id',Auth::user()->id)->first();
        if($check==NULL){

            $like=new \App\User_caregiver_like();
            $like->user_id=Auth::user()->id;
            $like->caregiver_id=$request->cg_user_id;
            $like->save();
            $status=true;
        }
        else{
            $check->delete();
            $status=false;
        }

        return response()->json(['status'=>$status], $this->successStatus);
    }
    public function question_like(Request $request)
    {

        $check=\App\User_question_like::where('question_id',$request->q_id)->where('user_id',Auth::user()->id)->first();
        if($check==NULL){
            $like=new \App\User_question_like();
            $like->user_id=Auth::user()->id;
            $like->question_id=$request->q_id;
            $like->save();
            $q_like=\App\AskQuestion::find($request->q_id);
            $q_like->question_like=$q_like->question_like+1;
            $q_like->update();
            $status=true;
        }
        else{
            $check->delete();
            $status=false;
            $q_like=\App\AskQuestion::find($request->q_id);
            if($q_like->question_like==0){
                $q_like->question_like=0;
            }
            else{
                $q_like->question_like=$q_like->question_like-1;
            }
            $q_like->update();
        }

        return response()->json(['status'=>$status], $this->successStatus);
    }

    public function check_like(Request $request)
    {
        if($request->type=="doctor"){
            $check=\App\User_like::where('doctor_id',$request->doctor_user_id)->where('user_id',Auth::user()->id)->first();
            if($check==NULL){
                $status=false;
            }
            else{
                $status=true;
            }
            return response()->json(['status'=>$status], $this->successStatus);
        }
        elseif($request->type=="cg"){
            $check=\App\User_caregiver_like::where('caregiver_id',$request->cg_user_id)->where('user_id',Auth::user()->id)->first();
            if($check==NULL){
                $status=false;
            }
            else{
                $status=true;
            }
            return response()->json(['status'=>$status], $this->successStatus);
        }
        elseif ($request->type=="question"){
            $check=\App\User_question_like::where('question_id',$request->q_id)->where('user_id',Auth::user()->id)->first();
            if($check==NULL){
                $status=false;
            }
            else{
                $status=true;
            }
            return response()->json(['status'=>$status], $this->successStatus);
        }
    }
    public function like_list(Request $request)
    {
        $doctors = DB::table('doctors')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->join('departments', 'doctors.department_id', '=', 'departments.id')
            ->join('user_likes', 'users.id', '=', 'user_likes.doctor_id')
            ->select('users.id as user_id', 'users.name as user_name', 'users.slug as user_slug', 'users.image', 'users.phone', 'users.country_code','users.gender', 'doctors.id as doctor_id', 'doctors.title', 'doctors.experience', 'doctors.clinic_cost', 'doctors.online_cost', 'doctors.home_cost', 'departments.name as department_name')
            ->where('doctors.is_active', '=', 1)
            ->where('user_likes.user_id', '=', Auth::user()->id)
            ->get();

        $caregivers = DB::table('users')
            ->join('user_caregiver_like', 'users.id', '=', 'user_caregiver_like.caregiver_id')
            ->where('user_caregiver_like.user_id', '=', Auth::user()->id)
            ->get();


        $question = DB::table('answer_by_questions')
            ->join('ask_questions', 'ask_questions.id', '=', 'answer_by_questions.question_id')
            ->join('user_question_like', 'answer_by_questions.id', '=', 'user_question_like.question_id')
            ->where('user_question_like.user_id', '=', Auth::user()->id)
            ->get();

//            $question=\App\User_question_like::where('user_id',Auth::user()->id)->get();
        return response()->json(['doctors'=>$doctors,'caregivers'=>$caregivers,'question'=>$question], $this->successStatus);
    }
    public function appoint(Request $request)
    {
        $appointmentCaregiver = DB::table('users')
            ->join('caregiver_schedule_time_slots', 'users.id', '=', 'caregiver_schedule_time_slots.caregiver_id')
            ->where('caregiver_schedule_time_slots.user_id', '=', Auth::user()->id)
            ->where('caregiver_schedule_time_slots.schedule_type', '=', $request->schedule_type)
            ->get();

//        $appointmentCaregiver=\App\CaregiverScheduleTimeSlot::where('user_id',Auth::user()->id)->get();
        return response()->json(['cg_appointment'=>$appointmentCaregiver], $this->successStatus);
    }

}
