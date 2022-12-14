<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    @if (!empty($meta_title))
    <title>{{ $meta_title }}</title>
    @else
    <title>Laravel E-commerce Website from frezh Developers YouTube Channel</title>
    @endif

    @if (!empty($meta_description))
    <meta name="description" content="{{ $meta_description }}">
    @else
    <meta name="description"
        content="This is the test E-commerce Website that we made to help the beginners in laravel. So join and learn Laravel quickly.">
    @endif

    @if (!empty($meta_keywords))
    <meta name="keywords" content="{{ $meta_keywords }}">
    @else
    <meta name="keywords"
        content="developers, laravel course, laravel video tutorial, subcribe frezh developers, download laravel website code">
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Front style -->
    <link id="callCss" rel="stylesheet" href="{{ url('css/front_css/front.min.css')}}" media="screen" />
    <link href="{{ url('css/front_css/base.css')}}" rel="stylesheet" media="screen" />
    <!-- Front style responsive -->
    <link href="{{ url('css/front_css/front-responsive.min.css')}}" rel="stylesheet" />
    <link href="{{ url('css/front_css/font-awesome.css')}}" rel="stylesheet" type="text/css">
    <!-- Google-code-prettify -->
    <link href="{{ url('js/front_js/google-code-prettify/prettify.css')}}" rel="stylesheet" />
    <!-- fav and touch icons -->
    <link rel="shortcut icon" href="{{ asset('images/front_images/ico/favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
        href="{{ asset('images/front_images/ico/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
        href="{{ asset('images/front_images/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
        href="{{ asset('images/front_images/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed"
        href="{{ asset('images/front_images/ico/apple-touch-icon-57-precomposed.png') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ url("plugins/summernote/summernote-bs4.min.css") }}">
    <style type="text/css" id="enject"></style>
    <style>
        form.cmxform label.error,
        label.error {
            color: red;
            font-style: italic;
        }
    </style>
    {{-- sticky share begin --}}
    <script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=63071dc0f4696f0019bda34b&product=sticky-share-buttons"
        async="async">
    </script>
    {{-- sticky share end --}}

</head>

<body>
    @include('layouts.front_layout.front_header')
    <!-- Header End====================================================================== -->
    @include('front.banners.home_page_banners')
    <div id="mainBody">
        <div class="container">
            <div class="row">
                <!-- Sidebar ================================================== -->
                @include('layouts.front_layout.front_sidebar')
                <!-- Sidebar end=============================================== -->
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Footer ================================================================== -->
    @include('layouts.front_layout.front_footer')
    <!-- Placed at the end of the document so the pages load faster ============================================= -->
    <script src="{{ url('js/front_js/jquery.js')}}" type="text/javascript"></script>
    <script src="{{ url('js/front_js/jquery.validate.js')}}" type="text/javascript"></script>
    <script src="{{ url('js/front_js/front.min.js')}}" type="text/javascript"></script>
    <script src="{{ url('js/front_js/google-code-prettify/prettify.js')}}"></script>

    <script src="{{ url('js/front_js/front.js')}}"></script>
    <script src="{{ url('js/front_js/front_script.js')}}"></script>
    <script src="{{ url('js/front_js/jquery.lightbox-0.5.js')}}"></script>
    <!-- Summernote -->
    <script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        $(function () {
                //Summernote
                $('.textarea').summernote();
            })
    </script>

    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-63072548feacb7ad"></script>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/63076f4f37898912e9651fc7/1gbaglk70';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
</body>

</html>