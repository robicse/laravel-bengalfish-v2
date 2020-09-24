@extends('web.layout')
@section('content')
<!-- End Header Content -->

<!-- NOTIFICATION CONTENT -->
 @include('web.common.notifications')
<!-- END NOTIFICATION CONTENT -->

<!-- Carousel Content -->

<?php  echo $final_theme['carousel']; ?>
<!-- Fixed Carousel Content -->

<!-- Banners Content -->
<!-- Products content -->



















<?php
/*echo '<pre>';
print_r($result['categoryLists']);
echo '</pre>';*/


if($result['categoryLists']['status'] == 1){
foreach($result['categoryLists']['categoryProducts'] as $key => $categoryProducts){
/*echo '<pre>';
print_r($categoryProducts);
echo '</pre>';*/
/*$r =   'web.product-sections.categories_products';*/

?>
{{--@include($r)--}}
<?php


}
}




//categories_products
//die();
?>

<?php

  $product_section_orders = json_decode($final_theme['product_section_order'], true);
  foreach ($product_section_orders as $product_section_order){
    //   echo '<pre>';
    //   print_r($product_section_order);
    //   echo '</pre>';
      
      if($product_section_order['order'] == 2 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      
      }
  }
?>

@if($result['categoryLists']['status'] == 1)
    <section class="products-content">
        <div class="container">
            
            @foreach($result['categoryLists']['categoryProducts'] as $key=>$categoryLists)
            
            @if($categoryLists['success']==1)
           
                <div class="products-area">
                    <div class="row">
                        <div class="col-md-12">
                            @foreach($categoryLists['product_data'] as $key1=>$products)
                                @php
                                    $dynamic_category_name = $products->categories['0']->categories_name;
                                
                                    $categories_id = $products->categories['0']->categories_id;
                                    $dynamic_categories = DB::table('categories')->where('categories_id',$categories_id)->get();
                                    $dynamic_categories_slug=$dynamic_categories[0]->categories_slug;
                                @endphp
                            @endforeach
                            
                    
                            {{--<div class="nav nav-pills">{{$dynamic_category_name}}</div>--}}
                            <div class="heading">
                                <h2>{{$dynamic_category_name}}</h2>
                            </div>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active show" id="featured" aria-labelledby="featured-tab">
                                    <div id="owl-tab" class="owl-tab owl-carousel">
                                        @foreach($categoryLists['product_data'] as $key1=>$products)
                                            <div class="product">
                                                <article>
                                                    <div class="thumb">
                                                        <div class="icons mobile-icons d-lg-none d-xl-none">
                                                            <div class="icon-liked">
                                                                <a class="icon active is_liked" products_id="<?=$products->products_id?>">
                                                                    <i class="fas fa-heart"></i>
                                                                    <span  class="badge badge-secondary counter"  >{{$products->products_liked}}</span>
                                                                </a>
                                                            </div>
                                                            <div class="icon"><i class="fas fa-eye"></i></div>
                                                            <a href="{{url('compare')}}" class="icon"><i class="fas fa-align-right" data-fa-transform="rotate-90"></i></a>
                                                        </div>
                                                        <img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->image_alt_tag ? $products->image_alt_tag :$products->products_name}}">
                                                    </div>
                                                    <?php
                                                    $default_currency = DB::table('currencies')->where('is_default',1)->first();
                                                    if($default_currency->id == Session::get('currency_id')){
                                                        if(!empty($products->discount_price)){
                                                            $discount_price = $products->discount_price;
                                                        }
                                                        $orignal_price = $products->products_price;
                                                    }else{
                                                        $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
                                                        if(!empty($products->discount_price)){
                                                            $discount_price = $products->discount_price * $session_currency->value;
                                                        }
                                                        $orignal_price = $products->products_price * $session_currency->value;
                                                    }
                                                    if(!empty($products->discount_price)){

                                                    if(($orignal_price+0)>0){
                                                        $discounted_price = $orignal_price-$discount_price;
                                                        $discount_percentage = $discounted_price/$orignal_price*100;
                                                    }else{
                                                        $discount_percentage = 0;
                                                        $discounted_price = 0;
                                                    }
                                                    ?>
                                                    <span class="discount-tag"><?php echo (int)$discount_percentage; ?>%</span>
                                                    <?php }
                                                    $current_date = date("Y-m-d", strtotime("now"));

                                                    $string = substr($products->products_date_added, 0, strpos($products->products_date_added, ' '));
                                                    $date=date_create($string);
                                                    date_add($date,date_interval_create_from_date_string($web_setting[20]->value." days"));

                                                    //echo $top_seller->products_date_added . "<br>";
                                                    $after_date = date_format($date,"Y-m-d");

                                                    if($after_date>=$current_date){
                                                        print '<span class="discount-tag">';
                                                        print __('website.New');
                                                        print '</span>';
                                                    }
                                                    /*echo '<pre>';
                                                    print_r($products);
                                                    echo '</pre>';*/
                                                    ?>
                                                    <span class="tag">
                                                        @foreach($products->categories as $key=>$category)
                                                            {{$category->categories_name}}@if(++$key === count($products->categories)) @else, @endif
                                                        @endforeach
                                                    </span>
                                                    <h2 class="title text-center"><a href="{{ URL::to('/product-detail/'.$products->products_slug)}}">{{strtoupper($products->products_name)}}</a></h2>
                                                    <span class="tag">
                                                        {{ $products->products_weight ? $products->products_weight : '' }} {{ $products->products_weight_unit ? $products->products_weight_unit : '' }}
                                                    </span>
                                                    <div class="price">
                                                        @if(!empty($products->discount_price))
                                                            {{Session::get('symbol_left')}}&nbsp;{{$discount_price+0}}&nbsp;{{Session::get('symbol_right')}}
                                                            <span> {{Session::get('symbol_left')}}{{$orignal_price+0}}{{Session::get('symbol_right')}}</span>
                                                        @else
                                                            {{Session::get('symbol_left')}}&nbsp;{{$orignal_price+0}}&nbsp;{{Session::get('symbol_right')}}
                                                        @endif
                                                    </div>
                                                    <div class="product-hover d-none d-lg-block d-xl-block">
                                                        <div class="icons">
                                                            <div class="icon-liked">
                                                                <a class="icon active is_liked" products_id="<?=$products->products_id?>">
                                                                    <i class="fas fa-heart"></i>
                                                                    <span  class="badge badge-secondary counter"  >{{$products->products_liked}}</span>
                                                                </a>
                                                            </div>
                                                            <div class="icon modal_show" data-toggle="modal" data-target="#myModal" products_id="{{$products->products_id}}"><i class="fas fa-eye"></i></div>
                                                            <a onclick="myFunction3({{$products->products_id}})"class="icon"><i class="fas fa-align-right" data-fa-transform="rotate-90"></i></a>
                                                        </div>
                                                        @include('web.common.scripts.addToCompare')
                                                        <div class="buttons">
                                                            @if($products->products_type==0)
                                                                @if(!in_array($products->products_id,$result['cartArray']))
                                                                    @if($products->defaultStock==0)

                                                                        <button type="button" class="btn btn-block btn-danger" products_id="{{$products->products_id}}">@lang('website.Out of Stock')</button>
                                                                    @elseif($products->products_min_order>1)
                                                                        <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                                                    @else
                                                                        <button type="button" class="btn btn-block btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                                                    @endif
                                                                @else
                                                                    <button type="button" class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                                                                @endif
                                                            @elseif($products->products_type==1)
                                                                <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                                            @elseif($products->products_type==2)
                                                                <a href="{{$products->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="mobile-buttons d-lg-none d-xl-none">
                                                        @if($products->products_type==0)
                                                            @if(!in_array($products->products_id,$result['cartArray']))
                                                                @if($products->defaultStock==0)
                                                                    <button type="button" class="btn btn-block btn-danger" products_id="{{$products->products_id}}">@lang('website.Out of Stock')</button>
                                                                @elseif($products->products_min_order>1)
                                                                    <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                                                @else
                                                                    <button type="button" class="btn btn-block btn-secondary cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                                                @endif
                                                            @else
                                                                <button type="button" class="btn btn-block btn-secondary active">@lang('website.Added')</button>
                                                            @endif
                                                        @elseif($products->products_type==1)
                                                            <a class="btn btn-block btn-secondary" href="{{ URL::to('/product-detail/'.$products->products_slug)}}">@lang('website.View Detail')</a>
                                                        @elseif($products->products_type==2)
                                                            <a href="{{$products->products_url}}" target="_blank" class="btn btn-block btn-secondary">@lang('website.External Link')</a>
                                                        @endif
                                                    </div>
                                                </article>
                                            </div>

                                        @endforeach
                                        <div class="product last-product">
                                            <article>
                                                <div class="icons">
                                                    <a href="{{url('/shop/category/'.$dynamic_categories_slug)}}">
                                                        <i class="fas fa-angle-right icon"></i>
                                                    </a>
                                                    <a href="{{url('/shop/category/'.$dynamic_categories_slug)}}" class="btn btn-block btn-link">@lang('website.View')</a>
                                                </div>
                                            </article>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 1st tab -->
                            
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>
@endif


















<?php

  $product_section_orders = json_decode($final_theme['product_section_order'], true);
  foreach ($product_section_orders as $product_section_order){
    //   echo '<pre>';
    //   print_r($product_section_order);
    //   echo '</pre>';
      if($product_section_order['order'] == 1 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>

        <?php
      }
      
        ?>
        
        <?php
      
      if($product_section_order['order'] == 3 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      }
      if($product_section_order['order'] == 4 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      }
      if($product_section_order['order'] == 5 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      }
      if($product_section_order['order'] == 6 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      }
      if($product_section_order['order'] == 7 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      }
      if($product_section_order['order'] == 8 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      }
      if($product_section_order['order'] == 9 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      }
      if($product_section_order['order'] == 10 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        {{--@include($r)--}}
        <?php
      }
      if($product_section_order['order'] == 11 && $product_section_order['status'] == 1){
        $r =   'web.product-sections.' . $product_section_order['file_name'];
        ?>
        @include($r)
        <?php
      }
  }
?>
<section class="info-boxes-content">
    <div class="container">

            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    @if(!empty($result['home_content']))
                        {!! $result['home_content'][0]->description; !!}
                    @endif
                </div>
            </div>

    </div>
</section>

@include('web.common.scripts.Like')
@endsection
