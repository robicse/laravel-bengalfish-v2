<link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed&display=swap" rel="stylesheet">
<style>
  .products-area .nav-pills{
    font-family: Roboto Condensed;
  }
  .products-area .nav-pills .active{
    background-color: #f89e20;
  }
</style>
@if($result['flash_sale']['success']==1)
<section class="products-content">
  <div class="container">
    @if($result['flash_sale']['success']==1)
    <div class="products-area">
      <!-- ..........tabs start ......... -->
      <div class="row">
        <div class="col-md-12">
          <div class="nav nav-pills" role="tablist">
            @if($result['top_seller']['success']==1)
            <a class="nav-link nav-item nav-index active show" href="#featured" id="featured-tab" data-toggle="pill" role="tab">@lang('website.TopSales')</a>
            @endif
            @if($result['special']['success']==1)
            <a class="nav-link nav-item nav-index" href="#special" id="special-tab" data-toggle="pill" role="tab" >@lang('website.Special')</a>
            @endif
            @if($result['most_liked']['success']==1)
            <a class="nav-link nav-item nav-index" href="#liked" id="liked-tab" data-toggle="pill" role="tab" >@lang('website.MostLiked')</a>
            @endif
          </div>
          <!-- Tab panes -->
          <div class="tab-content">
            @if($result['top_seller']['success']==1)
            <div role="tabpanel" class="tab-pane fade active show" id="featured" aria-labelledby="featured-tab">
              <div id="owl-tab" class="owl-tab owl-carousel">
                @foreach($result['top_seller']['product_data'] as $key=>$products)
                {{--@include('web.common.product')--}}
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
                      <a href="{{url('/shop')}}">
                        <i class="fas fa-angle-right icon"></i>
                      </a>
                      <a href="{{url('/shop')}}" class="btn btn-block btn-link">@lang('website.View')</a>
                    </div>
                  </article>
                </div>

              </div>
              <!-- 1st tab -->
            </div>
            @endif
            @if($result['special']['success']==1)
            <div role="tabpanel" class="tab-pane fade" id="special" aria-labelledby="special-tab">
                <div id="owl-tab" class="owl-tab owl-carousel">
                  @foreach($result['special']['product_data'] as $key=>$products)
                  @include('web.common.product')
                  @endforeach

                    <div class="product last-product">
                      <article>
                        <div class="icons">
                            <a href="{{url('/shop')}}">
                                <i class="fas fa-angle-right icon"></i>
                              </a>
                              <a href="{{url('/shop')}}" class="btn btn-block btn-link">@lang('website.View')</a>
                        </div>
                      </article>
                    </div>

                  </div>
              <!-- 2nd tab -->
            </div>
            @endif
            @if($result['most_liked']['success']==1)
            <div role="tabpanel" class="tab-pane fade" id="liked" aria-labelledby="liked-tab">
                <div id="owl-tab" class="owl-tab owl-carousel">
                    @foreach($result['most_liked']['product_data'] as $key=>$products)
                    {{--@include('web.common.product')--}}
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
                            <a href="{{url('/shop')}}">
                                <i class="fas fa-angle-right icon"></i>
                              </a>
                              <a href="{{url('/shop')}}" class="btn btn-block btn-link">@lang('website.View')</a>
                        </div>
                      </article>
                    </div>

                  </div>
              <!-- 3rd tab -->
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
@endif
