<section class=" cart-content">
      <div class="container">
    <div class="row">
      <div class="col-12 col-sm-12">
          <div class="row ">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('website.Shopping cart')</li>
                  </ol>
                </nav>
          </div>
      </div>

      <div class="col-12 col-sm-12 cart-area cart-page-one">
        @if(session()->has('message'))
           <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           </div>
       @endif
       @if(session::get('out_of_stock') == 1)
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
               This Product is out of stock.
               <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
      @endif
        <div class="row">

          <div class="col-12 col-lg-9">
            <form method='POST' id="update_cart_form" action='{{ URL::to('/updateCart')}}' >
            <table class="table top-table">
              <thead>
                <tr class="d-flex">
                  <th class="col-12 col-md-2">@lang('website.items')</th>
                  <th class="col-12 col-md-4"></th>
                  <th class="col-12 col-md-2">@lang('website.Price')</th>
                  <th class="col-12 col-md-2">@lang('website.Qty')</th>
                  <th class="col-12 col-md-2">@lang('website.SubTotal')</th>
                </tr>
              </thead>
              <?php
                $price = 0;
               ?>
               @php
               $default_currency = DB::table('currencies')->where('is_default',1)->first();

               if($default_currency->id == Session::get('currency_id')){

                $currency_value = 1;
               }else{
                $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();

                $currency_value = $session_currency->value;
               }
               @endphp
              @foreach( $result['cart'] as $products)
              <?php
              $price+= $products->final_price * $products->customers_basket_quantity;
              ?>
              <tbody  >

                  <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                  <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                <tr class="d-flex">
                  <td class="col-12 col-md-2" >

                    <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="cart-thumb">
                    <img class="img-fluid" src="{{asset('').$products->image_path}}" alt="{{$products->products_name}}"/>
                    </a>
                   </td>
                  <td class="col-12 col-md-4 item-detail-left">
                    <div class="item-detail">
                        <h4>{{$products->products_name}}
                          <br>
                        </h4>
                        <div class="item-attributes">
                          @if(isset($products->attributes))
                          @foreach($products->attributes as $attributes)
                            <small>{{$attributes->attribute_name}} : {{$attributes->attribute_value}}</small>
                          @endforeach
                          @endif
                        </div>

                      </div>
                   </td>
                   <?php
                      $default_currency = DB::table('currencies')->where('is_default',1)->first();
                      if($default_currency->id == Session::get('currency_id')){
                        if(!empty($products->discount_price)){
                        $discount_price = $products->discount_price;
                        }
                        if(!empty($products->final_price)){
                          $flash_price = $products->final_price;
                        }
                        $orignal_price = $products->price;
                      }else{
                        $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();
                        if(!empty($products->discount_price)){
                          $discount_price = $products->discount_price * $session_currency->value;
                        }
                        if(!empty($products->final_price)){
                          $flash_price = $products->final_price * $session_currency->value;
                        }
                        $orignal_price = $products->price * $session_currency->value;
                      }
                       if(!empty($products->discount_price)){

                        if(($orignal_price+0)>0){
                       $discounted_price = $orignal_price-$discount_price;
                       $discount_percentage = $discounted_price/$orignal_price*100;
                       }else{
                         $discount_percentage = 0;
                         $discounted_price = 0;
                     }
                   }
                   ?>
                  <td class="item-price col-12 col-md-2">
                    @if(!empty($products->final_price))
                    {{Session::get('symbol_left')}}{{$flash_price+0}}{{Session::get('symbol_right')}}
                    @elseif(!empty($products->discount_price))
                    {{Session::get('symbol_left')}}{{$discount_price+0}}{{Session::get('symbol_right')}}
                    <span> {{Session::get('symbol_left')}}{{$orignal_price+0}}{{Session::get('symbol_right')}}</span>
                    @else
                    {{Session::get('symbol_left')}}{{$orignal_price+0}}{{Session::get('symbol_right')}}
                    @endif

                   </td>

                  <td class="col-12 col-md-2 Qty">
                        <div class="input-group">
{{--                          <span class="input-group-btn qtyminuscart">--}}
{{--                              <button class="btn btn-defualt" type="button"><i class="fa fa-minus" aria-hidden="true"></i></button>--}}
{{--                            </span>--}}
{{--                            <input name="quantity[]" type="number" readonly value="{{$products->customers_basket_quantity}}" class="form-control qty" min="{{$products->min_order}}" max="{{$products->max_order}}">--}}
{{--                            <span class="input-group-btn qtypluscart">--}}
{{--                              <button class="btn btn-defualt" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>--}}
{{--                            </span>--}}
                            <input type="number" value="{{$products->customers_basket_quantity}}" name="quantity[]" class="form-control qty" id="quantity" min="{{$products->min_order}}" max="{{$products->max_order}}">
                        </div>
                   </td>

                  <td class="align-middle item-total col-12 col-md-2" align="center">
                    <span class="cart_price_{{$products->customers_basket_id}}">
                    {{Session::get('symbol_left')}}{{$products->final_price * $products->customers_basket_quantity * $currency_value}}{{Session::get('symbol_right')}}
                    </span>
                   </td>
                   <td class="align-middle item-total col-12 col-md-2" align="center"></td>
                </tr>
                <tr class="d-flex">
                    <td class="col-12 col-md-2 p-0">
                      <div class="item-controls">
                          <a href="{{ url('/editcart/'.$products->customers_basket_id.'/'.$products->products_slug)}}"  class="btn" >
                              <span class="fas fa-pencil-alt"></span>
                          </a>
                          <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}"  class="btn" >
                              <span class="fas fa-times"></span>
                          </a>
                      </div>
                    </td>
                    <td class="col-12 col-md-10 d-none d-md-block"></td>
                </tr>
              </tbody>
              @endforeach
            </table>
          </form>
            @if(!empty(session('coupon')))
              <div class="form-group">
                    @foreach(session('coupon') as $coupons_show)

                        <div class="alert alert-success">
                            <a href="{{ URL::to('/removeCoupon/'.$coupons_show->coupans_id)}}" class="close"><span aria-hidden="true">&times;</span></a>
                          @lang('website.Coupon Applied') {{$coupons_show->code}}.@lang('website.If you do note want to apply this coupon just click cross button of this alert.')
                        </div>

                    @endforeach
                </div>
            @endif
            <div class="col-12 col-lg-12 mb-4">
              <div class="row justify-content-between click-btn">
                <div class="col-12 col-lg-4">
                  <form id="apply_coupon" class="form-validate">
                    <div class="row">
                        <div class="input-group">
                            <input type="text" name="coupon_code" class="form-control" id="coupon_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="coupon-code">

                            <div class="">
                              <button class="btn btn-secondary" type="submit" id="coupon-code">APPLY</button>
                            </div>
                          </div>
                    </div>
                 </form>
                </div>
                <div class="col-12 col-lg-7 align-right">
                  <a  href="{{ URL::to('/shop')}}" class="btn btn-outline-primary">@lang('website.Back To Shopping')</a>
                  <button class="btn btn-dark" id="update_cart">@lang('website.Update Cart')</button>

                </div>
                <div id="coupon_error" class="help-block" style="display: none;color:red;"></div>
               <div  id="coupon_require_error" class="help-block" style="display: none;color:red;">@lang('website.Please enter a valid coupon code')</div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-3">
            <table class="table right-table">
              <thead>
                <tr>
                  <th scope="col" colspan="2" align="center">@lang('website.Order Summary')</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">@lang('website.SubTotal')</th>
                  <td align="right">
{{--                    {{Session::get('symbol_left')}}{{$currency_value * $price+0-number_format((float)session('coupon_discount'), 2, '.', '')}}{{Session::get('symbol_right')}}--}}
                    {{Session::get('symbol_left')}}{{$currency_value * $price}}{{Session::get('symbol_right')}}
                  </td>
                </tr>
                <tr>
                  <th scope="row">@lang('website.Discount(Coupon)')</th>
                  <td align="right">{{Session::get('symbol_left')}}{{$currency_value * number_format((float)session('coupon_discount'), 2, '.', '')+0}}{{Session::get('symbol_right')}}</td>
                </tr>
                <tr class="item-price">
                  <th scope="row">@lang('website.Total')</th>
                  <td align="right" >{{Session::get('symbol_left')}}{{$currency_value * $price+0-number_format((float)session('coupon_discount'), 2, '.', '')}}{{Session::get('symbol_right')}}</td>
                </tr>
              </tbody>
            </table>
            <a href="{{ URL::to('/checkout')}}" class="btn btn-secondary m-btn col-12">@lang('website.proceedToCheckout')</a>
          </div>
        </div>
      </div>
    </div>

    </div>
</section>
