<!-- Products content -->
<link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed&display=swap" rel="stylesheet">
<style>

    .cate{
        background-color: #fff;
        padding: 55px 25px 55px 25px;
        border-radius: 10px;
        -webkit-box-shadow: 2px 2px 10px -3px rgba(20,20,20,0.3);
        -moz-box-shadow: 2px 2px 10px -3px rgba(20,20,20,0.3);
        box-shadow: 2px 2px 10px -3px rgba(20,20,20,0.3);

    }
    .catimg :hover{
        transform: scale(1.1);
        transition: 0.3s ease;
        text-decoration: none;
    }
    .a{
        color: #090a0a;
        font-size: 22px;
        font-family: 'Roboto Condensed';
        font-weight: bold;
    }
    .a :hover{
        color: #00bfff;
    }
    .heading h2 {
        font-size: 1.25rem;
        /*font-weight: 600;*/
        margin-bottom: 12px;
        text-transform: uppercase;
        display: flex;
        justify-content: center;
        background: radial-gradient(circle, rgba(0,0,0,1) 49%, rgba(37,37,37,1) 100%);
        padding: 9px 0px;
        color: rgba(248,158,32,1);
    }

    @media only screen and (max-width: 576px) {
        .cate{
            /*padding: 20px 24px 20px 24px;*/
            /*border-radius: 10px;*/
            /*background-image: url("/images/media/2020/fishM.png");*/

        }
        .a{
            font-size: 21px;
        }
    }

</style>
@if(!empty($result['commonContent']['categories']))
    <section class="products-content mb-5">
        <div class="container">
            <div class="products-area category-area">
                <!-- heading -->
                <div class="heading">
{{--                    <h2>@lang('website.Categories')</h2>--}}
                    <h2>Our Categories</h2>
{{--                    <hr class="mb-4">--}}
                </div>
                <div class="row">
                    <!-- categories -->
                    <?php $counter = 0;?>
                    @foreach($result['commonContent']['categories'] as $categories_data)
                        {{--        @dd($categories_data)--}}
                        @if($counter<=7)
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3 text-center">
                                <a href = "{{ URL::to('/shop/category'.'/'.$categories_data->slug)}}">
                                    <div class="px-2 py-1 catimg">
                                        <img src = "{{asset($categories_data->path)}}" alt = "" width="280px">
                                    </div>
                                </a>
                                <!-- categories -->
                                {{--                      <div class="cate" style=" background-image:url({{asset($categories_data->path)}}) ">--}}
                                {{--                          <div class="">--}}
                                {{--                              <a class="a" href="{{ URL::to('/shop/category'.'/'.$categories_data->slug)}}" class="cat-title">--}}
                                {{--                                  {{$categories_data->name}}--}}
                                {{--                              </a>--}}
                                {{--                          </div>--}}
                                {{--                      </div>--}}
                            </div>
                        @endif
                        <?php $counter++;?>
                    @endforeach

                </div>
            </div>


        </div>
    </section>
@endif
