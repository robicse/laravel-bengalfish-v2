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
use Illuminate\Support\Facades\Lang;
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
    public function __construct(Products $products){
        $this->products = $products;
    }
    public $successStatus = 200;
    public $authStatus = 401;
    public $failStatus = 402;
    public $ExistsStatus = 403;
    public $validationStatus = 404;



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

        $categories = DB::table('categories')
            ->join('categories_description', 'categories.categories_id', '=', 'categories_description.categories_id')
            ->join('images', 'categories.categories_image', '=', 'images.id')
            ->where('categories.parent_id',0)
            ->where('categories_description.language_id',1)
            ->select('categories.*', 'images.id as image_id','categories_description.categories_name as categories_name')
            ->get();

        $data = array();

        foreach ($categories as $category) {

            $image_path = DB::table('image_categories')->where('image_id',$category->image_id)->pluck('path')->first();

            //$category_image = $this->custom_live_base_url().'/'.$image_path;
            //$category_image = $this->custom_localhost_base_url().$image_path;

            $data[] = array(
                'categories_id' => $category->categories_id,
                'parent_id' => $category->parent_id,
                'categories_name' => $category->categories_name,
                'categories_slug' => $category->categories_slug,
                //'cat_slogan' => $category->cat_slogan,
                //'position_sequence' => $category->position_sequence,
                'categories_status' => $category->categories_status,
                //'categories_image' => $category_image,
                'categories_image' => $image_path,
            );
        }

        if(count($data) > 0){
            return response()->json(['success'=>true,'response'=>$data], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'response'=>'No Data Found.'], $this->failStatus);
        }
    }

    public function subcategory(Request $request)
    {

        $categories = DB::table('categories')
            ->join('categories_description', 'categories.categories_id', '=', 'categories_description.categories_id')
            ->join('images', 'categories.categories_image', '=', 'images.id')
            ->where('categories.parent_id',0)
            ->where('categories_description.language_id',1)
            ->where('categories.parent_id',$request->category_id)
            ->select('categories.*', 'images.id as image_id','categories_description.categories_name as categories_name')
            ->get();

        $data = array();

        foreach ($categories as $category) {

            $image_path = DB::table('image_categories')->where('image_id',$category->image_id)->pluck('path')->first();

            //$category_image = $this->custom_live_base_url().'/'.$image_path;
            //$category_image = $this->custom_localhost_base_url().$image_path;

            $data[] = array(
                'categories_id' => $category->categories_id,
                'parent_id' => $category->parent_id,
                'categories_name' => $category->categories_name,
                'categories_slug' => $category->categories_slug,
                //'cat_slogan' => $category->cat_slogan,
                //'position_sequence' => $category->position_sequence,
                'categories_status' => $category->categories_status,
                //'categories_image' => $category_image,
                'categories_image' => $image_path,
            );
        }

        if(count($data) > 0){
            return response()->json(['success'=>true,'response'=>$data], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'response'=>'No Data Found Using This Parent Category.'], $this->failStatus);
        }
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

            //$product_image = $this->custom_live_base_url().'/'.$data->image_path;
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
            //$nested_data['image_path'] = $product_image;
            $nested_data['image_path'] = $data->image_path;
            $category[] = $nested_data;
        }

        if($category){
            return response()->json(['success'=>true,'response'=>$category], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'response'=>'No Data Found Using This Category.'], $this->failStatus);
        }

    }

