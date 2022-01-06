<nav id="nav-wrapper">
    <ul>
    @if (isset($nav))
        @foreach ($nav as $pages)
            @if (isset($pages['children']))
            <li class="submenu {{ $pages['class'] }}">
                <a class="slidedown-toggle" href="{{ $pages['url'] }}">{{ $pages['name'] }}</a>
                <ul class="slidedown-menu">
                @foreach ($pages['children'] as $page)
                    <li class="{{ $page['class'] }}"><a href="{{ $page['url'] }}">{{ $page['name'] }}</a></li>
                @endforeach
                </ul>
            </li>
            @else
            <li class="{{ $pages['class'] }}"><a href="{{ $pages['url'] }}">{{ $pages['name'] }}</a></li>
            @endif
        @endforeach
    @endif
        <li class="right"><a id="book-button" data-ajax-queue="sync" class="ajax-lock" href="{{ \Locales::route('book-step', '1') }}">{!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/icon-book.png'), trans(\Locales::getNamespace() . '/messages.book')) !!}{{ trans(\Locales::getNamespace() . '/messages.book') }}</a></li>
        {{-- <li class="right"><a id="info-button" href="{{ \Locales::route('info-2020') }}">{!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/icon-info.png'), trans(\Locales::getNamespace() . '/messages.info-2020')) !!}{{ trans(\Locales::getNamespace() . '/messages.info-2020') }}</a></li> --}}
    </ul>
</nav>
