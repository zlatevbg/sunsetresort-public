<!doctype html>
<html class="no-js" lang="{{ \Locales::getCurrent() }}">
<head dir="{{ \Locales::getScript() }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $model->title or null }}</title>
    <meta name="description" content="{{ $model->description or null }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" href="{{ \App\Helpers\autover('/apple-touch-icon.png') }}">

    <link href="{{ \App\Helpers\autover('/css/' . \Locales::getNamespace() . '/main.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Oswald|Roboto:400,400italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <script src="{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/modernizr-2.8.3.min.js') }}"></script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-10298608-1', 'auto');
        ga('send', 'pageview');
    </script>

    <!-- Global site tag (gtag.js) - Google Ads: 384610942 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-384610942"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'AW-384610942');
    </script>

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
      fbq('init', '2996998167214301');
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=2996998167214301&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel Code -->

    @yield('head')
</head>
<body>
    <header>
        @include(\Locales::getNamespace() . '/partials.header')
        @include(\Locales::getNamespace() . '/partials.nav')

        @if (isset($utcNow) && $utcNow->timestamp <= $utc->timestamp)
            @include(\Locales::getNamespace() . '/partials.countdown')
        @endif

        @include(\Locales::getNamespace() . '/partials.slider')
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        @include(\Locales::getNamespace() . '/partials.footer')
    </footer>

    <script>
    'use strict';

    var jsFiles = [];
    @section('jsFiles')
        jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jquery-ui.min.js') }}');
        jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/main.js') }}');
    @show

    Modernizr.load([
        {
            load: ['//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'],
            complete: function() {
                if (!window.jQuery) {
                    Modernizr.load('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jquery-2.2.0.min.js') }}');
                }
            }
        },
        {
            load: jsFiles,
            complete: function() {
                $('.bookings-home-overlay').addClass('loaded');

                $.extend(unikat.variables, {
                    ajaxErrorMessage: '{!! trans(\Locales::getNamespace() . '/js.ajaxErrorMessage') !!}',
                    loadingImageSrc: '{{ \App\Helpers\autover('/img/' . \Locales::getNamespace() . '/loading.gif') }}',
                    loadingImageAlt: '{{ trans(\Locales::getNamespace() . '/js.loadingImageAlt') }}',
                    loadingImageTitle: '{{ trans(\Locales::getNamespace() . '/js.loadingImageTitle') }}',
                    loadingText: '{{ trans(\Locales::getNamespace() . '/js.loadingText') }}',
                    language: '{{ \Locales::getCurrent() }}',
                    defaultLanguage: '{{ \Locales::getDefault() }}',
                    languageScript: '{{ \Locales::getScript() }}',
                    dates: '{!! json_encode($dates) !!}',

                    @if (isset($utcNow))
                    utcNowTimestamp: '{{ $utcNow->timestamp }}',
                    utcTimestamp: '{{ $utc->timestamp }}',
                    @endif

                    countdownDays: '{{ trans(\Locales::getNamespace() . '/messages.countdownDays') }}',
                    countdownHours: '{{ trans(\Locales::getNamespace() . '/messages.countdownHours') }}',
                    countdownMinutes: '{{ trans(\Locales::getNamespace() . '/messages.countdownMinutes') }}',
                    countdownSeconds: '{{ trans(\Locales::getNamespace() . '/messages.countdownSeconds') }}',

                    @foreach (\Lang::get(\Locales::getNamespace() . '/plugins') as $plugin => $data)
                        {{ $plugin }}: {
                            @foreach (\Lang::get(\Locales::getNamespace() . '/plugins.' . $plugin) as $key => $value)
                                @if (is_array($value))
                                {{ $key }}: {
                                    @foreach (\Lang::get(\Locales::getNamespace() . '/plugins.' . $plugin . '.' . $key) as $subKey => $subValue)
                                        {{ $subKey }}: {!! json_encode($subValue) !!},
                                    @endforeach
                                },
                                @else
                                {{ $key }}: '{!! $value !!}',
                                @endif
                            @endforeach
                        },
                    @endforeach
                });

                window.cookieconsent.initialise({
                  'palette': {
                    'popup': {
                      'background': '#e2a749',
                      'text': '#f0f2f2',
                      'link': '#f7f0ed',
                    },
                    'button': {
                      'background': '#e32f55',
                    },
                  },
                  'theme': 'classic',
                  'content': {
                    'header': '{{ trans(\Locales::getNamespace() . '/js.cookieconsentHeader') }}',
                    'message': '{{ trans(\Locales::getNamespace() . '/js.cookieconsentMessage') }}',
                    'dismiss': '{{ trans(\Locales::getNamespace() . '/js.cookieconsentDismiss') }}',
                    'allow': '{{ trans(\Locales::getNamespace() . '/js.cookieconsentAllow') }}',
                    'deny': '{{ trans(\Locales::getNamespace() . '/js.cookieconsentDecline') }}',
                    'link': '{{ trans(\Locales::getNamespace() . '/js.cookieconsentLink') }}',
                    'href': '{{ \Locales::route('terms') . '#cookies' }}',
                    'policy': '{{ trans(\Locales::getNamespace() . '/js.cookieconsentPolicy') }}',
                  }
                });

                @yield('script')

                unikat.run();
            }
        }
    ]);
    </script>

    <script type="text/javascript">
    _linkedin_partner_id = "3551297";
    window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
    window._linkedin_data_partner_ids.push(_linkedin_partner_id);
    </script><script type="text/javascript">
    (function(){var s = document.getElementsByTagName("script")[0];
    var b = document.createElement("script");
    b.type = "text/javascript";b.async = true;
    b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
    s.parentNode.insertBefore(b, s);})();
    </script>
    <noscript>
    <img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=3551297&fmt=gif" />
    </noscript>

    <!-- Twitter universal website tag code -->
    <script>
    !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
    },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
    a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
    // Insert Twitter Pixel ID and Standard Event data below
    twq('init','o60ev');
    twq('track','PageView');
    </script>
    <!-- End Twitter universal website tag code -->

</body>
</html>
