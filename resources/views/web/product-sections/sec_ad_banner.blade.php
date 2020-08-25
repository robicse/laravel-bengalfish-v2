<section class="products-content">
  <div class="container">
    <!-- Banner -->
  @if(count($result['commonContent']['homeBanners'])>0)
   @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
      @if($homeBanners->type==39)
    <div class="full-width-banner">
      <div class="row">
        <div class="col-12 col-sm-12">
          <figure class="banner-image">
            <a href="{{ $homeBanners->banners_url}}">
              <img class="img-fluid" src="{{asset('').$homeBanners->path}}" alt="Full Width Banner">
            </a>
          </figure>
        </div>
      </div>
    </div>
    @endif
   @endforeach
  @endif
</div>
</section>
