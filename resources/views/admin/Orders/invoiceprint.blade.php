@extends('admin.layout')
<style>
.wrapper.wrapper2{
	display: block;
}
.wrapper{
	display: none;
}
</style>
<body onload="window.print();">
<div class="wrapper wrapper2">
  <!-- Main content -->
  <section class="invoice" style="margin: 15px;">
      <!-- title row -->
      <div class="col-xs-12">
      <div class="row">
       @if(session()->has('message'))
      	<div class="alert alert-success alert-dismissible">
           <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
           <h4><i class="icon fa fa-check"></i> {{ trans('labels.Successlabel') }}</h4>
            {{ session()->get('message') }}
        </div>
        @endif
        @if(session()->has('error'))
        	<div class="alert alert-warning alert-dismissible">
               <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
               <h4><i class="icon fa fa-warning"></i> {{ trans('labels.WarningLabel') }}</h4>
                {{ session()->get('error') }}
            </div>
        @endif


       </div>
      </div>
      <div class="row">
          <div class="col-xs-12" style="text-align: center">
              <img src="../../dist/img/logo.png" alt="Logo">
              <h5>www.bengalfish.com.bd</h5>
              <h5>Contact: 01311-154001</h5>
          </div>
        <div class="col-xs-12">
          <h2 class="page-header" style="padding-bottom: 25px">
            <i class="fa fa-globe"></i> {{ trans('labels.OrderID') }}# {{ $data['orders_data'][0]->orders_id }}
            <small class="pull-right">{{ trans('labels.OrderedDate') }}: {{ date('m/d/Y', strtotime($data['orders_data'][0]->date_purchased)) }}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          {{ trans('labels.CustomerInfo') }}:
          <hr>
            <address>
            <strong>{{ $data['orders_data'][0]->customers_name }}</strong><br>
            {{ $data['orders_data'][0]->customers_street_address }} <br>
            {{ $data['orders_data'][0]->customers_city }}, {{ $data['orders_data'][0]->customers_state }} {{ $data['orders_data'][0]->customers_postcode }}, {{ $data['orders_data'][0]->customers_country }}<br>
            {{ trans('labels.Phone') }}: {{ $data['orders_data'][0]->customers_telephone }}<br>
            {{ trans('labels.Email') }}: {{ $data['orders_data'][0]->email }}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          {{ trans('labels.ShippingInfo') }}
          <hr>
          <address>
            <strong>{{ $data['orders_data'][0]->delivery_name }}</strong><br>
            {{ trans('labels.Phone') }}: {{ $data['orders_data'][0]->delivery_phone }}<br>
            {{ $data['orders_data'][0]->delivery_street_address }} <br>
            {{ $data['orders_data'][0]->delivery_city }}, {{ $data['orders_data'][0]->delivery_state }} {{ $data['orders_data'][0]->delivery_postcode }}, {{ $data['orders_data'][0]->delivery_country }}<br>
           <strong> {{ trans('labels.ShippingMethod') }}:</strong> {{ $data['orders_data'][0]->shipping_method }} <br>
           <strong> {{ trans('labels.ShippingCost') }}:</strong> @if(!empty($data['orders_data'][0]->shipping_cost)) {{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->shipping_cost }} @else --- @endif <br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
         {{ trans('labels.BillingInfo') }}
          <hr>
          <address>
            <strong>{{ $data['orders_data'][0]->billing_name }}</strong><br>
            {{ trans('labels.Phone') }}: {{ $data['orders_data'][0]->billing_phone }}<br>
            {{ $data['orders_data'][0]->billing_street_address }} <br>
            {{ $data['orders_data'][0]->billing_city }}, {{ $data['orders_data'][0]->billing_state }} {{ $data['orders_data'][0]->billing_postcode }}, {{ $data['orders_data'][0]->billing_country }}<br>
          </address>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


      <div style="height: 10px !important;">&nbsp;</div>

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
                <th>SL NO</th>
              <th>{{ trans('labels.ProductName') }}</th>
                <th>{{ trans('labels.Qty') }}</th>
              {{--<th>{{ trans('labels.ProductModal') }}</th>
              <th>{{ trans('labels.Options') }}</th>--}}
              <th>{{ trans('labels.Price') }}</th>
                <th style="float: right;margin-right: 40px;">Sub Total</th>
            </tr>
            </thead>
            <tbody>
            @php
                $sub_total = 0;
            @endphp
            @foreach($data['orders_data'][0]->data as $key => $products)

            <tr>
                <td>{{$key + 1}}</td>
                <td  width="30%">
                    {{  $products->products_name }}<br>
                </td>
                <td>{{  $products->products_quantity }}</td>
                {{--<td>
                    {{  $products->products_model }}
                </td>
                <td>
                @foreach($products->attribute as $attributes)
                	<b>{{ trans('labels.Name') }}:</b> {{ $attributes->products_options }}<br>
                    <b>{{ trans('labels.Value') }}:</b> {{ $attributes->products_options_values }}<br>
                    <b>{{ trans('labels.Price') }}:</b> {{ $data['currency'][19]->value }}{{ $attributes->options_values_price }}<br>

                @endforeach</td>--}}

                <td>{{ $data['currency'][19]->value }}{{ $products->products_price }}</td>
                <td style="float: right;margin-right: 40px;">{{ $data['currency'][19]->value }}{{ $products->products_quantity*$products->products_price }}</td>
                @php
                    $sub_total += $products->products_quantity*$products->products_price;
                @endphp
             </tr>
            @endforeach

            </tbody>
          </table>
        </div>
        <!-- /.col -->

      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-7">
          <p class="lead" style="margin-bottom:10px">{{ trans('labels.PaymentMethods') }}:</p>
          <p class="text-muted well well-sm no-shadow" style="text-transform:capitalize">
           	{{ str_replace('_',' ', $data['orders_data'][0]->payment_method) }}
          </p>
          @if(!empty($data['orders_data'][0]->coupon_code))
              <p class="lead" style="margin-bottom:10px">{{ trans('labels.Coupons') }}:</p>
                <table class="text-muted well well-sm no-shadow stripe-border table table-striped" style="text-align: center; ">
                	<tr>
                        <th style="text-align: center; ">{{ trans('labels.Code') }}</th>
                        <th style="text-align: center; ">{{ trans('labels.Amount') }}</th>
                    </tr>
{{--                	@foreach( json_decode($data['orders_data'][0]->coupon_code) as $couponData)--}}
{{--                    	<tr>--}}
{{--                        	<td>{{ $couponData->code}}</td>--}}
{{--                            <td>{{ $couponData->amount}}--}}

