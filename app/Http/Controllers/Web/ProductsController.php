<?php
namespace App\Http\Controllers\Web;
//validator is builtin class in laravel
use Validator;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

//for requesting a value
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
//for Carbon a value
use Carbon;
use Session;
use Lang;
use App\Models\Web\Index;
use App\Models\Web\Languages;
use App\Models\Web\Products;
use App\Models\Web\Currency;
//email
use Illuminate\Support\Facades\Mail;

class ProductsController extends Controller
{
	public function __construct(
											Index $index,
											Languages $languages,
											Products $products,
											Currency $currency
											)
	{
		$this->index = $index;
		$this->languages = $languages;
		$this->products = $products;
		$this->currencies = $currency;
		$this->theme = new ThemeController();
	}

	public function reviews(Request $request){
		if(Auth::guard('customer')->check()){
			$check = DB::table('reviews')
			           ->where('customers_id',Auth::guard('customer')->user()->id)
								 ->where('products_id',$request->products_id)
								 ->first();

			if($check){
				return 'already_commented';
			}
			$id = DB::table('reviews')->insertGetId([
				'products_id' => $request->products_id,
				'reviews_rating' => $request->rating,
				'customers_id' => Auth::guard('customer')->user()->id,
				'customers_name' => Auth::guard('customer')->user()->first_name,
				'created_at' =>  time()
			]);

			DB::table('reviews_description')
			  ->insert([
					'review_id' => $id,
					'language_id' => Session::get('language_id'),
					'reviews_text' => $request->comments
 				]);
			return 'done';
		}else {
      return 'not_login';

		}
	}

	public function check_search(Request $request){
        $category = $request->category;
        $search = $request->search;

        if(empty($category) && empty($search)){
            return redirect()->back();
        }
        $url = "";
	    if($category){
            $url = "/category/".$category;
            $sign = '/';
        }else{
            $sign = '/';
        }
        if($search){
            $url.= $sign."search/".$search;
        }

        return redirect('shop'.$url);
    }

	//shop
	public function shop(Request $request){
		$title = array('pageTitle' => Lang::get('website.Shop'));
		$result = array();

		$result['commonContent'] = $this->index->commonContent();
		$final_theme = $this->theme->theme();
		if(!empty($request->page)){
			$page_number = $request->page;
		}else{
			$page_number = 0;
		}

		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}

		if(!empty($request->type)){
			$type = $request->type;
		}else{
			$type = '';
		}

		//min_max_price
		if(!empty($request->price)){
			$d = explode(";",$request->price);
			$min_price = $d[0];
			$max_price = $d[1];
		}else{
			$min_price = '';
			$max_price = '';
		}
		//category
		if(!empty($request->category) and $request->category!='all'){
            $category = $this->products->getCategories($request);

			$categories_id = $category[0]->categories_id;
			//for main
			if($category[0]->parent_id==0){
				$category_name = $category[0]->categories_name;
				$meta_title = $category[0]->meta_title;
				$sub_category_name = '';
				$category_slug = '';
                $cat_slogan = $category[0]->cat_slogan;
                $cat_description = $category[0]->cat_description;
			}else{
			//for sub
				$main_category = $this->products->getMainCategories($category[0]->parent_id);

				$category_slug = $main_category[0]->categories_slug;
				$category_name = $main_category[0]->categories_name;
				$meta_title = $main_category[0]->meta_title;
				$sub_category_name = $category[0]->categories_name;
				$cat_slogan = $category[0]->cat_slogan;
				$cat_description = $category[0]->cat_description;
			}

		}else{
            $categories_id = '';
			$category_name = '';
            $meta_title = '';
			$sub_category_name = '';
			$category_slug = '';
            $cat_slogan = '';
            $cat_description = '';
		}

		$result['category_name'] = $category_name;
		$result['meta_title'] = $meta_title;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;
		$result['cat_slogan'] = $cat_slogan;
		$result['cat_description'] = $cat_description;

		//search value
		if(!empty($request->search)){
			$search = $request->search;
		}else{
			$search = '';
		}


