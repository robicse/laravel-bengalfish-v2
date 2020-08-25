@if(count($result['commonContent']['homeBanners'])>0)
 @foreach(($result['commonContent']['homeBanners']) as $homeBanners)
    @if($homeBanners->type==40)
      <section class="full-screen-banner">
        <div class="container">
            <div class="row">
                <figure class="banner-image">
                    <a href="{{ $homeBanners->banners_url}}">
                      <img src="{{asset('').$homeBanners->path}}" width="100%" alt="Fullscreen Banner">
                    </a>
                  </figure>
            </div>
        </div>


      </section>
      @endif
     @endforeach
    @endif
