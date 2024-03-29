@extends('admin.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.StatsProductsPurchased') }} <small>{{ trans('labels.StatsProductsPurchased') }}...</small> </h1>
    <ol class="breadcrumb">
      <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li class="active">{{ trans('labels.StatsProductsPurchased') }}</li>
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
            <h3 class="box-title">{{ trans('labels.StatsProductsPurchased') }} </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>{{ trans('labels.ID') }}</th>
                      <th>{{ trans('labels.Image') }}</th>
                      <th>{{ trans('labels.Products') }}</th>
                      <th>{{ trans('labels.PurchasedDate') }}</th>
                      <th>{{ trans('labels.UpdatedDate') }}</th>
                      <th>{{ trans('labels.Qty') }}</th>
                      <th>{{ trans('labels.View') }}</th>
                    </tr>
                  </thead>
                   <tbody>
                    @foreach ($result['data'] as  $key=>$products)
                        @php
                        /*echo '<pre>';
                        print_r($products);
                        $products_image_id =$products->products_image;

                        echo '</pre>';*/
                        @endphp
                        <tr>
                            <td>{{ ++$key }}</td>
                            {{--<td><img src="{{asset('').'/'.$products->products_image}}" alt="" width=" 100px" height="100px"></td>--}}
                            <td><img src="{{asset($products->path)}}" alt="" width=" 100px" height="100px"></td>
                            <td>
                            	<strong>{{ $products->products_name }}
                                @if(!empty($products->products_model))
                                ( {{ $products->products_model }} )
                                @endif
                                </strong><br>
                            </td>
                            <td align="center">
                            	{{ $products->products_date_added }}
                                <?php
									//$date = new DateTime($products->products_date_added);
									//echo $myDate = $date->format('d-m-Y');
									//print $myDate;
                                    //echo date('Y-m-d', strtotime($products->products_date_added));



                                    //$date = \Carbon\Carbon::parse('2016-11-01 15:04:19');
                                    //echo $date->format('Y-m-d');
								?>
                            </td>

                            <td align="center">
                                {{ $products->products_date_added }}
                                <?php
									/*if(!empty($products->products_last_modified)){
										$date = new DateTime($products->products_last_modified);
										echo $myDate = $date->format('d-m-Y');
										//print $myDate;
									}else{
										print "----";
									}*/
								?>
                            </td>

                            <td align="center">
                            	{{--{{ $result['currency'][19]->value }}--}}{{ $products->products_quantity }}
                            </td>

                            <td>
                            	<a href="{{url('admin/products/edit')}}/{{ $products->products_id }}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="col-xs-12 text-right">
                	{{$result['data']->links()}}
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