//    public function categoryByRelatedProduct(Request $request)
//    {
//        $categories = DB::table('products_to_categories')
//            ->join('products', 'products_to_categories.products_id', '=', 'products.products_id')
//            ->join('products_description', 'products_to_categories.products_id', '=', 'products_description.products_id')
//            ->join('image_categories', 'products.products_image', '=', 'image_categories.image_id')
//            ->where('products_to_categories.categories_id',$request->category_id)
//            ->where('language_id',1)
//            ->where('image_categories.image_type', 'ACTUAL')
//            ->select('products_to_categories.*','products.*','products_description.*','image_categories.path as image_path')
//            ->inRandomOrder()
//            ->limit(5) // here is yours limit
//            ->get();
//
//
//        $category = [];
//        foreach($categories as $data){
//
//            $product_image = $this->custom_live_base_url().'/'.$data->image_path;
//            //$product_image = $this->custom_localhost_base_url().$data->image_path;
//
//            $nested_data['categories_id'] = $data->categories_id;
//            $nested_data['products_id'] = $data->products_id;
//            $nested_data['products_name'] = $data->products_name;
//            $nested_data['products_slug'] = $data->products_slug;
//            $nested_data['products_description'] = $data->products_description;
//            $nested_data['products_price'] = $data->products_price;
//            $nested_data['products_weight'] = $data->products_weight;
//            $nested_data['products_weight_unit'] = $data->products_weight_unit;
//            $nested_data['products_status'] = $data->products_status;
//            $nested_data['products_ordered'] = $data->products_ordered;
//            $nested_data['products_liked'] = $data->products_liked;
//            $nested_data['is_feature'] = $data->is_feature;
//            $nested_data['products_min_order'] = $data->products_min_order;
//            $nested_data['image_path'] = $product_image;
//            $category[] = $nested_data;
//        }
//
//        return response()->json(['success'=>true,'response'=>$category], $this->successStatus);
//
//    }

    public function categoryByRelatedProduct(Request $request)
    {
        $categories = DB::table('products_to_categories')
            ->join('products', 'products_to_categories.products_id', '=', 'products.products_id')
            ->join('products_description', 'products_to_categories.products_id', '=', 'products_description.products_id')
            ->join('image_categories', 'products.products_image', '=', 'image_categories.image_id')
            ->where('products_to_categories.categories_id',$request->category_id)
            ->where('language_id',1)
            //->where('image_categories.image_type', 'ACTUAL')
            ->select('products_to_categories.*','products.*','products_description.*','image_categories.path as image_path')
            ->inRandomOrder()
            ->limit(5)
            ->get();


        $category = [];
        foreach($categories as $data){

            //$product_image = $this->custom_live_base_url().'/'.$data->image_path;
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
            //$nested_data['image_path'] = $product_image;
            $nested_data['image_path'] = $data->image_path;
            $category[] = $nested_data;
        }

        if($category){
            return response()->json(['success'=>true,'response'=>$category], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'response'=>'No Data Found Using This Category.'], $this->failStatus);
        }
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
            //$product_image = $this->custom_live_base_url().'/'.$data->product_image;
            //$product_image = $this->custom_localhost_base_url().$data->product_image;

            //$nested_data['product_image'] = $product_image;
            $nested_data['product_image'] = $data->product_image;

            $image[] = $nested_data;
        }


        if($image && $product){
            return response()->json(['success'=>true,'product'=>$product_info,'image'=>$image], $this->successStatus);
        }else{
            return response()->json(['success'=>false,'response'=>'No Data Found.'], $this->failStatus);
        }
    }

