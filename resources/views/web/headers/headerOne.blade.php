<!-- //header style One -->
<link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed&display=swap" rel="stylesheet">
{{--toastr css--}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
    .header-one .header-maxi .form-inline .search .select-control .form-control{
        font-family: Roboto Condensed;
    }
    .header-one .header-maxi .top-right-list li .block .title {
      font-family: Roboto Condensed;
}

    nav{margin-top:0px;}

    nav ul{list-style:none;border-radius:5px;padding:0 5px;}

    nav ul:after {content: '.';clear:both;visibility:hidden;display:block;height:0px;}

    nav ul li{float:left;position:relative;line-height:20px;}

    nav ul li a{display:block;color:#000000;text-decoration:none;padding:14px 15px 15px;-webkit-transition:0.25s ease-out;}

    nav ul li:hover > a{color:#3498DB;text-decoration:none;}

    nav ul li.active > a{color:#00bfff;}

    /*-- Sub menu --*/

    nav ul li > ul:before{content:"";border-style:solid;border-width:0 9px 9px 9px;border-color:transparent transparent blue transparent;width:0px;height:0px;position:absolute;left:15px;top:5px;}

    nav ul li > ul{position:absolute;left:14px;top:80%;padding-top:13px;background:none;width:150px;z-index:-9999;opacity:0;-webkit-transition:0.3s ease-out;-moz-transition:0.3s ease-out;transition:0.3s ease-out;}

    nav ul li:hover > ul{display:block;z-index:100;opacity:1;top:95%;}

    nav ul li > ul li:first-child{border-radius:4px 4px 0 0;padding-top:5px;}

    nav ul li > ul li:last-child{border-radius:0 0 4px 4px;}

    nav ul li > ul li{padding:0 3px 3px;background:#ffffff;width:100%;}

    nav ul li > ul li a{display:block;padding:6px 9px;border-radius:2px;font-size:14px;}

    nav ul li > ul li:hover  > a{color:#FFF;background:#3498DB;}

    nav ul li > ul li:active  > a{color:#FFF;background:#00bfff;}

    /*-----sub sub menu -----*/

    nav ul li > ul li > ul:before{content:"";border-style:solid;border-width:0 9px 9px 9px;border-color:transparent transparent blue transparent;width:0px;height:0px;position:absolute;left:0px;top:15px;-webkit-transform:rotate(270deg); -moz-transform:rotate(270deg);-ms-transform:rotate(270deg);-o-transform:rotate(270deg);transform:rotate(270deg);}

    nav ul  li > ul li > ul{top:0;left:90%;padding:0;padding-left:13px;-webkit-transition:0.3s ease-out;-moz-transition:0.3s ease-out;transition:0.3s ease-out;}

    nav ul li > ul li:hover > ul{display:block;z-index:100;opacity:1;top:0;left:100%;}

</style>
<header id="headerOne" class="header-area header-one header-desktop d-none d-lg-block d-xl-block sticky-top">
    <?php  ?>
    <div class="header-maxi bg-header-bar">
      <div class="container">
 <!-- ------------------------------------------------------Start Row 01 ---------------------------------------------- -->
        <div class="row align-items-center">
             <div class="col-12 col-lg-5">
                 <ul class="fm-top-contact-list">
                    <li><i class="fas fa-phone"></i> <span>09613-850 850, 01311-154006</span>
                    </li>
                    <li><i class="fas fa-envelope"></i> <span><a href="mailto:info@bengalfish.com.bd"> info@bengalfish.com.bd</a>
                        </span>
                    </li>
                </ul>
             </div>
             <div class="col-12 col-lg-2">
                 <ul class="fm-top-middle-menu">
{{--                    <li style="margin-top:13px;"><a href="https://bengalfish.com.bd/">Home |</a>  </li>--}}
{{--                    <li style="margin-top:13px;"><a href="https://bengalfish.com.bd/shop">Shop | </a> </li>--}}
                    <li>
                      <div class="navbar-lang">
                        @if(count($languages) > 1)
{{--                        <div class="dropdown">--}}
{{--                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">--}}
{{--                                <i class="fa fa-language " style="color: #fff; font-size: 1.9rem" aria-hidden="true"></i>--}}
{{--                            </button>--}}
{{--                            <ul class="dropdown-menu">--}}
{{--                              @foreach($languages as $language)--}}
{{--                              <li  @if(session('locale')==$language->code) style="" @endif>--}}
{{--                                <button  onclick="myFunction1({{$language->languages_id}})" class="btn" style="background:none;" href="#">--}}
{{--                                  <img style="margin-left:10px; margin-right:10px;"src="{{asset('').$language->image_path}}" width="17px" />--}}
{{--                                  <span>{{$language->name}}</span>--}}
{{--                                </button>--}}
{{--                              </li>--}}
{{--                              @endforeach--}}
{{--                            </ul>--}}
{{--                          </div>--}}
                          @include('web.common.scripts.changeLanguage')
                          @endif
                          @if(count($currencies) > 1)
{{--                          <div class="dropdown">--}}
{{--                              <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">--}}

{{--                                @if(session('symbol_left') != null)--}}
{{--                                <span >{{session('symbol_left')}}</span>--}}
{{--                                @else--}}
{{--                                <span >{{session('symbol_right')}}</span>--}}
{{--                                @endif--}}
{{--                               {{session('currency_code')}}--}}
{{--                              </button>--}}
{{--                              <ul class="dropdown-menu">--}}
{{--                                @foreach($currencies as $currency)--}}
{{--                                <li  @if(session('currency_title')==$currency->code) style="" @endif>--}}
{{--                                  <button  onclick="myFunction2({{$currency->id}})" class="btn" style="background:none;" href="#">--}}
{{--                                    @if($currency->symbol_left != null)--}}
{{--                                    <span style="margin-left:10px; margin-right:10px;">{{$currency->symbol_left}}</span>--}}
{{--                                    <span>{{$currency->code}}</span>--}}
{{--                                    @else--}}
{{--                                    <span style="margin-left:10px; margin-right:10px;">{{$currency->symbol_right}}</span>--}}
{{--                                    <span>{{$currency->code}}</span>--}}
{{--                                    @endif--}}
{{--                                  </button>--}}
{{--                                </li>--}}
{{--                                @endforeach--}}
{{--                              </ul>--}}
{{--                            </div>--}}
                            @include('web.common.scripts.changeCurrency')
                            @endif
                      </div>
                    </li>
                 </ul>
             </div>
             <div class="col-12 col-lg-5">
                 <ul class="fm-top-right-menu">
{{--	                <li class="cart-header dropdown head-cart-content d-none d-md-block mt-2" style="width: 300px;">--}}
	                <li class="cart-header dropdown head-cart-content d-none d-md-block mt-2">
                        <?php $qunatity=0; ?>
                        @foreach($result['commonContent']['cart'] as $cart_data)
                            <?php $qunatity += $cart_data->customers_basket_quantity; ?>
                        @endforeach
                        <a href="#" id="dropdownMenuButton" class="dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="badge badge-secondary">{{ $qunatity }}</span>
                            <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                            <span class="block">
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
                            <div class="shopping-cart shopping-cart-empty dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCartButton_9" style="min-width: 300px;">
                                <table class="table">
                                  <tbody>
                                    <?php
                                        $total_amount=0;
                                        $qunatity=0;
                                    ?>
                                    @foreach($result['commonContent']['cart'] as $cart_data)
                                    <?php
                                        $total_amount += $cart_data->final_price*$cart_data->customers_basket_quantity;
                                        $qunatity 	  += $cart_data->customers_basket_quantity; ?>
                                    <tr>
                                      <td class="text-center">
                                          <a href="{{ URL::to('/deleteCart?id='.$cart_data->customers_basket_id)}}" class="icon" style="color:black" ><i class="fa fa-times" aria-hidden="true"></i></a>
                                      </td>
                                      <td>
                                          <div class="item-thumb">
                                            <div class="image">
                                                <img class="img-fluid" src="{{asset('').$cart_data->image}}" alt="{{$cart_data->products_name}}" width="60px"/>
                                            </div>
                                           </div>
                                        </td>
                                      <td colspan="2"><h6 class="item-name" style="font-family: 'Roboto Condensed'">{{strtoupper($cart_data->products_name)}}</h6></td>
                                      <td><span class="item-quantity">@lang('website.Qty')&nbsp;:&nbsp;{{$cart_data->customers_basket_quantity}}</span></td>
                                      <td><span class="item-price">{{Session::get('symbol_left')}}{{$cart_data->final_price*$cart_data->customers_basket_quantity*$currency_value}}{{Session::get('symbol_right')}}</span></td>

                                    </tr>
                                    @endforeach
                                    <tr>
                                      <td>
                                          <p>@lang('website.items')<span>{{ $qunatity }}</span></p>

                                      </td>
                                      <td>
                                          <p>@lang('website.SubTotal')<span>{{Session::get('symbol_left')}}{{ $total_amount*$currency_value }}{{Session::get('symbol_right')}}</span></p>

                                      </td>
                                      <td>
                                          <a class="btn btn-dark" href="{{ URL::to('/viewcart')}}">Cart</a>
                                      </td>
                                      <td>
                                          <a class="btn btn-secondary" href="{{ URL::to('/checkout')}}">@lang('website.Checkout')</a>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                            </div>
                        @else
                            <div class="shopping-cart shopping-cart-empty dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <ul class="shopping-cart-items">
                                    <li>@lang('website.You have no items in your shopping cart')</li>
                                </ul>
                            </div>
                        @endif
                    </li>
                    <?php if(auth()->guard('customer')->check()){ ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span style="color: #00bfff; font-size: 16px;"><?php if(auth()->guard('customer')->check()){ ?>@lang('website.Welcome')&nbsp;! {{auth()->guard('customer')->user()->first_name}} <?php }?> </span>
                        </a>
                        @php
                        $liked_products = \Illuminate\Support\Facades\DB::table('liked_products')->where('liked_customers_id',auth()->guard('customer')->user()->id)->get();
                        $reward_point = \Illuminate\Support\Facades\DB::table('users')->where('id',auth()->guard('customer')->user()->id)->pluck('current_reward_point')->first();
                        $membership_category = \Illuminate\Support\Facades\DB::table('users')->where('id',auth()->guard('customer')->user()->id)->pluck('membership_category')->first();
                        @endphp
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color:black">
                          <a class="dropdown-item" href="{{url('profile')}}" class="nav-link">@lang('website.Profile')</a>
                          <a class="dropdown-item" href="#" class="nav-link">
                              Membership ({{$membership_category}})
                          </a>
{{--                          <a class="dropdown-item" href="{{url('withdraw_request_list')}}" class="nav-link">Withdraw Request List</a>--}}
                          <a class="dropdown-item" href="{{url('reward_point')}}" class="nav-link">Reward Point ({{$reward_point}})</a>
                          @if($reward_point >= 250)
                          <a class="dropdown-item" href="{{url('withdraw_request')}}" class="nav-link">Withdraw Request</a>
                          @endif
                          <a class="dropdown-item" href="{{url('withdraw_request_list')}}" class="nav-link">Withdraw Request List</a>
                          <a class="dropdown-item" href="{{url('wishlist')}}" class="nav-link">@lang('website.Wishlist') (<span id="liked_count">{{$liked_products->count()}}</span>)</a>
                          <a class="dropdown-item" href="{{url('compare')}}" class="nav-link">@lang('website.Compare')&nbsp;(<span id="compare">{{$count}}</span>)</a>
                          <a class="dropdown-item" href="{{url('orders')}}" class="nav-link">@lang('website.Orders')</a>
                          <a class="dropdown-item" href="{{url('shipping-address')}}" class="nav-link">@lang('website.Shipping Address')</a>
                          <a class="dropdown-item" href="{{url('logout')}}" class="nav-link padding-r0">@lang('website.Logout')</a>
                        </div>
                    </li>
                     <li style="min-height: 20px !important;">
                         <div class="">
                             <?php
                             if(auth()->guard('customer')->check()){
                             if(auth()->guard('customer')->user()->avatar == null){ ?>
                             <img src="{{asset('web/images/miscellaneous/avatar.jpg')}}" width="30px" style="margin-left: 0;margin-top: 5px">
                             <?php }else{ ?>
                             <img src="{{auth()->guard('customer')->user()->avatar}}" width="30px" style="margin-left: 0;margin-top: 5px">
                             <?php
                             }
                             }
                             ?>
                         </div>
                     </li>
                    <?php }else{ ?>
                    <li class="nav-item p-0" style="background-color: #f89e20;padding: 4px 18px;">
                        <a href="{{ URL::to('/login')}}" class="nav-link -before">
                            <i class="fa fa-lock" aria-hidden="true"></i>&nbsp;@lang('website.Login/Register')
                        </a>
                    </li>
                    @if(count($languages) > 1)
                        @foreach($languages as $language)
                             <li class="nav-item p-0" @if(session('locale')==$language->code) style="background-color: #f89e20;" @elseif((session('locale')== null) && ('en'==$language->code)) style="background-color: #f89e20;" @else style="background:lightgrey;" @endif>
                                 <button  onclick="myFunction1({{$language->languages_id}})" class="btn" style="background:none;color: #ffffff;" href="#">
                                     <span>{{$language->name}}</span>
                                 </button>
                             </li>
                        @endforeach
                    @endif
                    <?php } ?>
                 </ul>
             </div>
        </div>
<!-- ------------------------------------------------------End Row 01 ---------------------------------------------- -->

<!-- ------------------------------------------------------Start Row 02 ---------------------------------------------- -->

<!-- ------------------------------------------------------End Row 02 ---------------------------------------------- -->


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
             @include('web.common.HeaderCategories')
             <div class="col-12 col-lg-6">
                <nav id="navbar_0_2" class="navbar navbar-expand-md navbar-dark navbar-0">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <!--<li class="nav-item">-->
                        <!--    <a class="nav-link pr-0 mr-1" href="#">Home</a>-->
                        <!--</li>-->
                        <!--<li class="nav-item">-->
                        <!--    <a class="nav-link pr-0 mr-1" href="#">Shop</a>-->
                        <!--</li>-->
                        <!--<li class="nav-item">-->
                        <!--    <a class="nav-link" href="#">Contact Us</a>-->
                        <!--</li>-->

                        <li class="nav-item">
                            <a class="nav-link pr-0 mr-1" href="{{url('/')}}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link pr-0 mr-1" href="{{url('shop')}}">Shop</a>
                        </li>
                        <li class="nav-item">
{{--                            <a class="nav-link pr-0 mr-1" href="{{url('news')}}">Blog</a>--}}
                            <a class="nav-link pr-0 mr-1" href="{{url('blogs')}}">Blog</a>
                        </li>

                        @php
                            $specific_categories = list_of_specific_categories();
                            /*echo '<pre>';
                            print_r($specific_category);
                            echo '</pre>';*/
                        @endphp
                        @if(!empty($specific_categories))
                            @foreach($specific_categories as $specific_category)
                            <li class="nav-item">
                                <a class="nav-link pr-0 mr-1" href="{{url('shop/category').'/'.$specific_category->slug}}">{{ $specific_category->categories_name }}</a>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </nav>
          </div>
          <div class="col-12 col-lg-4">
            <form class="form-inline" action="{{ URL::to('/check_search')}}" method="get">
              <div class="search">
                  <div class="select-control">
                      <select class="form-control" name="category">
                        @php productCategories(); @endphp
                      </select>
                  </div>
                  <input type="search"  name="search" placeholder="@lang('website.Search entire store here')..." value="{{ app('request')->input('search') }}" aria-label="Search">
                  <button class="btn btn-secondary" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
              </div>
            </form>
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

        <?php
        $all_recursivecategories = listofrecursivecategories();
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

{{--    <div class="header-navbar logo-nav bg-menu-bar" id="myHeader">--}}
{{--      <div class="container">--}}
{{--          <nav class="navbar navbar-expand-lg navbar-light bg-light">--}}
{{--              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--                <span class="navbar-toggler-icon"></span>--}}
{{--              </button>--}}

{{--              <div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
{{--                <ul class="navbar-nav mr-auto">--}}
{{--                  <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Home</a>--}}
{{--                  </li>--}}
{{--                    <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Shop</a>--}}
{{--                  </li>--}}
{{--                    <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Contact Us</a>--}}
{{--                  </li>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Fresh Fish</a>--}}
{{--                  </li>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Deshi Fish</a>--}}
{{--                  </li>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Ready to Cook Fish</a>--}}
{{--                  </li>--}}
{{--                </ul>--}}
{{--              </div>--}}
{{--            </nav>--}}


{{--      </div>--}}
{{--    </div>--}}

  </header>
