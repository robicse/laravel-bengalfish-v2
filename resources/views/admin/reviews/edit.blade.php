@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1> Edit </h1>
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
                <li><a href="{{ URL::to('admin/reviews/display')}}"><i class="fa fa-dashboard"></i> {{ trans('labels.ListingAllNews') }}</a></li>
                <li class="active">Edit Reviews</li>
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
                            <h3 class="box-title">Edit Reviews </h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="box box-info">
                                        <!--<div class="box-header with-border">
                                          <h3 class="box-title">Edit News</h3>
                                        </div>-->
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">
                                            @if( count($errors) > 0)
                                                @foreach($errors->all() as $error)
                                                    <div class="alert alert-success" role="alert">
                                                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                        <span class="sr-only">{{ trans('labels.Error') }}:</span>
                                                        {{ $error }}
                                                    </div>
                                                @endforeach
                                            @endif

                                            {!! Form::open(array('url' =>'admin/reviews/update', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}

                                            {!! Form::hidden('id',  $result['reviews']->reviews_id, array('class'=>'form-control', 'id'=>'id')) !!}


                                            <div class="form-group">
                                                <label for="reviews_text" class="col-sm-2 col-md-3 control-label">Reviews Text</label>
                                                <div class="col-sm-10 col-md-8">
                                                    <textarea id="" name="reviews_text" class="form-control"  rows="10" cols="80">{{ stripslashes($result['reviews']->reviews_text) }}</textarea>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Reviews Text</span>
                                                    <br>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="reviews_rating" class="col-sm-2 col-md-3 control-label">Reviews Rating</label>
                                                <div class="col-sm-10 col-md-4">
                                                    <select class="form-control" name="reviews_rating">
                                                        <option value="5" @if($result['reviews']->reviews_rating==5) selected @endif >5</option>
                                                        <option value="4" @if($result['reviews']->reviews_rating==4) selected @endif>4</option>
                                                        <option value="3" @if($result['reviews']->reviews_rating==3) selected @endif>3</option>
                                                        <option value="2" @if($result['reviews']->reviews_rating==2) selected @endif>2</option>
                                                        <option value="1" @if($result['reviews']->reviews_rating==1) selected @endif>1</option>
                                                    </select>
                                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">Active status will be displayed on user side.</span>
                                                </div>
                                            </div>
                                            <!-- /.box-body -->
                                            <div class="box-footer text-center">
                                                <button type="submit" class="btn btn-primary">{{ trans('labels.Submit') }}</button>
                                                <a href="{{ URL::to('admin/news/display')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
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
    <script src="{!! asset('admin/plugins/jQuery/jQuery-2.2.0.min.js') !!}"></script>
    <script type="text/javascript">
        $(function () {

            //for multiple languages

            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor');



            //bootstrap WYSIHTML5 - text editor
            $(".textarea").wysihtml5();

        });
    </script>
@endsection
