<!doctype html>
<html class="no-js" lang="{{ \Locales::getCurrent() }}">
<head dir="{{ \Locales::getScript() }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $model->title or null }}</title>
    <meta name="description" content="{{ $model->description or null }}">
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" href="{{ \App\Helpers\autover('/apple-touch-icon.png') }}">

    <link href="{{ \App\Helpers\autover('/css/' . \Locales::getNamespace() . '/main.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Oswald|Roboto:400,400italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <script src="{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>
<body>
    <header>
        @include(\Locales::getNamespace() . '/partials.header')
        @include(\Locales::getNamespace() . '/partials.nav')
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
                $.extend(unikat.variables, {
                    ajaxErrorMessage: '{!! trans(\Locales::getNamespace() . '/js.ajaxErrorMessage') !!}',
                    loadingImageSrc: '{{ \App\Helpers\autover('/img/' . \Locales::getNamespace() . '/loading.gif') }}',
                    loadingImageAlt: '{{ trans(\Locales::getNamespace() . '/js.loadingImageAlt') }}',
                    loadingImageTitle: '{{ trans(\Locales::getNamespace() . '/js.loadingImageTitle') }}',
                    loadingText: '{{ trans(\Locales::getNamespace() . '/js.loadingText') }}',

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

                @yield('script')

                unikat.run();
            }
        }
    ]);
    </script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-10298608-1', 'auto');
        ga('send', 'pageview');
    </script>

</body>
</html>
