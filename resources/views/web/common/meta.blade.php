<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
@php
    $result['setting'] = DB::table('settings')->get();
    $first_segment = request()->segment(1);
    $second_segment = request()->segment(2);


    if( ($first_segment == 'product-detail' && $second_segment != '') && $result['detail']['product_data'][0]){
      //dd($result['detail']);
      $meta_title = $result['detail']['product_data'][0]->meta_title;
      $meta_description = $result['detail']['product_data'][0]->meta_description;
    }else{
        $meta_title = '';
        $meta_description = '';
    }

@endphp

@if($first_segment == '')
    <title><?=stripslashes($result['setting'][118]->value)?> <?=stripslashes($result['setting'][18]->value)?></title>
@elseif($first_segment != '')
    <title>@yield('dynamic_title') <?=stripslashes($result['setting'][18]->value)?></title>
@elseif($first_segment == 'product-detail' && $second_segment != '')
    <title>@yield('dynamic_title') <?=$meta_title?></title>
@endif

@if(!empty($result['setting'][86]->value))
    <link rel="icon" href="{{asset('').$result['setting'][86]->value}}" type="image/gif">
@endif
<meta name="DC.title"  content="<?=stripslashes($result['setting'][73]->value)?>"/>

@if($first_segment == '')
    <meta name="description" content="<?=stripslashes($result['setting'][119]->value)?>"/>
@elseif($first_segment == 'product-detail' && $second_segment != '')
    <meta name="description" content="<?=stripslashes($meta_description)?>"/>
@else
    <meta name="description" content="@yield('dynamic_description') : <?=stripslashes($result['setting'][119]->value)?>"/>
@endif

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

<!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '164883161788401');
    fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
               src="https://www.facebook.com/tr?id=164883161788401&ev=PageView&noscript=1"
    /></noscript>
<!-- End Facebook Pixel Code -->

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
{{--<script src="https://js.stripe.com/v3/"></script>--}}

{{--<link rel="stylesheet" type="text/css" href="{{asset('web/css/stripe.css') }}" data-rel-css="" />--}}

<!------- paypal ---------->
{{--<script src="https://www.paypalobjects.com/api/checkout.js"></script>--}}
{{--<script src="https://checkout.razorpay.com/v1/checkout.js"></script>--}}


<!---- onesignal ------>
{{--@if($result['setting'][54]->value=='onesignal')--}}
{{--    <link rel="manifest" href="{!! asset('onesignal/manifest.json') !!}" />--}}
{{--    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>--}}
{{--    <script>--}}
{{--        var OneSignal = window.OneSignal || [];--}}
{{--        OneSignal.push(function() {--}}
{{--            //push here--}}
{{--        });--}}

{{--        //onesignal--}}
{{--        OneSignal.push(["init", {--}}
{{--            appId: "{{$result['setting'][55]->value}}",--}}
{{--            // safari_web_id: oneSignalSafariWebId,--}}
{{--            persistNotification: false,--}}
{{--            notificationClickHandlerMatch: 'origin',--}}
{{--            autoRegister: false,--}}
{{--            notifyButton: {--}}
{{--                enable: false--}}
{{--            }--}}
{{--        }]);--}}

{{--    </script>--}}

{{--    @php--}}
{{--        $first_segment = request()->segment(1);--}}
{{--        $second_segment = request()->segment(2);--}}
{{--        $third_segment = request()->segment(3);--}}
{{--    @endphp--}}


{{--@endif--}}

@if(!empty($result['setting'][76]->value))
    <?=stripslashes($result['setting'][76]->value)?>
@endif
