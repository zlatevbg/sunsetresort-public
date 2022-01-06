<div id="header">
    <div class="logo-nav">
        <a href="{{ \Locales::route() }}"></a>
    </div>
    <div id="mobile-nav"><span>{{ trans(\Locales::getNamespace() . '/messages.mobileNav') }}</span><span class="glyphicon glyphicon-menu-hamburger"></span><span class="glyphicon glyphicon-remove"></span></div>
    <div class="facebook-nav">
        <a class="btn btn-primary" href="https://www.facebook.com/HotelSunsetResortPomorie"><span>{{ trans(\Locales::getNamespace() . '/messages.facebookTitle') }} </span>{!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/facebook-nav.png'), trans(\Locales::getNamespace() . '/messages.facebookTitle')) !!}</a>
    </div>
    @if (count(\Locales::getLocales()) > 1)
    <ul class="languages-nav">
        <li class="submenu">
            <a class="btn btn-primary slidedown-toggle">
                <span>{{ \Locales::getNativeName() }}</span>
                @if (\Locales::getNativeName() != \Locales::getName())<span class="sub-text">/ {{ \Locales::getName() }}</span>@endif
                <span class="caret"></span>
            </a>
            <ul class="slidedown-menu">
            @foreach (\Locales::getLanguages(true) as $key => $language)
                <li{!! $language['active'] ? ' class="active"' : '' !!}>
                    <a href="{{ $language['link'] }}">
                        {{ $language['native'] }}@if ($language['name'])<span class="sub-text">/ {{ $language['name'] }}</span>@endif
                    </a>
                </li>
            @endforeach
            </ul>
        </li>
    </ul>
    @endif
    <div class="phone-nav">
        <a class="btn btn-primary" href="tel:+35959635590"><span class="glyphicon glyphicon-earphone"></span></a>
    </div>
</div>
