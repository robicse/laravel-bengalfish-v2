@extends('web.layout')
@section('content')
<!-- Profile Content -->
<section class="profile-content">
   <div class="container">
     <div class="row">
         <div class="col-12 col-sm-12">
             <div class="row ">
                 <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
                       <li class="breadcrumb-item"><a href="#">@lang('website.Home')</a></li>
                       <li class="breadcrumb-item active" aria-current="page">@lang('website.myProfile')</li>
                     </ol>
                   </nav>
             </div>
         </div>

       <div class="col-12 col-lg-3">
           <div class="heading">
               <h2>
                   @lang('website.My Account')
               </h2>
               <hr >
             </div>

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
                       <a class="nav-link" href="{{ URL::to('/reward_point')}}">
                           <i class="fas fa-heart"></i>
                           Withdraw Request
                       </a>
                   </li>
               @endif
               <li class="list-group-item">
                   <a class="nav-link" href="{{ URL::to('/withdraw_request_list')}}">
                       <i class="fas fa-heart"></i>
                       Withdraw Request List
                   </a>
               </li>
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
       </div>
       <div class="col-12 col-lg-9 ">
           <div class="heading">
               <h2>
                   Withdraw Request
               </h2>
               <hr >
             </div>


           <div class="col-12 col-lg-3">
               <div>&nbsp;</div>
               <h2>Withdraw List</h2>
               <table class="table order-table">

                   <thead>
                   <tr class="d-flex">
{{--                       <th class="col-12 col-md-2">SL</th>--}}
                       {{--                     <th class="col-12 col-md-2">Date</th>--}}
                       <th class="col-12 col-md-2">Available Point</th>
                       <th class="col-12 col-md-2">Requested Point</th>
                       <th class="col-12 col-md-2">Received Point</th>
                       <th class="col-12 col-md-2" >Available Amount</th>
                       <th class="col-12 col-md-2" >Requested Amount</th>
                       <th class="col-12 col-md-2" >Received Amount</th>
                       <th class="col-12 col-md-2" >Request Payment By</th>
{{--                       <th class="col-12 col-md-2" >Payment By Number</th>--}}
                       <th class="col-12 col-md-2" >Request Status</th>
                       {{--                   <th class="col-12 col-md-2" ></th>--}}

                   </tr>
                   </thead>
                   <tbody>
                   @if(count($result['withdrawRequestLists']) > 0)
                       @foreach( $result['withdrawRequestLists'] as $withdrawRequestList)
                           <tr class="d-flex">
{{--                               <td class="col-12 col-md-2">{{$withdrawRequestList->id}}</td>--}}
                               {{--                             <td class="col-12 col-md-2">--}}
                               {{--                                 {{ date('d/m/Y', strtotime($withdrawRequestList->created_at))}}--}}
                               {{--                             </td>--}}
                               <td class="col-12 col-md-2">{{$withdrawRequestList->available_point}}</td>
                               <td class="col-12 col-md-2">{{$withdrawRequestList->request_point}}</td>
                               <td class="col-12 col-md-2">{{$withdrawRequestList->received_point}}</td>
                               <td class="col-12 col-md-2">{{$withdrawRequestList->available_amount}}</td>
                               <td class="col-12 col-md-2">{{$withdrawRequestList->request_amount}}</td>
                               <td class="col-12 col-md-2">{{$withdrawRequestList->received_amount}}</td>
                               <td class="col-12 col-md-2">
                                   {{$withdrawRequestList->request_payment_by}}
                                   @if($withdrawRequestList->payment_by_number)
                                       ({{$withdrawRequestList->payment_by_number}})
                                   @endif
                               </td>
                               <td class="col-12 col-md-2">{{$withdrawRequestList->payment_status}}</td>
                           </tr>
                       @endforeach
                   @else
                       <tr>
                           <td colspan="4">No Withdraw Request List is placed yet</td>
                       </tr>
                   @endif
                   </tbody>
               </table>
           </div>


       </div>


     </div>
   </div>
 </section>
<script>
    jQuery(document).on('change', '#request_payment_by', function(e){
        var request_payment_by = jQuery("#request_payment_by").val();
        console.log(request_payment_by);
        if(request_payment_by != 'Cash'){
            console.log(request_payment_by);
            jQuery("#payment_by_number").show();
        }else{
            console.log(request_payment_by);
            jQuery("#payment_by_number").hide();
        }
    });
</script>
 @endsection