//    public function special_product()
//    {
//        session()->put('language_id', 1);
//        $data = array('page_number' => '0', 'type' => 'special', 'limit' => 12, 'min_price' => '', 'max_price' => '');
//        $special = $this->products->products($data);
//
//        return response()->json(['success'=>true,'product'=>$special], $this->successStatus);
//    }

    public function special_product()
    {
        $sortby	     = "specials.products_id";
        $order	     = "DESC";
        $currentDate = time();

        $categories = DB::table('products')
            ->leftJoin('products_description','products_description.products_id','=','products.products_id')
            ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id');


//        $categories->LeftJoin('specials', function ($join) use ($currentDate) {
//            $join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
//        })->select('products.products_id','products.products_quantity','products.products_image','products.products_price','products.products_weight','products.products_weight_unit','products.products_status','products.is_current','products.products_ordered','products.products_liked','products.low_limit','products.is_feature','products.products_slug','products.products_type','products.products_min_order','image_categories.path as image_path','products_description.products_name','products_description.products_description');
        $categories->LeftJoin('specials', 'specials.products_id', '=', 'products.products_id')
            ->select('products.products_id','products.products_quantity','products.products_image','products.products_price','products.products_weight','products.products_weight_unit','products.products_status','products.is_current','products.products_ordered','products.products_liked','products.low_limit','products.is_feature','products.products_slug','products.products_type','products.products_min_order','image_categories.path as image_path','products_description.products_name','products_description.products_description', 'specials.specials_new_products_price as discount_price');


        $categories->where('products_description.language_id','=',1)->where('products_status','=',1);
        $categories->where('specials.status','=', '1')->where('expires_date','>',  $currentDate);
        $categories->orderBy($sortby, $order)->groupBy('products.products_id');

        //count
        $total_record = $categories->get();
        $products  = $categories->get();

        $result = array();

        //check if record exist
        if(count($products)>0){

            $index = 0;
            foreach ($products as $products_data){
                $reviews = DB::table('reviews')
                    ->leftjoin('users', 'users.id', '=', 'reviews.customers_id')
                    ->leftjoin('reviews_description', 'reviews.reviews_id', '=', 'reviews_description.review_id')
                    ->select('reviews.*','reviews_description.reviews_text')
                    ->where('products_id', $products_data->products_id)
                    ->where('reviews_status', '1')
                    ->where('reviews_read', '1')
                    ->get();

                if (count($reviews) > 0) {
                    $five_star = 0;
                    $five_count = 0;

                    $four_star = 0;
                    $four_count = 0;

                    $three_star = 0;
                    $three_count = 0;

                    $two_star = 0;
                    $two_count = 0;

                    $one_star = 0;
                    $one_count = 0;

                    foreach ($reviews as $review) {

                        //five star ratting
                        if ($review->reviews_rating == '5') {
                            $five_star += $review->reviews_rating;
                            $five_count++;
                        }

                        //four star ratting
                        if ($review->reviews_rating == '4') {
                            $four_star += $review->reviews_rating;
                            $four_count++;
                        }
                        //three star ratting
                        if ($review->reviews_rating == '3') {
                            $three_star += $review->reviews_rating;
                            $three_count++;
                        }
                        //two star ratting
                        if ($review->reviews_rating == '2') {
                            $two_star += $review->reviews_rating;
                            $two_count++;
                        }

                        //one star ratting
                        if ($review->reviews_rating == '1') {
                            $one_star += $review->reviews_rating;
                            $one_count++;
                        }
                    }

                    $five_ratio = round($five_count / count($reviews) * 100);
                    $four_ratio = round($four_count / count($reviews) * 100);
                    $three_ratio = round($three_count / count($reviews) * 100);
                    $two_ratio = round($two_count / count($reviews) * 100);
                    $one_ratio = round($one_count / count($reviews) * 100);

                    $avarage_rate = (5 * $five_star + 4 * $four_star + 3 * $three_star + 2 * $two_star + 1 * $one_star) / ($five_star + $four_star + $three_star + $two_star + $one_star);
                    $total_user_rated = count($reviews);
                    $reviewed_customers = $reviews;
                } else {
                    $reviewed_customers = array();
                    $avarage_rate = 0;
                    $total_user_rated = 0;

                    $five_ratio = 0;
                    $four_ratio = 0;
                    $three_ratio = 0;
                    $two_ratio = 0;
                    $one_ratio = 0;
                }

                $products_data->rating = number_format($avarage_rate, 2);
                $products_data->total_user_rated = $total_user_rated;

                $products_data->five_ratio = $five_ratio;
                $products_data->four_ratio = $four_ratio;
                $products_data->three_ratio = $three_ratio;
                $products_data->two_ratio = $two_ratio;
                $products_data->one_ratio = $one_ratio;

                //review by users
                //$products_data->reviewed_customers = $reviewed_customers;
                $products_id = $products_data->products_id;

                //multiple images
//                $products_images = DB::table('products_images')
//                    ->LeftJoin('image_categories','products_images.image','=','image_categories.image_id')
//                    ->select('image_categories.path as image_path','image_categories.image_type')
//                    ->where('products_id','=', $products_id)
//                    ->orderBy('sort_order', 'ASC')
//                    ->get();
//                $products_data->images =  $products_images;

                $default_image_thumb = DB::table('products')
                    ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id')
                    ->select('image_categories.path as image_path','image_categories.image_type')
                    ->where('products_id','=', $products_id)
                    ->where('image_type','=', 'THUMBNAIL')
                    ->first();

                $products_data->default_thumb =  $default_image_thumb;

                //categories
                $categories = DB::table('products_to_categories')
                    ->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
                    ->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
                    ->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
                    ->where('products_id','=', $products_id)
                    ->where('categories_description.language_id','=', 1)->get();

                $products_data->categories =  $categories;

                $stocks = 0;
                $stockOut = 0;
                if($products_data->products_type == '0'){
                    $stocks = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','in')->sum('stock');
                    $stockOut = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','out')->sum('stock');
                }

                $products_data->defaultStock =  $stocks - $stockOut;

                //like product
                $categories = DB::table('liked_products')->where('liked_products_id', '=', $products_id)->get();

                if(count($categories)>0){
                    $products_data->isLiked = count($categories);
                }else{
                    $products_data->isLiked = 0;
                }
                array_push($result,$products_data);
            }
            $responseData = array('success'=>'1', 'product_data'=>$result,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>count($total_record));

        }else{
            $responseData = array('success'=>'0', 'product_data'=>$result,  'message'=>Lang::get('website.Empty record'), 'total_record'=>count($total_record));
        }

        return response()->json(['success'=>true,'product'=>$responseData], $this->successStatus);
    }

