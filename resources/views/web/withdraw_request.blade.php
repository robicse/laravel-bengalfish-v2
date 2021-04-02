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
             @if($reward_point >= 250)
             <form name="withdrawRequestStore" class="align-items-center" enctype="multipart/form-data" action="{{ URL::to('withdrawRequestStore')}}" method="post">
               @csrf
                @if( count($errors) > 0)
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">@lang('website.Error'):</span>
                            {{ $error }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endforeach
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">@lang('website.Error'):</span>
                        {{ session()->get('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">@lang('website.Error'):</span>
                        {!! session('loginError') !!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session()->has('success') )
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session()->get('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
{{--                <div class="form-group row justify-content-center">--}}
{{--                  <div class="col-12 media-main">--}}
{{--                      <div class="media">--}}
{{--                        @if(!empty(auth()->guard('customer')->user()->avatar))--}}
{{--                            <input type="hidden" name="customers_old_picture" value="{{ auth()->guard('customer')->user()->avatar }}">--}}
{{--                        @else--}}
{{--                          <input type="hidden" name="customers_old_picture" value="">--}}
{{--                        @endif--}}
{{--                          <img style="margin-bottom:-50px;" src="{{auth()->guard('customer')->user()->avatar}}" alt="avatar">--}}
{{--                          <div class="media-body"style="margin-left:70px; margin-bottom:-50px;">--}}
{{--                            <div class="row">--}}
{{--                              <div class="col-12 col-sm-4 col-md-6">--}}
{{--                                 <input name="picture" id="userImage" type="file" class="inputFile" onChange="showPreview(this);" /><br>--}}
{{--                              </div>--}}
{{--                            </div>--}}
{{--                          </div>--}}

{{--                      </div>--}}
{{--                  </div>--}}
{{--                </div>--}}

                 <div class="form-group row">
                   <label for="firstName" class="col-sm-2 col-form-label">Available Point</label>
                   <div class="col-sm-10">
                     <input type="text" required name="available_point" class="form-control" id="available_point" value="{{$result['userInfo']->current_reward_point}}">
                   </div>
                 </div>
                 <div class="form-group row">
                   <label for="lastName" class="col-sm-2 col-form-label">Requested Point</label>
                   <div class="col-sm-10">
                     <input type="text" required name="request_point"  class="form-control field-validate" id="request_point">
                   </div>
                 </div>
                 <div class="form-group row">
                   <label for="gender"  class="col-sm-2 col-form-label">Request Payment By</label>
                     <div class="col-5 col-sm-5">
                         <div class="select-control">
                             <select name="request_payment_by" required class="form-control" id="request_payment_by" aria-describedby="genderHelp">
                               <option value="Cash">Cash</option>
                               <option value="BKash">BKash</option>
                               <option value="Rocket">Rocket</option>
                              </select>
                         </div>
                     </div>

                   </div>

                 <div class="form-group row" id="payment_by_number" style="display: none">
                     <label for="payment_by_number" class="col-sm-2 col-form-label">Payment By Number</label>
                     <div class="col-sm-10">
                         <input type="text" name="payment_by_number"  class="form-control field-validate">
                     </div>
                 </div>

                   <button type="submit" class="btn btn-primary">Save</button>
             </form>
             @endif

         <!-- ............the end..... -->


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

    jQuery(document).on('keyup', '#request_point', function(e){

        var available_point = jQuery("#available_point").val();
        var request_point = jQuery("#request_point").val();
        console.log(request_point);
        
        if(request_point > available_point){
            alert('You do not requested your current reward point!');
            jQuery("#request_point").val('');
        }
    });

    jQuery(document).on('blur', '#request_point', function(e){

        var available_point = jQuery("#available_point").val();
        var request_point = jQuery("#request_point").val();
        console.log(request_point);

        if(request_point < 20){
            alert('You do not requested less than 20 reward point!');
            jQuery("#request_point").val('');
        }
    });

</script>
 @endsection