		$filters = array();
		if(!empty($request->filters_applied) and $request->filters_applied==1){
			$index = 0;
			$options = array();
			$option_values = array();

			$option = $this->products->getOptions();

			foreach($option as $key=>$options_data){
				$option_name = str_replace(' ','_',$options_data->products_options_name);

				if(!empty($request->$option_name)){
					$index2 = 0;
					$values = array();
					foreach($request->$option_name as $value)
					{
						$value = $this->products->getOptionsValues($value);
						$option_values[]=$value[0]->products_options_values_id;
					}
					$options[] = $options_data->products_options_id;
				}
			}


			$filters['options_count'] = count($options);

			$filters['options'] = implode($options,',');
			$filters['option_value'] = implode($option_values, ',');

                        $filters['filter_attribute']['options'] = $options;
			$filters['filter_attribute']['option_values'] = $option_values;

                        $result['filter_attribute']['options'] = $options;
			$result['filter_attribute']['option_values'] = $option_values;
		}

		$data = array('page_number'=>$page_number, 'type'=>$type, 'limit'=>$limit,
		 'categories_id'=>$categories_id, 'search'=>$search,
		 'filters'=>$filters, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );

		$products = $this->products->products($data);
		$result['products'] = $products;

		$data = array('limit'=>$limit, 'categories_id'=>$categories_id );
		$filters = $this->filters($data);
		$result['filters'] = $filters;

		$cart = '';
		$result['cartArray'] = $this->products->cartIdArray($cart);

		if($limit > $result['products']['total_record']){
			$result['limit'] = $result['products']['total_record'];
		}else{
			$result['limit'] = $limit;
		}

		//liked products
		$result['liked_products'] = $this->products->likedProducts();
		$result['categories'] = $this->products->categories();

		$result['min_price'] = $min_price;
		$result['max_price'] = $max_price;

		return view("web.shop", ['title' => $title,'final_theme' => $final_theme])->with('result', $result);

	}

	public function filterProducts(Request $request){

		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}

		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}

		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}

		if(!empty($request->type)){
			$type = $request->type;
		}else{
			$type = '';
		}

		//if(!empty($request->category_id)){
		if(!empty($request->category) and $request->category!='all'){
			$category = DB::table('categories')->leftJoin('categories_description','categories_description.categories_id','=','categories.categories_id')->where('categories_slug',$request->category)->where('language_id',Session::get('language_id'))->get();

			$categories_id = $category[0]->categories_id;
		}else{
			$categories_id = '';
		}

		//search value
		if(!empty($request->search)){
			$search = $request->search;
		}else{
			$search = '';
		}

		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}

		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}

		if(!empty($request->filters_applied) and $request->filters_applied==1){
			$filters['options_count'] = count($request->options_value);
			$filters['options'] = $request->options;
			$filters['option_value'] = $request->options_value;
		}else{
			$filters = array();
		}


		$data = array('page_number'=>$request->page_number, 'type'=>$type, 'limit'=>$limit, 'categories_id'=>$categories_id, 'search'=>$search, 'filters'=>$filters, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$products = $this->products->products($data);
		$result['products'] = $products;

		$cart = '';
		$result['cartArray'] =  $this->products->cartIdArray($cart);
		$result['limit'] = $limit;
		return view("web.filterproducts")->with('result', $result);

	}

	public function ModalShow(Request $request){
		$result 		= 	array();
		$result['commonContent'] = $this->index->commonContent();
		$final_theme = $this->theme->theme();
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}

		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}

		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}

		$products = $this->products->getProductsById($request->products_id);

		//category
		$category = $this->products->getCategoryByParent($products[0]->products_id);


		if(!empty($category)){
			$category_slug = $category[0]->categories_slug;
			$category_name = $category[0]->categories_name;
		}else{
			$category_slug = '';
			$category_name = '';
		}
		$sub_category = $this->products->getSubCategoryByParent($products[0]->products_id);

		if(!empty($sub_category) and count($sub_category)>0){
			$sub_category_name = $sub_category[0]->categories_name;
			$sub_category_slug = $sub_category[0]->categories_slug;
		}else{
			$sub_category_name = '';
			$sub_category_slug = '';
		}

		$result['category_name'] = $category_name;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;
		$result['sub_category_slug'] = $sub_category_slug;

		$isFlash = $this->products->getFlashSale($products[0]->products_id);


		if(!empty($isFlash) and count($isFlash)>0){
			$type = "flashsale";
		}else{
			$type = "";
		}

		$data = array('page_number'=>'0', 'type'=>$type, 'products_id'=>$products[0]->products_id, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
		$detail = $this->products->products($data);
		$result['detail'] = $detail;

		$i = 0;
		foreach($result['detail']['product_data'][0]->categories as $postCategory){
			if($i==0){
				$postCategoryId = $postCategory->categories_id;
				$i++;
			}
		}

		$data = array('page_number'=>'0', 'type'=>'', 'categories_id'=>$postCategoryId, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
		$simliar_products = $this->products->products($data);
		$result['simliar_products'] = $simliar_products;

		$cart = '';
		$result['cartArray'] = $this->products->cartIdArray($cart);

		//liked products
		$result['liked_products'] = $this->products->likedProducts();
		return view("web.common.modal1")->with('result', $result);
	}

	//access object for custom pagination
	function accessObjectArray($var){
	  return $var;
	}

	//productDetail
	public function productDetail(Request $request){

		$title 			= 	array('pageTitle' => Lang::get('website.Product Detail'));
		$result 		= 	array();
		$result['commonContent'] = $this->index->commonContent();
		$final_theme = $this->theme->theme();
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}

		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}

		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}

		$products = $this->products->getProductsBySlug($request->slug);

		//category
		$category = $this->products->getCategoryByParent($products[0]->products_id);