//    public function top_seller_product()
//    {
//        session()->put('language_id', 1);
//        $data = array('page_number' => '0', 'type' => 'topseller', 'limit' => 12, 'min_price' => '', 'max_price' => '');
//        $top_seller = $this->products->products($data);
//
//        return response()->json(['success'=>true,'product'=>$top_seller], $this->successStatus);
//    }

    public function top_seller_product()
    {
        $sortby	     = "products_ordered";
        $order	     = "DESC";
        $currentDate = time();

        $categories = DB::table('products')
            ->leftJoin('products_description','products_description.products_id','=','products.products_id')
            ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id');


        $categories->LeftJoin('specials', function ($join) use ($currentDate) {
            $join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
        })->select('products.products_id','products.products_quantity','products.products_image','products.products_price','products.products_weight','products.products_weight_unit','products.products_status','products.is_current','products.products_ordered','products.products_liked','products.low_limit','products.is_feature','products.products_slug','products.products_type','products.products_min_order','image_categories.path as image_path','products_description.products_name','products_description.products_description');


        $categories->where('products_description.language_id','=',1)->where('products_status','=',1);
        $categories->orderBy($sortby, $order)->groupBy('products.products_id');

        //count
        $total_record = $categories->get();
        $products  = $categories->get();

        $result = array();

        //check if record exist
        if(count($products)>0){

            $index = 0;
            foreach ($products as $products_data){
                $reviews = DB::table('reviews')
                    ->leftjoin('users', 'users.id', '=', 'reviews.customers_id')
                    ->leftjoin('reviews_description', 'reviews.reviews_id', '=', 'reviews_description.review_id')
                    ->select('reviews.*','reviews_description.reviews_text')
                    ->where('products_id', $products_data->products_id)
                    ->where('reviews_status', '1')
                    ->where('reviews_read', '1')
                    ->get();

                if (count($reviews) > 0) {
                    $five_star = 0;
                    $five_count = 0;

                    $four_star = 0;
                    $four_count = 0;

                    $three_star = 0;
                    $three_count = 0;

                    $two_star = 0;
                    $two_count = 0;

                    $one_star = 0;
                    $one_count = 0;

                    foreach ($reviews as $review) {

                        //five star ratting
                        if ($review->reviews_rating == '5') {
                            $five_star += $review->reviews_rating;
                            $five_count++;
                        }

                        //four star ratting
                        if ($review->reviews_rating == '4') {
                            $four_star += $review->reviews_rating;
                            $four_count++;
                        }
                        //three star ratting
                        if ($review->reviews_rating == '3') {
                            $three_star += $review->reviews_rating;
                            $three_count++;
                        }
                        //two star ratting
                        if ($review->reviews_rating == '2') {
                            $two_star += $review->reviews_rating;
                            $two_count++;
                        }

                        //one star ratting
                        if ($review->reviews_rating == '1') {
                            $one_star += $review->reviews_rating;
                            $one_count++;
                        }
                    }

                    $five_ratio = round($five_count / count($reviews) * 100);
                    $four_ratio = round($four_count / count($reviews) * 100);
                    $three_ratio = round($three_count / count($reviews) * 100);
                    $two_ratio = round($two_count / count($reviews) * 100);
                    $one_ratio = round($one_count / count($reviews) * 100);

                    $avarage_rate = (5 * $five_star + 4 * $four_star + 3 * $three_star + 2 * $two_star + 1 * $one_star) / ($five_star + $four_star + $three_star + $two_star + $one_star);
                    $total_user_rated = count($reviews);
                    $reviewed_customers = $reviews;
                } else {
                    $reviewed_customers = array();
                    $avarage_rate = 0;
                    $total_user_rated = 0;

                    $five_ratio = 0;
                    $four_ratio = 0;
                    $three_ratio = 0;
                    $two_ratio = 0;
                    $one_ratio = 0;
                }

                $products_data->rating = number_format($avarage_rate, 2);
                $products_data->total_user_rated = $total_user_rated;

                $products_data->five_ratio = $five_ratio;
                $products_data->four_ratio = $four_ratio;
                $products_data->three_ratio = $three_ratio;
                $products_data->two_ratio = $two_ratio;
                $products_data->one_ratio = $one_ratio;

                //review by users
                //$products_data->reviewed_customers = $reviewed_customers;
                $products_id = $products_data->products_id;

                //multiple images
//                $products_images = DB::table('products_images')
//                    ->LeftJoin('image_categories','products_images.image','=','image_categories.image_id')
//                    ->select('image_categories.path as image_path','image_categories.image_type')
//                    ->where('products_id','=', $products_id)
//                    ->orderBy('sort_order', 'ASC')
//                    ->get();
//                $products_data->images =  $products_images;

                $default_image_thumb = DB::table('products')
                    ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id')
                    ->select('image_categories.path as image_path','image_categories.image_type')
                    ->where('products_id','=', $products_id)
                    ->where('image_type','=', 'THUMBNAIL')
                    ->first();

                $products_data->default_thumb =  $default_image_thumb;

                //categories
                $categories = DB::table('products_to_categories')
                    ->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
                    ->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
                    ->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
                    ->where('products_id','=', $products_id)
                    ->where('categories_description.language_id','=', 1)->get();

                $products_data->categories =  $categories;

                $stocks = 0;
                $stockOut = 0;
                if($products_data->products_type == '0'){
                    $stocks = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','in')->sum('stock');
                    $stockOut = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','out')->sum('stock');
                }

                $products_data->defaultStock =  $stocks - $stockOut;

                //like product
                $categories = DB::table('liked_products')->where('liked_products_id', '=', $products_id)->get();

                if(count($categories)>0){
                    $products_data->isLiked = count($categories);
                }else{
                    $products_data->isLiked = 0;
                }
                array_push($result,$products_data);
            }
            $responseData = array('success'=>'1', 'product_data'=>$result,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>count($total_record));

        }else{
            $responseData = array('success'=>'0', 'product_data'=>$result,  'message'=>Lang::get('website.Empty record'), 'total_record'=>count($total_record));
        }

        return response()->json(['success'=>true,'product'=>$responseData], $this->successStatus);
    }

