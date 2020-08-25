<!-- Products content -->
@if(!empty($result['commonContent']['categories']))
<section class="products-content">
  <div class="container">
    <div class="products-area category-area">
      <!-- heading -->
      <div class="heading">
        <h2>@lang('website.Categories')</h2>
        <hr class="mb-4">
      </div>
      <div class="row">
        <!-- categories -->
        <?php $counter = 0;?>
        @foreach($result['commonContent']['categories'] as $categories_data)
                @if($counter<=7)
                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                  <!-- categories -->
                  <div class="product">
                      {{--<article>
                        <div class="module">
                          <div class="cat-thumb">
                              <img class="img-fluid" src="{{asset('').$categories_data->path}}" alt="{{$categories_data->name}}">
                          </div>
                          <a href="{{ URL::to('/shop?category='.$categories_data->slug)}}" class="cat-title">
                            {{$categories_data->name}}
                          </a>
                        </div>
                      </article>--}}
                      <article style="padding: 0px !important">
                          <div class="module">
                              {{--<div class="cat-thumb">
                                  <img class="img-fluid" src="{{asset('').$categories_data->path}}" alt="{{$categories_data->name}}">
                              </div>--}}
                              <a href="{{ URL::to('/shop/category'.'/'.$categories_data->slug)}}" class="cat-title">
                                  {{$categories_data->name}}
                              </a>
                          </div>
                      </article>
                  </div>
                </div>
                @endif
                <?php $counter++;?>
        @endforeach

      </div>
    </div>


  </div>
</section>
@endif
