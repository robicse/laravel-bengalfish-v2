@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> Withdraw Category </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li class="active"> Withdraw Category</li>
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
                            <h3 class="box-title">Withdraw Category Lists </h3>

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
{{--                                <a href="{{ URL::to('admin/withdraw_category/add')}}" type="button" class="btn btn-block btn-primary">{{ trans('labels.AddNew') }}</a>--}}
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
                                            <th>One Point To TK</th>
                                            <th>Minimum Withdraw Point</th>
                                            <th>Per Month Withdraw Point Limit</th>
{{--                                            <th>{{ trans('labels.Action') }}</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($withdrawCategories !== null)
                                            @foreach ($withdrawCategories as $key=>$withdrawCategory)

                                                <tr>
                                                    <td>{{ $withdrawCategory->id }}</td>
                                                    <td>{{ $withdrawCategory->one_point_to_tk }}</td>
                                                    <td>{{ $withdrawCategory->minimum_withdraw_point }}</td>
                                                    <td>{{ $withdrawCategory->per_month_withdraw_point_limit }}</td>
{{--                                                    <td>--}}
{{--                                                        <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Edit') }}" href="{{ url('admin/withdraw_category/edit')}}/{{$withdrawCategory->id}}" class="badge bg-light-blue"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>--}}
{{--                                                        <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('labels.Delete') }}" id="deleteCoupans_id" coupans_id ="{{ $withdrawCategory->id }}" class="badge bg-red"><i class="fa fa-trash" aria-hidden="true"></i></a>--}}
{{--                                                    </td>--}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8"><strong>{{ trans('labels.NoRecordFound') }}</strong></td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="col-xs-12 text-right">
                                        {!! $withdrawCategories->appends(\Request::except('page'))->render() !!}

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
            <!-- deleteCoupanModal -->
            <div class="modal fade" id="deleteCoupanModal" tabindex="-1" role="dialog" aria-labelledby="deleteCoupanModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deleteCoupanModalLabel">{{ trans('labels.DeleteCoupon') }}</h4>
                        </div>
                        {!! Form::open(array('url' =>'admin/withdraw_category/delete', 'name'=>'deleteCoupan', 'id'=>'deleteCoupan', 'method'=>'post', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data')) !!}
                        {!! Form::hidden('action',  'delete', array('class'=>'form-control')) !!}
                        {!! Form::hidden('id',  '', array('class'=>'form-control', 'id'=>'coupans_id')) !!}
                        <div class="modal-body">
                            <p>{{ trans('labels.DeleteCouponText') }}</p>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-danger" id="deleteCoupanBtn">{{ trans('labels.Delete') }} </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('labels.Close') }}</button>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            <!--  row -->

            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
