	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<?php $result['setting'] = DB::table('settings')->get(); ?>
    @if(empty($result['setting'][18]->value))
    <title>@lang('website.Ecommerce')</title>
    @else
    <title>@yield('dynamic_title')<?=stripslashes($result['setting'][18]->value)?></title>
    @endif

    @if(!empty($result['setting'][86]->value))
    <link rel="icon" href="{{asset('').$result['setting'][86]->value}}" type="image/gif">
    @endif
    <meta name="DC.title"  content="<?=stripslashes($result['setting'][73]->value)?>"/>
    <meta name="description" content="<?=stripslashes($result['setting'][75]->value)?>"/>
    <meta name="keywords" content="<?=stripslashes($result['setting'][74]->value)?>"/>

	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- For SEO -->
    <meta name="google-site-verification" content="sxjq3NJEhqqy02xEYxqHFOfiNZpQ3jgBTvT7uIglkss" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-137704178-8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-137704178-8');
    </script>
    <!-- For SEO -->

	    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,800">

	    <!--<link rel='stylesheet' href="web/css/fontawesome.css">-->
	    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

	    <link rel="stylesheet" href="{{asset('web/css').'/'.$result['setting'][81]->value}}.css">
			<link rel="stylesheet" href="{{asset('web/css/custom.css') }}">
	    <link rel="stylesheet" href="{{asset('web/css/owl.carousel.min.css') }}">
	    <link rel="stylesheet" href="{{asset('web/css/owl.theme.default.min.css') }}">

				<link rel="stylesheet" href="{{asset('web/css/slick.css') }}">
			<link rel="stylesheet" href="{{asset('web/css/slick-theme.css') }}">

	    <link rel="stylesheet" href="{{asset('web/css/responsive.css') }}">
	    <link rel="stylesheet" href="{{asset('web/css/rtl.css')}}">

	    <link rel="stylesheet" href="{{asset('web/api/fancybox/source/jquery.fancybox.css')}}"  media="all"/>

			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
			<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <!--------- stripe js ------>
	<script src="https://js.stripe.com/v3/"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('web/css/stripe.css') }}" data-rel-css="" />

    <!------- paypal ---------->
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
		<script src="https://checkout.razorpay.com/v1/checkout.js"></script>


    <!---- onesignal ------>
    @if($result['setting'][54]->value=='onesignal')
	<link rel="manifest" href="{!! asset('onesignal/manifest.json') !!}" />
	<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
	<script>
    var OneSignal = window.OneSignal || [];
      OneSignal.push(function() {
		  //push here
      });

	//onesignal
	OneSignal.push(["init", {
	  appId: "{{$result['setting'][55]->value}}",
	 // safari_web_id: oneSignalSafariWebId,
	  persistNotification: false,
	  notificationClickHandlerMatch: 'origin',
	  autoRegister: false,
	  notifyButton: {
	   enable: false
	  }
	 }]);

    </script>
    @endif

    @if(!empty($result['setting'][76]->value))
		<?=stripslashes($result['setting'][76]->value)?>
    @endif
