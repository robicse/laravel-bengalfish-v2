@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>  Customer Reward Point Category </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active"> Customer Reward Point Withdraw Request</li>
            </ol>
        </section>

        <!--  content -->
        <section class="content">
            <!-- Info boxes -->

            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"> Customer Reward Point Withdraw Request</h3>

                            <div class="col-lg-6 form-inline" id="contact-form">


{{--                                <form  name='registration' id="registration" class="registration" method="get" action="{{url('admin/coupons/filter')}}">--}}
{{--                                    <input type="hidden"  value="{{csrf_token()}}">--}}
{{--                                    <div class="input-group-btn search-panel ">--}}
{{--                                    <div class="input-group-form search-panel ">--}}
{{--                                        <select type="button" class="btn btn-default dropdown-toggle form-control" data-toggle="dropdown" name="FilterBy" id="FilterBy"  >--}}
{{--                                            <option value="" selected disabled hidden>Filter By</option>--}}
{{--                                            <option value="Code"  @if(isset($name)) @if  ($name == "Code") {{ 'selected' }} @endif @endif>{{ trans('labels.Code') }}</option>--}}
{{--                                        </select>--}}
{{--                                        </div>--}}

{{--                                        <input type="text" class="form-control input-group-form " name="parameter" placeholder="Search term..." id="parameter" @if(isset($param)) value="{{$param}}" @endif >--}}
{{--                                        <span class="input-group-btn">--}}
{{--                                        <button class="btn btn-primary " id="submit" type="submit"><span class="glyphicon glyphicon-search"></span></button>--}}
{{--                                        @if(isset($param,$name))  <a class="btn btn-danger " href="{{url('admin/coupons/display')}}"><i class="fa fa-ban" aria-hidden="true"></i> </a>@endif--}}
{{--                                        </span>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                                <div class="col-lg-4 form-inline" id="contact-form12"></div>--}}
                            </div>


{{--                            <div class="box-tools pull-right">--}}
{{--                                <a href="{{ URL::to('admin/customer_reward_point_category/add')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNew') }}</a>--}}
{{--                            </div>--}}
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    @if (count($errors) > 0)
                                        @if($errors->any())
                                            <div class="alert alert-success alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                {{$errors->first()}}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Available Point</th>
                                            <th>Requested Point</th>
                                            <th>Received Point</th>
                                            <th>Available Amount</th>
                                            <th>Requested Amount</th>
                                            <th>Received Amount</th>
                                            <th>Request Payment By</th>
                                            <th>Request Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($withdrawRequestLists !== null)
                                            @foreach ($withdrawRequestLists as $key=>$withdrawRequestList)

                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $withdrawRequestList->available_point }}</td>
                                                    <td>{{ $withdrawRequestList->request_point }}</td>
                                                    <td>{{ $withdrawRequestList->received_point }}</td>
                                                    <td>{{ $withdrawRequestList->available_amount }}</td>
                                                    <td>{{ $withdrawRequestList->request_amount }}</td>
                                                    <td>{{ $withdrawRequestList->received_amount }}</td>
                                                    <td>
                                                        {{$withdrawRequestList->request_payment_by}}
                                                        @if($withdrawRequestList->payment_by_number)
                                                            ({{$withdrawRequestList->payment_by_number}})
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $withdrawRequestList->payment_status }}
                                                        @if($withdrawRequestList->payment_status != 'Paid')
                                                            <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteCoupans_id" coupans_id="{{ $withdrawRequestList->id }}" class="badge bg-red">
                                                                Pay Now
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>


                                                <!-- deleteCoupanModal -->
                                                <div class="modal fade" id="deleteCoupanModal" tabindex="-1" role="dialog" aria-labelledby="deleteCoupanModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title" id="deleteCoupanModalLabel">Pay Now</h4>
                                                            </div>
                                                            {!! Form::open(array('url' =>'admin/customer_reward_point_withdraw/insert', 'name'=>'deleteCoupan', 'id'=>'deleteCoupan', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                                                            {{--                        {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}--}}
                                                            {!! Form::hidden('customer_id', $withdrawRequestList->customer_id, array('class'=>'form-control', 'id'=>'customer_withdraw_request_id')) !!}
                                                            {!! Form::hidden('customer_withdraw_request_id', $withdrawRequestList->id, array('class'=>'form-control', 'id'=>'customer_withdraw_request_id')) !!}

                                                            <div class="modal-body">
{{--                                                                <p>{{ trans('labels.DeleteCouponText') }}</p>--}}
                                                                <div class="form-group row">
                                                                    <label for="request_point" class="col-sm-5 col-form-label">Requested Point</label>
                                                                    <div class="col-sm-5">
                                                                        <input type="text" name="request_point" value="{{$withdrawRequestList->request_point}}" class="form-control field-validate" id="request_point" required readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="request_point" class="col-sm-5 col-form-label">Requested Point Amount</label>
                                                                    <div class="col-sm-5">
                                                                        <input type="text" name="request_amount" value="{{$withdrawRequestList->request_amount}}" class="form-control field-validate" id="request_point" required readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="request_point" class="col-sm-5 col-form-label">Paid Point</label>
                                                                    <div class="col-sm-5">
                                                                        <input type="text" name="received_point" value="{{$withdrawRequestList->request_point}}" class="form-control field-validate" id="request_point" required readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="request_point" class="col-sm-5 col-form-label">Paid Point Amount</label>
                                                                    <div class="col-sm-5">
                                                                        <input type="text" name="received_amount" value="{{$withdrawRequestList->request_amount}}" class="form-control field-validate" id="request_point" required readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="gender"  class="col-sm-5 col-form-label">Request Payment By</label>
                                                                    <div class="col-5 col-sm-5">
                                                                        <div class="select-control">
                                                                            <select name="request_payment_by" required class="form-control" id="request_payment_by" aria-describedby="genderHelp" readonly>
                                                                                <option value="Cash"{{$withdrawRequestList->request_payment_by == 'Cash' ? 'selected' : ''}}>Cash</option>
                                                                                <option value="BKash"{{$withdrawRequestList->request_payment_by == 'BKash' ? 'selected' : ''}}>BKash</option>
                                                                                <option value="Rocket"{{$withdrawRequestList->request_payment_by == 'Rocket' ? 'selected' : ''}}>Rocket</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if($withdrawRequestList->request_payment_by != 'Cash')
                                                                    <div class="form-group row">
                                                                        <label for="request_point" class="col-sm-5 col-form-label">Payment By Number</label>
                                                                        <div class="col-sm-5">
                                                                            <input type="text" name="payment_by_number" value="{{$withdrawRequestList->payment_by_number}}" class="form-control field-validate" id="request_point" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="request_point" class="col-sm-5 col-form-label">Transaction ID</label>
                                                                        <div class="col-sm-5">
                                                                            <input type="text" name="transaction_id" value="" class="form-control field-validate" id="transaction_id">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">

                                                                <button type="submit" class="btn btn-danger" id="deleteCoupanBtn">Pay </button>
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>

                                                            </div>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--  row -->


                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8"><strong>{{ trans('labels.NoRecordFound') }}</strong></td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="col-xs-12 text-right">
                                        {!! $withdrawRequestLists->appends(\Request::except('page'))->render() !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