//    public function most_liked()
//    {
//        session()->put('language_id', 1);
//        $data = array('page_number' => '0', 'type' => 'mostliked', 'limit' => 12, 'min_price' => '', 'max_price' => '');
//        $most_liked = $this->products->products($data);
//
//        return response()->json(['success'=>true,'product'=>$most_liked], $this->successStatus);
//    }

    public function most_liked()
    {
        $sortby	     = "products_liked";
        $order	     = "DESC";
        $currentDate = time();

        $categories = DB::table('products')
            ->leftJoin('products_description','products_description.products_id','=','products.products_id')
            ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id');


        $categories->LeftJoin('specials', function ($join) use ($currentDate) {
            $join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
        })->select('products.products_id','products.products_quantity','products.products_image','products.products_price','products.products_weight','products.products_weight_unit','products.products_status','products.is_current','products.products_ordered','products.products_liked','products.low_limit','products.is_feature','products.products_slug','products.products_type','products.products_min_order','image_categories.path as image_path','products_description.products_name','products_description.products_description');


        $categories->where('products_description.language_id','=',1)->where('products_status','=',1);
        $categories->orderBy($sortby, $order)->groupBy('products.products_id');

        //count
        $total_record = $categories->get();
        $products  = $categories->get();

        $result = array();

        //check if record exist
        if(count($products)>0){

            $index = 0;
            foreach ($products as $products_data){
                $reviews = DB::table('reviews')
                    ->leftjoin('users', 'users.id', '=', 'reviews.customers_id')
                    ->leftjoin('reviews_description', 'reviews.reviews_id', '=', 'reviews_description.review_id')
                    ->select('reviews.*','reviews_description.reviews_text')
                    ->where('products_id', $products_data->products_id)
                    ->where('reviews_status', '1')
                    ->where('reviews_read', '1')
                    ->get();

                if (count($reviews) > 0) {
                    $five_star = 0;
                    $five_count = 0;

                    $four_star = 0;
                    $four_count = 0;

                    $three_star = 0;
                    $three_count = 0;

                    $two_star = 0;
                    $two_count = 0;

                    $one_star = 0;
                    $one_count = 0;

                    foreach ($reviews as $review) {

                        //five star ratting
                        if ($review->reviews_rating == '5') {
                            $five_star += $review->reviews_rating;
                            $five_count++;
                        }

                        //four star ratting
                        if ($review->reviews_rating == '4') {
                            $four_star += $review->reviews_rating;
                            $four_count++;
                        }
                        //three star ratting
                        if ($review->reviews_rating == '3') {
                            $three_star += $review->reviews_rating;
                            $three_count++;
                        }
                        //two star ratting
                        if ($review->reviews_rating == '2') {
                            $two_star += $review->reviews_rating;
                            $two_count++;
                        }

                        //one star ratting
                        if ($review->reviews_rating == '1') {
                            $one_star += $review->reviews_rating;
                            $one_count++;
                        }
                    }

                    $five_ratio = round($five_count / count($reviews) * 100);
                    $four_ratio = round($four_count / count($reviews) * 100);
                    $three_ratio = round($three_count / count($reviews) * 100);
                    $two_ratio = round($two_count / count($reviews) * 100);
                    $one_ratio = round($one_count / count($reviews) * 100);

                    $avarage_rate = (5 * $five_star + 4 * $four_star + 3 * $three_star + 2 * $two_star + 1 * $one_star) / ($five_star + $four_star + $three_star + $two_star + $one_star);
                    $total_user_rated = count($reviews);
                    $reviewed_customers = $reviews;
                } else {
                    $reviewed_customers = array();
                    $avarage_rate = 0;
                    $total_user_rated = 0;

                    $five_ratio = 0;
                    $four_ratio = 0;
                    $three_ratio = 0;
                    $two_ratio = 0;
                    $one_ratio = 0;
                }

                $products_data->rating = number_format($avarage_rate, 2);
                $products_data->total_user_rated = $total_user_rated;

                $products_data->five_ratio = $five_ratio;
                $products_data->four_ratio = $four_ratio;
                $products_data->three_ratio = $three_ratio;
                $products_data->two_ratio = $two_ratio;
                $products_data->one_ratio = $one_ratio;

                //review by users
                //$products_data->reviewed_customers = $reviewed_customers;
                $products_id = $products_data->products_id;

                //multiple images
//                $products_images = DB::table('products_images')
//                    ->LeftJoin('image_categories','products_images.image','=','image_categories.image_id')
//                    ->select('image_categories.path as image_path','image_categories.image_type')
//                    ->where('products_id','=', $products_id)
//                    ->orderBy('sort_order', 'ASC')
//                    ->get();
//                $products_data->images =  $products_images;

                $default_image_thumb = DB::table('products')
                    ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id')
                    ->select('image_categories.path as image_path','image_categories.image_type')
                    ->where('products_id','=', $products_id)
                    ->where('image_type','=', 'THUMBNAIL')
                    ->first();

                $products_data->default_thumb =  $default_image_thumb;

                //categories
                $categories = DB::table('products_to_categories')
                    ->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
                    ->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
                    ->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
                    ->where('products_id','=', $products_id)
                    ->where('categories_description.language_id','=', 1)->get();

                $products_data->categories =  $categories;

                $stocks = 0;
                $stockOut = 0;
                if($products_data->products_type == '0'){
                    $stocks = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','in')->sum('stock');
                    $stockOut = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','out')->sum('stock');
                }

                $products_data->defaultStock =  $stocks - $stockOut;

                //like product
                $categories = DB::table('liked_products')->where('liked_products_id', '=', $products_id)->get();

                if(count($categories)>0){
                    $products_data->isLiked = count($categories);
                }else{
                    $products_data->isLiked = 0;
                }
                array_push($result,$products_data);
            }
            $responseData = array('success'=>'1', 'product_data'=>$result,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>count($total_record));

        }else{
            $responseData = array('success'=>'0', 'product_data'=>$result,  'message'=>Lang::get('website.Empty record'), 'total_record'=>count($total_record));
        }

        return response()->json(['success'=>true,'product'=>$responseData], $this->successStatus);
    }

