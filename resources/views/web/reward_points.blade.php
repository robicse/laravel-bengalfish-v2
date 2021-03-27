@extends('web.layout')
@section('content')
     <!--My Order Content -->
     <section class="order-one-content">
      <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="row justify-content-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                          <li class="breadcrumb-item active" aria-current="page">My Reward Point</li>
                        </ol>
                      </nav>
                </div>
            </div>
          <div class="col-12 col-lg-3  d-none d-lg-block d-xl-block">
            <div class="heading">
                <h2>
                    @lang('website.My Account')
                </h2>
                <hr >
              </div>
   @if(Auth::guard('customer')->check())
            <ul class="list-group">
                <li class="list-group-item">
                    <a class="nav-link" href="{{ URL::to('/profile')}}">
                        <i class="fas fa-user"></i>
                      @lang('website.Profile')
                    </a>
                </li>
                <li class="list-group-item">
                    <a class="nav-link" href="{{ URL::to('/reward_point')}}">
                        <i class="fas fa-heart"></i>
                     Reward Point ({{$reward_point = \Illuminate\Support\Facades\DB::table('users')->where('id',auth()->guard('customer')->user()->id)->pluck('current_reward_point')->first()}})
                    </a>
                </li>
                @if($reward_point >= 250)
                <li class="list-group-item">
                    <a class="nav-link" href="{{ URL::to('/withdraw_request')}}">
                        <i class="fas fa-heart"></i>
                        Withdraw Request
                    </a>
                </li>
                @endif
                <li class="list-group-item">
                    <a class="nav-link" href="{{ URL::to('/wishlist')}}">
                        <i class="fas fa-heart"></i>
                        @lang('website.Wishlist')
                    </a>
                </li>
                <li class="list-group-item">
                    <a class="nav-link" href="{{ URL::to('/orders')}}">
                        <i class="fas fa-shopping-cart"></i>
                      @lang('website.Orders')
                    </a>
                </li>
                <li class="list-group-item">
                    <a class="nav-link" href="{{ URL::to('/shipping-address')}}">
                        <i class="fas fa-map-marker-alt"></i>
                     @lang('website.Shipping Address')
                    </a>
                </li>
                <li class="list-group-item">
                    <a class="nav-link" href="{{ URL::to('/logout')}}">
                        <i class="fas fa-power-off"></i>
                      @lang('website.Logout')
                    </a>
                </li>
              </ul>
              @endif
          </div>
          <div class="col-12 col-lg-9 ">
              <div class="heading">
                  <h2>
                      @lang('website.My Orders')
                  </h2>
                  <hr >
                </div>
                @if(session()->has('message'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         {{ session()->get('message') }}
                    </div>

                @endif

              <table class="table order-table">

                <thead>
                  <tr class="d-flex">
                    <th class="col-12 col-md-2">Order Id</th>
                    <th class="col-12 col-md-2">Order Price</th>
                    <th class="col-12 col-md-2">Get Reward Point</th>
                    <th class="col-12 col-md-2" >Get Reward Point Amount</th>
                    <th class="col-12 col-md-2" ></th>

                  </tr>
                </thead>
                <tbody>
                  @if(count($result['reward_points']) > 0)
                  @foreach( $result['reward_points'] as $reward_point)
                  <tr class="d-flex">
                    <td class="col-12 col-md-2">{{$reward_point->order_id}}</td>
                    <td class="col-12 col-md-2">{{$reward_point->order_price}}</td>
                    <td class="col-12 col-md-2">{{$reward_point->get_reward_point}}</td>
                    <td class="col-12 col-md-2">{{$reward_point->get_reward_point_amount}}</td>
{{--                    <td class="col-12 col-md-2">--}}
{{--                      {{ date('d/m/Y', strtotime($orders->date_purchased))}}--}}
{{--                    </td>--}}
                  </tr>
                  @endforeach
                  @else
                      <tr>
                          <td colspan="4">@lang('website.No order is placed yet')
                          </td>
                      </tr>
                  @endif
                </tbody>
              </table>
            <!-- ............the end..... -->
          </div>
        </div>
      </div>
    </section>

@endsection
