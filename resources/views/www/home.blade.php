@extends(\Locales::getNamespace() . '.master')

@section('content')
<article class="content-wrapper article">
    <h1>{{ $model->title }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    <div class="text">{!! $model->content !!}</div>
</article>

@if (count($banners) > 0)
<section class="slider-pro" id="slider-banners" style="width: 100%; height: {{ \Config::get('upload.bannerHeight') }}px">
    <div class="sp-slides">
    @foreach ($banners as $banner)
        @if($banner->is_video)
        <div class="sp-slide">
            <div id="{{ $banner->identifier }}">Loading the player...</div>
        </div>
        @else
        <div @if($banner->identifier)id="{{ $banner->identifier }}"@endif class="sp-slide">
            @if($banner->url)<a href="{{ $banner->url }}">@endif
            {!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/blank.gif'), $banner->name, ['class' => 'sp-image', 'data-src' => \App\Helpers\autover('/upload/' . $banner->getTable() . '/' . \Locales::getCurrent() . '/' . $banner->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $banner->image->first()->uuid . '/' . \Config::get('upload.bannerDirectory') . '/' . $banner->image->first()->file), 'data-small' => \App\Helpers\autover('/upload/' . $banner->getTable() . '/' . \Locales::getCurrent() . '/' . $banner->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $banner->image->first()->uuid . '/' . \Config::get('upload.bannerSmallDirectory') . '/' . $banner->image->first()->file), 'data-medium' => \App\Helpers\autover('/upload/' . $banner->getTable() . '/' . \Locales::getCurrent() . '/' . $banner->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $banner->image->first()->uuid . '/' . \Config::get('upload.bannerMediumDirectory') . '/' . $banner->image->first()->file), 'data-large' => \App\Helpers\autover('/upload/' . $banner->getTable() . '/' . \Locales::getCurrent() . '/' . $banner->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $banner->image->first()->uuid . '/' . \Config::get('upload.bannerLargeDirectory') . '/' . $banner->image->first()->file)]) !!}
            @if($banner->url)</a>@endif

            @if($banner->name)
            <div class="sp-layer" data-position="centerRight" data-horizontal="80" data-show-transition="left" data-show-delay="300" data-hide-transition="right">
                <h1>{!! str_replace('|', '<br>', $banner->name) !!}</h1>

                @if($banner->slogan)
                <hr><p>{!! str_replace('|', '<br>', $banner->slogan) !!}</p>
                @endif

                @if($banner->identifier)
                    <a id="{{ $banner->identifier }}" href="{{ $banner->url ?: \Locales::route($banner->slug) }}" class="btn btn-block btn-default">
                        {{ trans(\Locales::getNamespace() . '/forms.' . ($banner->identifier == 'banner-view' || $banner->url ? 'viewButton' : 'viewOfferButton')) }}
                    </a>
                    {{--<button id="{{ $banner->identifier }}" href="#" class="btn btn-block btn-default">
                        {{ trans(\Locales::getNamespace() . '/messages.book') }}
                    </button>--}}
                @endif
            </div>
            @endif
        </div>
        @endif
    @endforeach
    </div>
</section>
@endif

@if (count($info) > 0)
<section class="info-wrapper">
    <div class="content-wrapper">
        <h1>{{ trans(\Locales::getNamespace() . '/messages.infoTitle') }}</h1>
        <div class="dots"><span></span><span></span><span></span><span></span></div>
        <ul>
        @foreach ($info as $page)
            <li>
                @if ($page->is_map)
                <a data-name="{{ $page->name }}" class="js-map" href="{{ \Locales::route() }}">
                @else
                <a data-name="{{ $page->name }}" class="js-info" href="{{ \Locales::route($page->slug) }}">
                @endif
                    {!! HTML::image(\App\Helpers\autover('/upload/' . $page->getTable() . '/' . \Locales::getCurrent() . '/' . $page->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $page->icon->first()->uuid . '/' . \Config::get('upload.iconDirectory') . '/' . $page->icon->first()->file), $page->icon->first()->name, ['title' => $page->icon->first()->title]) !!}
                </a>
            </li>
        @endforeach
        </ul>
        <ul class="info-links">
        @foreach ($info as $page)
            <li>
                @if ($page->is_map)
                <a class="js-map" href="{{ \Locales::route() }}">
                @else
                <a class="js-info" href="{{ \Locales::route($page->slug) }}">
                @endif
                {{ $page->name }}</a>
            </li>
        @endforeach
        </ul>
    </div>
</section>
@endif

@if (count($offers) > 0)
<section class="offers-wrapper">
    <div class="content-wrapper">
        <h1>{{ trans(\Locales::getNamespace() . '/messages.offersTitle') }}</h1>
        <div class="dots"><span></span><span></span><span></span><span></span></div>
        <div class="offers">
        @foreach ($offers as $offer)
            <article>
                <a class="info-page" href="{{ \Locales::route($offer->slug) }}">{!! HTML::image(\App\Helpers\autover('/upload/' . $offer->getTable() . '/' . \Locales::getCurrent() . '/' . $offer->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $offer->image->first()->uuid . '/' . \Config::get('upload.offerDirectory') . '/' . $offer->image->first()->file), $offer->image->first()->name, ['title' => $offer->image->first()->title]) !!}</a>
                <div class="offer-content">
                    <a class="info-page" href="{{ \Locales::route($offer->slug) }}"><h1>{{ $offer->name }}</h1></a>
                </div>
            </article>
        @endforeach
        </div>
    </div>
</section>
@endif

@if (count($partners->logo) > 0)
<section class="partners-wrapper">
    <div class="content-wrapper">
        <div class="slider-pro" id="slider-partners" style="height: {{ \Config::get('upload.partnerHeight') }}px">
            <div class="sp-slides">
            @foreach ($partners->logo as $partner)
                <div class="sp-slide">
                    @if($partner->url)<a href="{{ $partner->url }}">@endif
                    {!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/blank.gif'), $partner->name, ['class' => 'sp-image', 'data-src' => \App\Helpers\autover('/upload/' . $partners->getTable() . '/' . \Locales::getCurrent() . '/' . \Config::get('upload.imagesDirectory') . '/' . $partner->uuid . '/' . \Config::get('upload.partnerDirectory') . '/' . $partner->file)]) !!}
                    @if($partner->url)</a>@endif
                </div>
            @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@if ($video)
@section('jsFiles')
    jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jwplayer/jwplayer.js') }}');

    @parent
@endsection
@endif

@section('script')
@if ($video)
    jwplayer.key="BDjNoUIAZYxSHTDCXGSDLcQHYzypUkzTxjs5pQ==";
    @foreach ($banners as $banner)
        @if($banner->is_video)
        var player{{ $banner->id }} = jwplayer('{{ $banner->identifier }}');
        player{{ $banner->id }}.setup({
            file: '{{ $banner->url }}',
            image: '{{ \App\Helpers\autover('/upload/' . $banner->getTable() . '/' . \Locales::getCurrent() . '/' . $banner->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $banner->image->first()->uuid . '/' . \Config::get('upload.bannerDirectory') . '/' . $banner->image->first()->file) }}',
            title: '{{ $banner->name }}',
            description: '{{ $banner->slogan }}',
            width: '100%',
            height: ($(window).width() / ({{ \Config::get('upload.bannerWidth') }} / {{ \Config::get('upload.bannerHeight') }})),
            stretching: 'fill',
            logo: {
                file: '{{ \App\Helpers\autover('/img/www/logo-nav.png') }}',
                hide: true,
            },
        });

        player{{ $banner->id }}.on('play', function(event) {
            $(this.getContainer()).closest('.slider-pro').children('.sp-buttons').hide();
        });

        player{{ $banner->id }}.on('pause', function(event) {
            $(this.getContainer()).closest('.slider-pro').children('.sp-buttons').show();
        });

        player{{ $banner->id }}.on('complete', function(event) {
            $(this.getContainer()).closest('.slider-pro').children('.sp-buttons').show();
        });

        $(window).on('resize orientationchange', function() {
            player{{ $banner->id }}.resize($(window).width(), ($(window).width() / ({{ \Config::get('upload.bannerWidth') }} / {{ \Config::get('upload.bannerHeight') }})));
        });
        @endif
    @endforeach
@endif

    unikat.sliders = {};

    @if (count($model->images) > 0)
    unikat.sliders['slider-header'] = {
        buttons: false,
        loop: {{ count($model->images) > 2 ? 'true' : 'false' }},
        height: {{ \Config::get('upload.sliderHeight') }},
    };
    @endif

    @if (count($banners) > 0)
    unikat.sliders['slider-banners'] = {
        loop: {{ count($banners) > 2 ? 'true' : 'false' }},
        autoplay: true,
        height: {{ \Config::get('upload.bannerHeight') }},
        gotoSlide: function() {
            if (typeof jwplayer !== 'undefined') {
                var i = 0;
                while (jwplayer(i).id) {
                    var player = jwplayer(i);
                    player.pause(true);
                    i++;
                }
            }
        },
    };
    @endif

    @if (count($partners->logo) > 0)
    unikat.sliders['slider-partners'] = {
        width: {{ \Config::get('upload.partnerWidth') }},
        height: {{ \Config::get('upload.partnerHeight') }},
        autoHeight: false,
        buttons: false,
        loop: true,
        arrows: true,
        autoplay: true,
        shuffle: true,
        visibleSize: '100%',
        imageScaleMode: 'none',
        slideDistance: 50,
    };
    @endif

    unikat.bookDates(null, null, '{{ $firstDate }}');
@endsection