//    public function newest_product()
//    {
//        session()->put('language_id', 1);
//        $data = array('page_number' => '0', 'type' => '', 'limit' => 12, 'min_price' => '', 'max_price' => '');
//        $newest_products = $this->products->products($data);
//
//        return response()->json(['success'=>true,'product'=>$newest_products], $this->successStatus);
//    }

    public function newest_product()
    {
        $sortby	     = "products_id";
        $order	     = "DESC";
        $currentDate = time();

        $categories = DB::table('products')
            ->leftJoin('products_description','products_description.products_id','=','products.products_id')
            ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id');


        $categories->LeftJoin('specials', function ($join) use ($currentDate) {
            $join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
        })->select('products.products_id','products.products_quantity','products.products_image','products.products_price','products.products_weight','products.products_weight_unit','products.products_status','products.is_current','products.products_ordered','products.products_liked','products.low_limit','products.is_feature','products.products_slug','products.products_type','products.products_min_order','image_categories.path as image_path','products_description.products_name','products_description.products_description');


        $categories->where('products_description.language_id','=',1)->where('products_status','=',1);
        $categories->orderBy($sortby, $order)->groupBy('products.products_id');

        //count
        $total_record = $categories->get();
        $products  = $categories->get();

        $result = array();

        //check if record exist
        if(count($products)>0){

            $index = 0;
            foreach ($products as $products_data){
                $reviews = DB::table('reviews')
                    ->leftjoin('users', 'users.id', '=', 'reviews.customers_id')
                    ->leftjoin('reviews_description', 'reviews.reviews_id', '=', 'reviews_description.review_id')
                    ->select('reviews.*','reviews_description.reviews_text')
                    ->where('products_id', $products_data->products_id)
                    ->where('reviews_status', '1')
                    ->where('reviews_read', '1')
                    ->get();

                if (count($reviews) > 0) {
                    $five_star = 0;
                    $five_count = 0;

                    $four_star = 0;
                    $four_count = 0;

                    $three_star = 0;
                    $three_count = 0;

                    $two_star = 0;
                    $two_count = 0;

                    $one_star = 0;
                    $one_count = 0;

                    foreach ($reviews as $review) {

                        //five star ratting
                        if ($review->reviews_rating == '5') {
                            $five_star += $review->reviews_rating;
                            $five_count++;
                        }

                        //four star ratting
                        if ($review->reviews_rating == '4') {
                            $four_star += $review->reviews_rating;
                            $four_count++;
                        }
                        //three star ratting
                        if ($review->reviews_rating == '3') {
                            $three_star += $review->reviews_rating;
                            $three_count++;
                        }
                        //two star ratting
                        if ($review->reviews_rating == '2') {
                            $two_star += $review->reviews_rating;
                            $two_count++;
                        }

                        //one star ratting
                        if ($review->reviews_rating == '1') {
                            $one_star += $review->reviews_rating;
                            $one_count++;
                        }
                    }

                    $five_ratio = round($five_count / count($reviews) * 100);
                    $four_ratio = round($four_count / count($reviews) * 100);
                    $three_ratio = round($three_count / count($reviews) * 100);
                    $two_ratio = round($two_count / count($reviews) * 100);
                    $one_ratio = round($one_count / count($reviews) * 100);

                    $avarage_rate = (5 * $five_star + 4 * $four_star + 3 * $three_star + 2 * $two_star + 1 * $one_star) / ($five_star + $four_star + $three_star + $two_star + $one_star);
                    $total_user_rated = count($reviews);
                    $reviewed_customers = $reviews;
                } else {
                    $reviewed_customers = array();
                    $avarage_rate = 0;
                    $total_user_rated = 0;

                    $five_ratio = 0;
                    $four_ratio = 0;
                    $three_ratio = 0;
                    $two_ratio = 0;
                    $one_ratio = 0;
                }

                $products_data->rating = number_format($avarage_rate, 2);
                $products_data->total_user_rated = $total_user_rated;

                $products_data->five_ratio = $five_ratio;
                $products_data->four_ratio = $four_ratio;
                $products_data->three_ratio = $three_ratio;
                $products_data->two_ratio = $two_ratio;
                $products_data->one_ratio = $one_ratio;

                //review by users
                //$products_data->reviewed_customers = $reviewed_customers;
                $products_id = $products_data->products_id;

                //multiple images
//                $products_images = DB::table('products_images')
//                    ->LeftJoin('image_categories','products_images.image','=','image_categories.image_id')
//                    ->select('image_categories.path as image_path','image_categories.image_type')
//                    ->where('products_id','=', $products_id)
//                    ->orderBy('sort_order', 'ASC')
//                    ->get();
//                $products_data->images =  $products_images;

                $default_image_thumb = DB::table('products')
                    ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id')
                    ->select('image_categories.path as image_path','image_categories.image_type')
                    ->where('products_id','=', $products_id)
                    ->where('image_type','=', 'THUMBNAIL')
                    ->first();

                $products_data->default_thumb =  $default_image_thumb;

                //categories
                $categories = DB::table('products_to_categories')
                    ->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
                    ->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
                    ->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
                    ->where('products_id','=', $products_id)
                    ->where('categories_description.language_id','=', 1)->get();

                $products_data->categories =  $categories;

                $stocks = 0;
                $stockOut = 0;
                if($products_data->products_type == '0'){
                    $stocks = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','in')->sum('stock');
                    $stockOut = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','out')->sum('stock');
                }

                $products_data->defaultStock =  $stocks - $stockOut;

                //like product
                $categories = DB::table('liked_products')->where('liked_products_id', '=', $products_id)->get();

                if(count($categories)>0){
                    $products_data->isLiked = count($categories);
                }else{
                    $products_data->isLiked = 0;
                }
                array_push($result,$products_data);
            }
            $responseData = array('success'=>'1', 'product_data'=>$result,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>count($total_record));

        }else{
            $responseData = array('success'=>'0', 'product_data'=>$result,  'message'=>Lang::get('website.Empty record'), 'total_record'=>count($total_record));
        }

        return response()->json(['success'=>true,'product'=>$responseData], $this->successStatus);
    }

    public function all_product()
    {
        $sortby	     = "products_id";
        $order	     = "ASC";
        $currentDate = time();

        $categories = DB::table('products')
            ->leftJoin('products_description','products_description.products_id','=','products.products_id')
            ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id');


        $categories->LeftJoin('specials', function ($join) use ($currentDate) {
            $join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
        })->select('products.products_id','products.products_quantity','products.products_image','products.products_price','products.products_weight','products.products_weight_unit','products.products_status','products.is_current','products.products_ordered','products.products_liked','products.low_limit','products.is_feature','products.products_slug','products.products_type','products.products_min_order','image_categories.path as image_path','products_description.products_name','products_description.products_description');


        $categories->where('products_description.language_id','=',1)->where('products_status','=',1);
        $categories->orderBy($sortby, $order)->groupBy('products.products_id');

        //count
        $total_record = $categories->get();
        $products  = $categories->get();

        $result = array();

        //check if record exist
        if(count($products)>0){

            $index = 0;
            foreach ($products as $products_data){
                $reviews = DB::table('reviews')
                    ->leftjoin('users', 'users.id', '=', 'reviews.customers_id')
                    ->leftjoin('reviews_description', 'reviews.reviews_id', '=', 'reviews_description.review_id')
                    ->select('reviews.*','reviews_description.reviews_text')
                    ->where('products_id', $products_data->products_id)
                    ->where('reviews_status', '1')
                    ->where('reviews_read', '1')
                    ->get();

                if (count($reviews) > 0) {
                    $five_star = 0;
                    $five_count = 0;

                    $four_star = 0;
                    $four_count = 0;

                    $three_star = 0;
                    $three_count = 0;

                    $two_star = 0;
                    $two_count = 0;

                    $one_star = 0;
                    $one_count = 0;

                    foreach ($reviews as $review) {

                        //five star ratting
                        if ($review->reviews_rating == '5') {
                            $five_star += $review->reviews_rating;
                            $five_count++;
                        }

                        //four star ratting
                        if ($review->reviews_rating == '4') {
                            $four_star += $review->reviews_rating;
                            $four_count++;
                        }
                        //three star ratting
                        if ($review->reviews_rating == '3') {
                            $three_star += $review->reviews_rating;
                            $three_count++;
                        }
                        //two star ratting
                        if ($review->reviews_rating == '2') {
                            $two_star += $review->reviews_rating;
                            $two_count++;
                        }

                        //one star ratting
                        if ($review->reviews_rating == '1') {
                            $one_star += $review->reviews_rating;
                            $one_count++;
                        }
                    }

                    $five_ratio = round($five_count / count($reviews) * 100);
                    $four_ratio = round($four_count / count($reviews) * 100);
                    $three_ratio = round($three_count / count($reviews) * 100);
                    $two_ratio = round($two_count / count($reviews) * 100);
                    $one_ratio = round($one_count / count($reviews) * 100);

                    $avarage_rate = (5 * $five_star + 4 * $four_star + 3 * $three_star + 2 * $two_star + 1 * $one_star) / ($five_star + $four_star + $three_star + $two_star + $one_star);
                    $total_user_rated = count($reviews);
                    $reviewed_customers = $reviews;
                } else {
                    $reviewed_customers = array();
                    $avarage_rate = 0;
                    $total_user_rated = 0;

                    $five_ratio = 0;
                    $four_ratio = 0;
                    $three_ratio = 0;
                    $two_ratio = 0;
                    $one_ratio = 0;
                }

                $products_data->rating = number_format($avarage_rate, 2);
                $products_data->total_user_rated = $total_user_rated;

                $products_data->five_ratio = $five_ratio;
                $products_data->four_ratio = $four_ratio;
                $products_data->three_ratio = $three_ratio;
                $products_data->two_ratio = $two_ratio;
                $products_data->one_ratio = $one_ratio;

                //review by users
                //$products_data->reviewed_customers = $reviewed_customers;
                $products_id = $products_data->products_id;

                //multiple images
//                $products_images = DB::table('products_images')
//                    ->LeftJoin('image_categories','products_images.image','=','image_categories.image_id')
//                    ->select('image_categories.path as image_path','image_categories.image_type')
//                    ->where('products_id','=', $products_id)
//                    ->orderBy('sort_order', 'ASC')
//                    ->get();
//                $products_data->images =  $products_images;

                $default_image_thumb = DB::table('products')
                    ->LeftJoin('image_categories','products.products_image','=','image_categories.image_id')
                    ->select('image_categories.path as image_path','image_categories.image_type')
                    ->where('products_id','=', $products_id)
                    ->where('image_type','=', 'THUMBNAIL')
                    ->first();

                $products_data->default_thumb =  $default_image_thumb;

                //categories
                $categories = DB::table('products_to_categories')
                    ->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
                    ->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
                    ->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
                    ->where('products_id','=', $products_id)
                    ->where('categories_description.language_id','=', 1)->get();

                $products_data->categories =  $categories;

                $stocks = 0;
                $stockOut = 0;
                if($products_data->products_type == '0'){
                    $stocks = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','in')->sum('stock');
                    $stockOut = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','out')->sum('stock');
                }

                $products_data->defaultStock =  $stocks - $stockOut;

                //like product
                $categories = DB::table('liked_products')->where('liked_products_id', '=', $products_id)->get();

                if(count($categories)>0){
                    $products_data->isLiked = count($categories);
                }else{
                    $products_data->isLiked = 0;
                }
                array_push($result,$products_data);
            }
            $responseData = array('success'=>'1', 'product_data'=>$result,  'message'=>Lang::get('website.Returned all products'), 'total_record'=>count($total_record));

        }else{
            $responseData = array('success'=>'0', 'product_data'=>$result,  'message'=>Lang::get('website.Empty record'), 'total_record'=>count($total_record));
        }

        return response()->json(['success'=>true,'product'=>$responseData], $this->successStatus);
    }

    public function banners(){
        $banners = DB::table('sliders_images')
            ->leftJoin('languages','languages.languages_id','=','sliders_images.languages_id')
            ->leftJoin('image_categories','sliders_images.sliders_image','=','image_categories.image_id')
            ->select('sliders_images.*','image_categories.path')
            ->orderBy('sliders_images.sliders_id','ASC')
            ->groupBy('sliders_images.sliders_id')
            ->paginate(20);

        if($banners){
            return response()->json(['success'=>true,'response'=>$banners], $this->successStatus);
        }else{
            return response()->json(['success'=>true,'response'=>'No Banner Found.'], $this->failStatus);
        }
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

        if($products){
            return response()->json(['success'=>true,'response'=>$products], $this->successStatus);
        }else{
            return response()->json(['success'=>true,'response'=>'No Products Found.'], $this->failStatus);
        }
    }

}