/*
//die("$category[0]->categories_slug:: $category[0]->categories_name");

echo"<script type='text/javascript'>
alert(' ::OK');
</script>";
if(isset($category[0]->categories_slug)){
    echo"<script type='text/javascript'>
alert(' ::OK');
</script>";
}
else{
    echo"<script type='text/javascript'>
alert(' Not::');
</script>";
}
*/
		if(!empty($category)){
			$category_slug = $category[0]->categories_slug;
			$category_name = $category[0]->categories_name;
		}else{
			$category_slug = '';
			$category_name = '';
		}

		
///			$category_slug = '';
//			$category_name = '';
		$sub_category = $this->products->getSubCategoryByParent($products[0]->products_id);

		if(!empty($sub_category) and count($sub_category)>0){
			$sub_category_name = $sub_category[0]->categories_name;
			$sub_category_slug = $sub_category[0]->categories_slug;
		}else{
			$sub_category_name = '';
			$sub_category_slug = '';
		}

		$result['category_name'] = $category_name;
		$result['category_slug'] = $category_slug;
		$result['sub_category_name'] = $sub_category_name;
		$result['sub_category_slug'] = $sub_category_slug;

		$isFlash = $this->products->getFlashSale($products[0]->products_id);


		if(!empty($isFlash) and count($isFlash)>0){
			$type = "flashsale";
		}else{
			$type = "";
		}

		$data = array('page_number'=>'0', 'type'=>$type, 'products_id'=>$products[0]->products_id, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
		$detail = $this->products->products($data);
		$result['detail'] = $detail;

		$i = 0;
		foreach($result['detail']['product_data'][0]->categories as $postCategory){
			if($i==0){
				$postCategoryId = $postCategory->categories_id;
				$i++;
			}
		}

		$data = array('page_number'=>'0', 'type'=>'', 'categories_id'=>$postCategoryId, 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price);
		$simliar_products = $this->products->products($data);
		$result['simliar_products'] = $simliar_products;

		$cart = '';
		$result['cartArray'] = $this->products->cartIdArray($cart);

		//liked products
		$result['liked_products'] = $this->products->likedProducts();

		return view("web.detail", ['title' => $title, 'final_theme' => $final_theme])->with('result', $result);
	}

	//filters
	public function filters($data){
    $response = $this->products->filters($data);
		return($response);
		}

	//getquantity
	public function getquantity(Request $request){
		$data = array();
		$data['products_id'] = $request->products_id;
		$data['attributes'] = $request->attributeid;

		$result = $this->products->productQuantity($data);
		print_r(json_encode($result));
	}


}
