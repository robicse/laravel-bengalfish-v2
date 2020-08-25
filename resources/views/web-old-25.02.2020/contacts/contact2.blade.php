<!-- contact Content -->
<section class="contact-content contact-two-content">
  <div class="container">
    <div class="row">
      <div class="col-12 col-sm-12">
          <div class="row justify-content-end">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>

                  <li class="breadcrumb-item active" aria-current="page">@lang('website.Contact Us')</li>
                </ol>
              </nav>
          </div>
      </div>
      <div class="col-12 col-sm-12">
        <div class="row">
            <div class="col-12 col-lg-8">
              <div class="form-start">
                @if(session()->has('success') )
                   <div class="alert alert-success">
                       {{ session()->get('success') }}
                   </div>
                @endif
                  <form enctype="multipart/form-data" action="{{ URL::to('/processContactUs')}}" method="post">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">

                    <label class="first-label" for="email">@lang('website.Full Name')</label>
                    <div class="input-group">

                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" id="name" name="name" placeholder="@lang('website.Please enter your name')" aria-describedby="inputGroupPrepend" required>
                        <span class="help-block error-content" hidden>@lang('website.Please enter your name')</span>
                    </div>
                    <label for="email">@lang('website.Email')</label>
                    <div class="input-group">

                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupPrepend"><i class="fas fa-at"></i></span>
                        </div>
                        <input type="email"  name="email" class="form-control" id="validationCustomUsername" placeholder="Enter Email here.." aria-describedby="inputGroupPrepend" required>
                        <span class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</span>

                    </div>
                    <label for="email">@lang('website.Message')</label>
                    <textarea type="text" name="message"  placeholder="write your message here..." rows="5" cols="56"></textarea>
                    <span class="help-block error-content" hidden>@lang('website.Please enter your message')</span>
                    <button type="submit" class="btn btn-secondary">@lang('website.Send') <i class="fas fa-location-arrow"></i></button>
                  </form>
              </div>
          </div>
          <div class="col-12 col-lg-4 contact-main">
            <div class="row">
              <div class="col-6">

                  <ul class="contact-logo pl-0 mb-0">
                    <li> <i class="fas fa-mobile-alt"></i><br>CONTACT US</li>
                    <li> <i class="fas fa-map-marker"></i><br>ADDRESS</li>
                    <li> <i class="fas fa-envelope"></i><br>EMAIL ADDRESS </li>
                    <li> <i class="fas fa-tty"></i><br>FAX</li>
                  </ul>
              </div>
              <div class="col-6 right">
                <ul class="contact-info  pl-0 mb-0">
                  <li><font>
                    <a href="#">{{$result['commonContent']['setting'][11]->value}}</a><br> <a href="#">{{$result['commonContent']['setting'][11]->value}}</a>
                  </font> </li>
                  <li> <font><a href="#">{{$result['commonContent']['setting'][4]->value}} <br>{{$result['commonContent']['setting'][5]->value}} {{$result['commonContent']['setting'][6]->value}}, {{$result['commonContent']['setting'][7]->value}} {{$result['commonContent']['setting'][8]->value}}</a></font></li>
                  <li> <font><a href="mailto:{{$result['commonContent']['setting'][3]->value}}">{{$result['commonContent']['setting'][3]->value}}</a><br><a href="#">{{$result['commonContent']['setting'][3]->value}}</a> </font></li>
                  <li><font><a href="#">{{$result['commonContent']['setting'][11]->value}}</a><br><a href="#">{{$result['commonContent']['setting'][11]->value}}</a> </font></li>
                </ul>
              </div>
            </div>


             <p style="margin-top:30px;">
              {{$result['commonContent']['setting'][112]->value}}
             </p>
          </div>


        </div>
      </div>
    </div>

  </div>
</section>
