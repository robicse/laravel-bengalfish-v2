<!-- //header style One -->
<style>
    nav{margin-top:10px;}

    nav ul{list-style:none;border-radius:5px;padding:0 5px;}

    nav ul:after {content: '.';clear:both;visibility:hidden;display:block;height:0px;}

    nav ul li{float:left;position:relative;line-height:20px;}

    nav ul li a{display:block;color:#000000;text-decoration:none;padding:14px 15px 15px;-webkit-transition:0.25s ease-out;}

    nav ul li:hover > a{color:#3498DB;text-decoration:none;}

    nav ul li.active > a{color:#2980B9;}

    /*-- Sub menu --*/

    nav ul li > ul:before{content:"";border-style:solid;border-width:0 9px 9px 9px;border-color:transparent transparent blue transparent;width:0px;height:0px;position:absolute;left:15px;top:5px;}

    nav ul li > ul{position:absolute;left:14px;top:80%;padding-top:13px;background:none;width:150px;z-index:-9999;opacity:0;-webkit-transition:0.3s ease-out;-moz-transition:0.3s ease-out;transition:0.3s ease-out;}

    nav ul li:hover > ul{display:block;z-index:100;opacity:1;top:95%;}

    nav ul li > ul li:first-child{border-radius:4px 4px 0 0;padding-top:5px;}

    nav ul li > ul li:last-child{border-radius:0 0 4px 4px;}

    nav ul li > ul li{padding:0 3px 3px;background:#ffffff;width:100%;}

    nav ul li > ul li a{display:block;padding:6px 9px;border-radius:2px;font-size:14px;}

    nav ul li > ul li:hover  > a{color:#FFF;background:#3498DB;}

    nav ul li > ul li:active  > a{color:#FFF;background:#2980B9;}

    /*-----sub sub menu -----*/

    nav ul li > ul li > ul:before{content:"";border-style:solid;border-width:0 9px 9px 9px;border-color:transparent transparent blue transparent;width:0px;height:0px;position:absolute;left:0px;top:15px;-webkit-transform:rotate(270deg); -moz-transform:rotate(270deg);-ms-transform:rotate(270deg);-o-transform:rotate(270deg);transform:rotate(270deg);}

    nav ul  li > ul li > ul{top:0;left:90%;padding:0;padding-left:13px;-webkit-transition:0.3s ease-out;-moz-transition:0.3s ease-out;transition:0.3s ease-out;}

    nav ul li > ul li:hover > ul{display:block;z-index:100;opacity:1;top:0;left:100%;}

</style>
<header id="headerOne" class="header-area header-one header-desktop d-none d-lg-block d-xl-block">
    <?php  ?>
    <div class="header-mini bg-top-bar">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12">
            <nav id="navbar_0_2" class="navbar navbar-expand-md navbar-dark navbar-0">
                <?php  ?>
              <div class="navbar-lang">

                @if(count($languages) > 1)
                <div class="dropdown">

                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                     <img src="{{asset('').session('language_image')}}" width="17px" />
                     {{	session('language_name')}}
                    </button>
                    <ul class="dropdown-menu">
                      @foreach($languages as $language)
                      <li  @if(session('locale')==$language->code) style="background:lightgrey;" @endif>
                        <button  onclick="myFunction1({{$language->languages_id}})" class="btn" style="background:none;" href="#">
                          <img style="margin-left:10px; margin-right:10px;"src="{{asset('').$language->image_path}}" width="17px" />
                          <span>{{$language->name}}</span>
                        </button>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                  @include('web.common.scripts.changeLanguage')
                  @endif
                  @if(count($currencies) > 1)
                  <div class="dropdown">

                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

                        @if(session('symbol_left') != null)
                        <span >{{session('symbol_left')}}</span>
                        @else
                        <span >{{session('symbol_right')}}</span>
                        @endif
                       {{session('currency_code')}}


                      </button>
                      <ul class="dropdown-menu">
                        @foreach($currencies as $currency)
                        <li  @if(session('currency_title')==$currency->code) style="background:lightgrey;" @endif>
                          <button  onclick="myFunction2({{$currency->id}})" class="btn" style="background:none;" href="#">
                            @if($currency->symbol_left != null)
                            <span style="margin-left:10px; margin-right:10px;">{{$currency->symbol_left}}</span>
                            <span>{{$currency->code}}</span>
                            @else
                            <span style="margin-left:10px; margin-right:10px;">{{$currency->symbol_right}}</span>
                            <span>{{$currency->code}}</span>
                            @endif
                          </button>
                        </li>
                        @endforeach
                      </ul>
                    </div>
                    @include('web.common.scripts.changeCurrency')
                    @endif
              </div>
                <?php  ?>
              <div class="navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <div class="nav-avatar nav-link">
                          <div class="avatar">
                          <?php
                          if(auth()->guard('customer')->check()){
                           if(auth()->guard('customer')->user()->avatar == null){ ?>
                            <img class="img-fluid" src="{{asset('web/images/miscellaneous/avatar.jpg')}}">
                          <?php }else{ ?>
                            <img class="img-fluid" src="{{auth()->guard('customer')->user()->avatar}}">
                          <?php
                                }
                             }
                          ?>
                          </div>
                          <span><?php if(auth()->guard('customer')->check()){ ?>@lang('website.Welcome')&nbsp;! {{auth()->guard('customer')->user()->first_name}} <?php }?> </span>
                        </div>
                      </li>
                      <?php if(auth()->guard('customer')->check()){ ?>
                      <li class="nav-item"> <a href="{{url('profile')}}" class="nav-link">@lang('website.Profile')</a> </li>
                      <li class="nav-item"> <a href="{{url('wishlist')}}" class="nav-link">@lang('website.Wishlist')</a> </li>
                      <li class="nav-item"> <a href="{{url('compare')}}" class="nav-link">@lang('website.Compare')&nbsp;(<span id="compare">{{$count}}</span>)</a> </li>
                      <li class="nav-item"> <a href="{{url('orders')}}" class="nav-link">@lang('website.Orders')</a> </li>
                      <li class="nav-item"> <a href="{{url('shipping-address')}}" class="nav-link">@lang('website.Shipping Address')</a> </li>
                      <li class="nav-item"> <a href="{{url('logout')}}" class="nav-link padding-r0">@lang('website.Logout')</a> </li>
                      <?php }else{ ?>

                        <!-- <li class="nav-item"> <a href="{{url('orders')}}" class="nav-link">@lang('website.Orders')</a> </li> -->
                        <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;@lang('website.Login/Register')</a> </li>

                      <?php } ?>
                </ul>
              </div>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <?php  ?>
    <?php /* ?>
    <div class="header-navbar logo-nav bg-menu-bar">
      <div class="container">
        <nav id="navbar_1_2" class="navbar navbar-expand-lg  bg-nav-bar">
            <a href="{{ URL::to('/')}}" class="logo">
                @if($result['commonContent']['setting'][77]->value=='name')
                <?=stripslashes($result['commonContent']['setting'][78]->value)?>
                @endif

                @if($result['commonContent']['setting'][77]->value=='logo')
                <img src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?=stripslashes($result['commonContent']['setting'][79]->value)?>">
                @endif
            </a>
            <div class=" navbar-collapse">
              <ul class="navbar-nav ml-auto">
                @foreach($result['commonContent']["menus"] as $menus)
                <li class="nav-item dropdown">
                  <a class="nav-link @if(array_key_exists("childs",$menus)) dropdown-toggle @endif" @if($menus->type == 0)target="_blank"@endif  @if($menus->type == 0) href="{{$menus->external_link}}" @elseif($menus->type == 1) href="{{$menus->link}}" @else href="#" @endif >
                    {{$menus->name}}
                  </a>
                  @if(array_key_exists("childs",$menus))
                  <div class="dropdown-menu">
                    <?php
                    $array = (array) $menus->childs;
                    $key = "sub_sort_order";
                        $sorter=array();
                        $ret=array();
                        reset($array);
                        foreach ($array as $ii => $va) {
                          $va = (array) $va;

                            $sorter[$ii]=$va[$key];
                        }
                        asort($sorter);
                        foreach ($sorter as $ii => $va) {
                            $ret[$ii]=$array[$ii];
                        }
                        $array=$ret;
                     ?>
                    @foreach($array as $me)
                    <a class="dropdown-item" @if($me->type == 0)target="_blank"@endif  @if($me->type == 0) href="{{$me->external_link}}" @elseif($me->type == 1) href="{{$me->link}}" @else href="#" @endif  >
                      {{$me->name}}
                    </a>
                    @endforeach
                  </div>
                  @endif
                </li>
                @endforeach
                {{--<li class="nav-item ">
                      <a href="{{url('shop?type=special')}}"class="btn btn-secondary">@lang('website.SPECIAL DEALS')</a>
                    </li>--}}
              </ul>
            </div>

        </nav>
      </div>
    </div>
    <?php */ ?>
    <div class="header-maxi bg-header-bar">
      <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-2">
                <a href="{{ URL::to('/')}}" class="logo">
                    @if($result['commonContent']['setting'][77]->value=='name')
                        <?=stripslashes($result['commonContent']['setting'][78]->value)?>
                    @endif

                    @if($result['commonContent']['setting'][77]->value=='logo')
                        <img src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?=stripslashes($result['commonContent']['setting'][79]->value)?>">
                    @endif
                </a>
            </div>
          <div class="col-12 col-lg-6">
            <form class="form-inline" action="{{ URL::to('/check_search')}}" method="get">
              <div class="search">
                  <div class="select-control">
                      <select class="form-control" name="category">
                       @include('web.common.HeaderCategories')
                       @php    productCategories(); @endphp
                      </select>
                    </div>
                    <input type="search"  name="search" placeholder="@lang('website.Search entire store here')..." value="{{ app('request')->input('search') }}" aria-label="Search">
                <button class="btn btn-secondary" type="submit">
                <i class="fa fa-search"></i></button>
              </div>
            </form>
          </div>
          <div class="col-12 col-lg-4">
            <ul class="top-right-list">
              <li class="phone-header">
                <a href="#">
                    <i class="fas fa-phone"></i>
                    <span class="block">
                      <span class="title">@lang('website.Call Us Now')</span>
                      {{--<span class="items">{{$result['commonContent']['setting'][11]->value}}</span>--}}
                      <span class="items">+8801841262414</span>
                    </span>
                </a>
              </li>
              <li class="cart-header dropdown head-cart-content d-none d-md-block">
                <?php $qunatity=0; ?>
                                @foreach($result['commonContent']['cart'] as $cart_data)
                                	<?php $qunatity += $cart_data->customers_basket_quantity; ?>
                                @endforeach

                                <a href="#" id="dropdownMenuButton" class="dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="badge badge-secondary">{{ $qunatity }}</span>
                                    <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                                    <!--<img class="img-fluid" src="{{asset('').'public/images/shopping_cart.png'}}" alt="icon">-->

                                    <span class="block">
                                    	<span class="title">@lang('website.My Cart')</span>
                                        @if(count($result['commonContent']['cart'])>0)
                                            <span class="items">{{ count($result['commonContent']['cart']) }}&nbsp;@lang('website.items')</span>
                                        @else
                                            <span class="items">(0)&nbsp;@lang('website.item')</span>
                                        @endif
                                    </span>
                                </a>

                                @if(count($result['commonContent']['cart'])>0)
                                @php
                                $default_currency = DB::table('currencies')->where('is_default',1)->first();
                                if($default_currency->id == Session::get('currency_id')){

                                  $currency_value = 1;
                                }else{
                                  $session_currency = DB::table('currencies')->where('id',Session::get('currency_id'))->first();

                                  $currency_value = $session_currency->value;
                                }
                                @endphp
                                <div class="shopping-cart shopping-cart-empty dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCartButton_9">
                                    <ul class="shopping-cart-items">
                                        <?php
                                            $total_amount=0;
                                            $qunatity=0;
                                        ?>
                                        @foreach($result['commonContent']['cart'] as $cart_data)

                                        <?php
                					             	$total_amount += $cart_data->final_price*$cart_data->customers_basket_quantity;
                					            	$qunatity 	  += $cart_data->customers_basket_quantity; ?>
                                        <li>
                                            <div class="item-thumb">
                                            	<a href="{{ URL::to('/deleteCart?id='.$cart_data->customers_basket_id)}}" class="icon" ><img class="img-fluid" src="{{asset('').'web/images/close.png'}}" alt="icon"></a>
                                            	<div class="image">
                                                	<img class="img-fluid" src="{{asset('').$cart_data->image}}" alt="{{$cart_data->products_name}}"/>
                                                </div>
                                            </div>
                                            <div class="item-detail">
                                              <h2 class="item-name">{{$cart_data->products_name}}</h2>
                                              <span class="item-quantity">@lang('website.Qty')&nbsp;:&nbsp;{{$cart_data->customers_basket_quantity}}</span>
                                              <span class="item-price">{{Session::get('symbol_left')}}{{$cart_data->final_price*$cart_data->customers_basket_quantity*$currency_value}}{{Session::get('symbol_right')}}</span>
                                           </div>
                                        </li>
                                        @endforeach
                                    <li>
                                      <div class="tt-summary">
                                      	  <p>@lang('website.items')<span>{{ $qunatity }}</span></p>
                                        	<p>@lang('website.SubTotal')<span>{{Session::get('symbol_left')}}{{ $total_amount*$currency_value }}{{Session::get('symbol_right')}}</span></p>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="buttons">
                                          <a class="btn btn-dark" href="{{ URL::to('/viewcart')}}">@lang('website.View Cart')</a>
                                          <a class="btn btn-secondary" href="{{ URL::to('/checkout')}}">@lang('website.Checkout')</a>
                                      </div>
                                   </li>
                                 </ul>

                                </div>

                				@else

                                <div class="shopping-cart shopping-cart-empty dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <ul class="shopping-cart-items">
                                        <li>@lang('website.You have no items in your shopping cart')</li>
                                    </ul>
                                </div>
                                @endif

              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

        <?php
        //$all_recursivecategories = listofrecursivecategories();
        /*echo '<pre>';
        print_r($all_recursivecategories);
        echo '</pre>';*/
        //foreach($all_recursivecategories as $all_recursivecategorie){
            //echo '<pre>';
            //print_r($all_recursivecategorie);
            //print_r($all_recursivecategorie->categories_name);
            //print_r($all_recursivecategorie->childs);
            //echo '</pre>';

            /*if(!empty($all_recursivecategorie->childs)){
                foreach($all_recursivecategorie->childs as $value){
                    echo $value->categories_name;
                }
            }*/
            //exit;

        //}
        ?>

    <div class="header-navbar logo-nav bg-menu-bar" id="myHeader">
      <div class="container">
          <?php /* ?>
        <nav id="navbar_1_2" class="navbar navbar-expand-lg  bg-nav-bar">
            <div class=" navbar-collapse">
              <ul class="navbar-nav ml-auto">
                @foreach($result['commonContent']["menus"] as $menus)
                <li class="nav-item dropdown">
                  <a class="nav-link @if(array_key_exists("childs",$menus)) dropdown-toggle @endif" @if($menus->type == 0)target="_blank"@endif  @if($menus->type == 0) href="{{$menus->external_link}}" @elseif($menus->type == 1) href="{{$menus->link}}" @else href="#" @endif >
                    {{$menus->name}}
                  </a>
                  @if(array_key_exists("childs",$menus))
                  <div class="dropdown-menu">
                    <?php
                    $array = (array) $menus->childs;
                    $key = "sub_sort_order";
                        $sorter=array();
                        $ret=array();
                        reset($array);
                        foreach ($array as $ii => $va) {
                          $va = (array) $va;

                            $sorter[$ii]=$va[$key];
                        }
                        asort($sorter);
                        foreach ($sorter as $ii => $va) {
                            $ret[$ii]=$array[$ii];
                        }
                        $array=$ret;
                     ?>
                    @foreach($array as $me)
                    <a class="dropdown-item" @if($me->type == 0)target="_blank"@endif  @if($me->type == 0) href="{{$me->external_link}}" @elseif($me->type == 1) href="{{$me->link}}" @else href="#" @endif  >
                      {{$me->name}}
                    </a>
                    @endforeach
                  </div>
                  @endif
                </li>
                @endforeach
                {{--<li class="nav-item ">
                      <a href="{{url('shop?type=special')}}"class="btn btn-secondary">@lang('website.SPECIAL DEALS')</a>
                    </li>--}}
              </ul>
            </div>

        </nav>
 <?php */ ?>





           <nav> <!-- start Main menu -->
              <ul>  <!-- start main UL -->
                  {{--@foreach($result['commonContent']["menus"] as $menus)
                      <li class="">
                          <a class="nav-link @if(array_key_exists("childs",$menus)) dropdown-toggle @endif" @if($menus->type == 0)target="_blank"@endif  @if($menus->type == 0) href="{{$menus->external_link}}" @elseif($menus->type == 1) href="{{$menus->link}}" @else href="#" @endif >
                              {{$menus->name}}
                          </a>
                      </li>
                  @endforeach--}}
                  <li class="active"><a href="">Home</a></li>
                  <li><a href="shop">Shop</a></li>
                  <li><a href="contact">Contact Us</a></li>
                  <?php /*if($all_recursivecategories):?>
                  <li><a href="#">Category</a>
                      <ul>
                          <?php
                            foreach($all_recursivecategories as $all_recursivecategorie){
                                echo '<pre>';
                                print_r($all_recursivecategorie);
                                echo '</pre>';
                              ?>
                          <li>
                              <a href="#">
                                  <?= $all_recursivecategorie->categories_name?>
                              </a>
                          </li>
                          <?php }?>
                      </ul>
                  </li>
                  <?php endif;*/?>
              </ul>
          </nav>


      </div>
    </div>

  </header>
