<!doctype html>
<html class="no-js" lang="{{ \Locales::getCurrent() }}">
<head dir="{{ \Locales::getScript() }}">
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ \Locales::getMetaTitle() }}</title>
    <meta name="description" content="{{ \Locales::getMetaDescription() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" href="{{ \App\Helpers\autover('/apple-touch-icon.png') }}">

    <link href="{{ \App\Helpers\autover('/css/' . \Locales::getNamespace() . '/main.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Oswald|Roboto:400,400italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <script src="{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>
<body>
    @if (Auth::user()->role_id < 3)
        <header>
            <div id="header-wrapper">
                @include(\Locales::getNamespace() . '/partials.fixed-header')
            </div>
        </header>
    @endif
    <main>
        @if (Auth::user()->role_id < 3)
            <div id="wrapper"{!! isset($jsCookies['navState']) ? ' class="collapsed"' : '' !!}>
                <div id="sidebar-wrapper">
                    @include(\Locales::getNamespace() . '/partials.sidebar')
                </div>
                <div id="main-wrapper">
                    @include(\Locales::getNamespace() . '/partials.breadcrumbs')

                    <div id="content-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        @else
            <div id="wrapper" class="collapsed">
                <div id="main-wrapper">
                    <div id="content-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        @endif
    </main>
    @if (Auth::user()->role_id < 3)
        <footer>
            <div id="footer-wrapper">
                @include(\Locales::getNamespace() . '/partials.footer')
            </div>
        </footer>
    @endif

	<script>
    'use strict';

    var CKEDITOR_BASEPATH = '/js/{{ \Locales::getNamespace() }}/vendor/ckeditor/';

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
                    headroomOffset: 300,
                    language: '{{ \Locales::getCurrent() }}',
                    defaultLanguage: '{{ \Locales::getDefault() }}',
                    languageScript: '{{ \Locales::getScript() }}',

                    @foreach (\Lang::get(\Locales::getNamespace() . '/plugins') as $plugin => $data)
                        {{ $plugin }}: {
                            @if ($plugin == 'fineUploader')
                                imageExtensions: {!! json_encode(\Config::get('upload.imageExtensions')) !!},
                                fileExtensions: {!! json_encode(\Config::get('upload.fileExtensions')) !!},
                            @endif

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

                    datatables: true,
                    datatablesLanguage: '{{ \App\Helpers\autover('/lng/datatables/' . \Locales::getCurrent() . '.json') }}',
                    datatablesPipeline: {{ \Config::get('datatables.pipeline') }},
                    datatablesSearchDelay: {{ \Config::get('datatables.searchDelay') }},
                    datatablesPaging: {{ \Config::get('datatables.paging') }},
                    datatablesPagingType: {
                        @foreach (\Config::get('datatables.sizes') as $size)
                            {{ $size }}: '{{ \Config::get('datatables.pagingType.' . $size) }}',
                        @endforeach
                    },
                    datatablesPageLength: {
                        @foreach (\Config::get('datatables.sizes') as $size)
                            {{ $size }}: {{ \Config::get('datatables.pageLength.' . $size) }},
                        @endforeach
                    },
                    datatablesLengthMenu: {
                        @foreach (\Config::get('datatables.sizes') as $size)
                            {{ $size }}: {!! str_replace('all', trans(\Locales::getNamespace() . '/messages.all'), \Config::get('datatables.lengthMenu.' . $size)) !!},
                        @endforeach
                    },
                });

                @yield('script')

                unikat.run();
            }
        },
    ]);
    </script>

</body>
</html>
