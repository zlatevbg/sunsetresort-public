@if (isset($item['children']))
<li class="submenu {!! $item['class'] ? $item['class'] : '' !!}">
    <a class="slidedown-toggle" href="{{ $item['url'] }}">{{ $item['name'] }}<span class="caret"></span></a>
    <ul class="slidedown-menu menu-static">
        @each(\Locales::getNamespace() . '/galleries.navigation', $item['children'], 'item')
    </ul>
</li>
@else
<li{!! $item['class'] ? ' class="' . $item['class'] . '"' : '' !!}>
    <a href="{{ $item['url'] }}">{{ $item['name'] }}</a>
</li>
@endif
