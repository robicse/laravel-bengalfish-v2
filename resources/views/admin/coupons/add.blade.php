@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> {{ trans('labels.AddCoupons') }} <small>{{ trans('labels.AddCoupons') }}...</small> </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/coupons/display')}}"><i class="fa fa-tablet"></i>{{ trans('labels.ListingAllCoupons') }}</a></li>
                <li class="active">{{ trans('labels.AddCoupons') }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->

            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">{{ trans('labels.AddCoupons') }}</h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    @if (count($errors) > 0)
                                        @if($errors->any())
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                {{$errors->first()}}
                                            </div>
                                        @endif
                                    @endif

                                    @if(Session::has('success'))
                                        <div class="alert alert-success alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            {!! session('success') !!}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box box-info"><br>
                                        @if(count($result['message'])>0)
                                            <div class="alert alert-success alert-dismissible" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                {{ $result['message'] }}
                                            </div>
                                        @endif
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">

                                            {!! Form::open(array('url' =>'admin/coupons/insert', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Coupon') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('code',  '', array('class'=>'form-control field-validate', 'id'=>'code'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.AddCouponsTaxt') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.AddCouponsTaxt') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CouponDescription') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::textarea('description',  '', array('class'=>'form-control', 'rows'=>'5', 'id'=>'description'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                	                {{ trans('labels.CouponDescriptionText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Discounttype') }}  </label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="discount_type" class='form-control'>
                                                        <option value="fixed_cart" selected>Cart Discount</option>
                                                        <option value="percent">Cart % Discount</option>
                                                        {{--<option value="fixed_product">Product Discount</option>
                                                        <option value="percent_product">Product % Discount</option>--}}
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                	                {{ trans('labels.DiscounttypeText') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CouponAmount') }}
                                                </label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('amount',  '0', array('class'=>'form-control field-validate', 'id'=>'amount'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                	                {{ trans('labels.CouponAmountText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group" style="display: none">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Products') }}</label>
                                                <div class="col-sm-10 col-md-4 couponProdcuts">
                                                    <select name="product_ids[]" multiple class="form-control select2 ">
                                                        @foreach($result['products'] as $products)
                                                            <option value="{{ $products->products_id }}">{{ $products->products_name }} {{ $products->products_model }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CouponProductsUsed') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group" style="display: none">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.IncludeCategories') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select name="product_categories[]" multiple class="form-control select2">
                                                        @foreach($result['categories'] as $categories)
                                                            <option value="{{ $categories->categories_id }}">{{ $categories->categories_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.IncludeCategoriesText') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.CouponExpiryDate') }}</label>
                                                <div class="col-sm-10 col-md-4">
                                                    {!! Form::text('expiry_date',  '', array('class'=>'form-control field-validate datepicker', 'id'=>'datepicker', 'readonly'=>'readonly'))!!}
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.CouponExpiryDateText') }}</span>
                                                    <span class="help-block hidden">{{ trans('labels.CouponExpiryDateText') }}</span>
                                                </div>
                                            </div>

                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ URL::to('admin/coupons/display')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                                            </div>
                                            <!-- /.box-footer -->
                                            {!! Form::close() !!}
                                        </div>
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

            <!-- Main row -->

            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
