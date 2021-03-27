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
                     <input type="text" required name="available_point" class="form-control" id="inputName" value="{{$result['userInfo']->current_reward_point}}">
                   </div>
                 </div>
                 <div class="form-group row">
                   <label for="lastName" class="col-sm-2 col-form-label">Requested Point</label>
                   <div class="col-sm-10">
                     <input type="text" required name="request_point"  class="form-control field-validate" id="lastName">
                   </div>
                 </div>
                 <div class="form-group row">
                   <label for="gender"  class="col-sm-2 col-form-label">@lang('website.Gender')</label>
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

                 <div class="form-group row" style="display: none">
                     <label for="number" class="col-sm-2 col-form-label">Number</label>
                     <div class="col-sm-10">
                         <input type="text" name="number"  class="form-control field-validate" id="number">
                     </div>
                 </div>

                   <button type="submit" class="btn btn-primary">Save</button>
             </form>

         <!-- ............the end..... -->


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
                               <td class="col-12 col-md-2">{{$withdrawRequestList->request_payment_by}}</td>
                               <td class="col-12 col-md-2">{{$withdrawRequestList->request_status}}</td>
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
 @endsection
