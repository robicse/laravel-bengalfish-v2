<!-- Products content -->
<link href="https://fonts.googleapis.com/css?family=Oswald|Roboto+Condensed&display=swap" rel="stylesheet">
<style>

    .cate{
        background-color: #fff;
        padding: 15px 24px 15px 24px;
        border-radius: 10px;
        -webkit-box-shadow: 2px 2px 10px -3px rgba(20,20,20,0.3);
        -moz-box-shadow: 2px 2px 10px -3px rgba(20,20,20,0.3);
        box-shadow: 2px 2px 10px -3px rgba(20,20,20,0.3);
        background-image: url("/images/media/2020/fish.png");
    }
    .cate :hover{
        transform: scale(1.1);
        transition: 0.2s ease-in-out;
        text-decoration: none;
    }
    .a{
        color: #00bfff;
        font-size: 19px;
        font-family: 'Roboto Condensed';
    }
    .a :hover{
        color: #00bfff;

    }

    @media only screen and (max-width: 576px) {
        .cate{
        padding: 20px 24px 20px 24px;
        border-radius: 10px;
        background-image: url("/images/media/2020/fishM.png");

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
        <h2>@lang('website.Categories')</h2>
        <hr class="mb-4">
      </div>
      <div class="row">
        <!-- categories -->
        <?php $counter = 0;?>
         <?php
         $fm_location=$_SERVER['DOCUMENT_ROOT'];
         //echo":: $fm_location ::<br/>";
         //include($fm_location."/app/Models/Core/Categories.php");
         //echo $fm_location."/app/Models/Core/Categories.php";
         //$myVar = new Categories();
		//$categories = $myVar->getter(1);
         ?>
        @foreach($result['commonContent']['categories'] as $key=>$categories_data)
                @if($counter<=7)
                <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3 text-center">
                  <!-- categories -->
                      <!-- <div class="cate" style="">-->
                          <div class="product">
                              <article>
                                   <img class="img-fluid-cat" alt="{{$categories_data->name}}" src="{{asset($categories_data->path)}}">
                                  <div class="thumb">
                                <!--    <img class="img-fluid" alt="{{$categories_data->name}}" src="{{asset($categories_data->path)}}"> -->
                                     
                                      
                                      <?php
//$category->id
                                     // echo":: $categories_data[0] ::";
                                     ///echo"(:: $categories_data->path :: )";
                                      ?>
                                     
                                     
                                  </div>      
                              </article>      
                              <h2 class="title text-center">
<a class="a" href="{{ URL::to('/shop/category'.'/'.$categories_data->slug)}}" class="cat-title">
                                  {{$categories_data->name}}
                              </a>
</h2>
                          </div>
                          <!--
                          <div class="">
                              <a class="a" href="{{ URL::to('/shop/category'.'/'.$categories_data->slug)}}" class="cat-title">
                                  {{$categories_data->name}}
                              </a>
                          </div>
                          -->
                     <!-- </div>-->
                </div>
                @endif
                <?php $counter++;?>
        @endforeach

      </div>
    </div>


  </div>
</section>
@endif