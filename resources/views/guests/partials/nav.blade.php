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
        <li class="right"><a href="{{ env('APP_URL') . '/' . \Locales::getLanguage() }}">{{ trans(\Locales::getNamespace() . '/messages.site') }}</a></li>
    </ul>
</nav>