{{--                                @if($couponData->discount_type=='percent_product')--}}
{{--                                    ({{ trans('labels.Percent') }})--}}
{{--                                @elseif($couponData->discount_type=='percent')--}}
{{--                                    ({{ trans('labels.Percent') }})--}}
{{--                                @elseif($couponData->discount_type=='fixed_cart')--}}
{{--                                    ({{ trans('labels.Fixed') }})--}}
{{--                                @elseif($couponData->discount_type=='fixed_product')--}}
{{--                                    ({{ trans('labels.Fixed') }})--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
                    <tr>
                        <td>{{ $data['orders_data'][0]->coupon_code}}</td>
                        <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->coupon_amount}}</td>
                    </tr>
				</table>
          @endif
            <p class="lead" style="margin-bottom:10px">Order Note:</p>
            @if(count($data['orders_status_history']) > 0)
                @foreach( $data['orders_status_history'] as $orders_status_history)
                <p class="lead" style="margin-bottom:10px">
                    {{$orders_status_history->comments}}
                </p>
                @endforeach
            @endif
        </div>
        <!-- /.col -->
        <div class="col-xs-5">
          <!--<p class="lead"></p>-->

          <div class="table-responsive ">
            <table class="table order-table">
              <tr>
                <th style="width:50%">{{ trans('labels.Subtotal') }}:</th>
{{--                <td>{{ $data['currency'][19]->value }}{{ $data['subtotal'] }}</td>--}}
                <td>{{ $data['currency'][19]->value }}{{ $sub_total }}</td>
              </tr>
              {{--<tr>
                <th>{{ trans('labels.Tax') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->total_tax }}</td>
              </tr>--}}
              <tr>
                <th>{{ trans('labels.ShippingCost') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->shipping_cost }}</td>
              </tr>
              @if(!empty($data['orders_data'][0]->coupon_code))
              <tr>
                <th>{{ trans('labels.DicountCoupon') }}:</th>
                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->coupon_amount }}</td>
              </tr>
              @endif
              <tr>
                <th>{{ trans('labels.Total') }}:</th>
{{--                <td>{{ $data['currency'][19]->value }}{{ $data['orders_data'][0]->order_price }}</td>--}}
                  <td>{{ $data['currency'][19]->value }}{{ ($sub_total + $data['orders_data'][0]->shipping_cost) - $data['orders_data'][0]->coupon_amount}}</td>
              </tr>
            </table>
          </div>

        </div>
        {{--<div class="col-xs-12">
        	<p class="lead" style="margin-bottom:10px">{{ trans('labels.Orderinformation') }}:</p>
        	<p class="text-muted well well-sm no-shadow" style="text-transform:capitalize; word-break:break-all;">
            @if(trim($data['orders_data'][0]->order_information) != '[]' and !empty($data['orders_data'][0]->order_information))
           		{{ $data['orders_data'][0]->order_information }}
            @else
           		---
            @endif
            </p>
        </div>--}}

        <!-- /.col -->
          {{--<div class="col-xs-12">
              <p><strong>Address:</strong> Bengel Fish (Dhanmondi Zone) Plot No 03, Road No 03, Block B, Dhaka Uddan Housing, Mohammedpur, Dhaka.</p>
          </div>--}}
      </div>
      <!-- /.row -->

      <div class="row" style="margin-top: 100px;margin-left: 20px; margin-right: 40px">
          <div class="col-md-12">
              <div class="col-sm-6" style="float: left;">
                  <hr/>
                  Office Signature
              </div>
              <!-- /.col -->
              <div class="col-sm-6" style="float: right;">
                  <hr/>
                  Customer Signature
              </div>
          </div>
          <!-- /.col -->
      </div>


    </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>

