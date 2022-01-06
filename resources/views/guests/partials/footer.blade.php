@if (count($info) > 0)
<div class="info-wrapper">
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
</div>
@endif

<div class="subscribe-wrapper">
    <div class="content-wrapper">
        <h1>{{ trans(\Locales::getNamespace() . '/messages.subscribeTitle') }}</h1>
        <p>{{ trans(\Locales::getNamespace() . '/messages.subscribeText') }}</p>
        {!! Form::open(['url' => \Locales::route('subscribe'), 'id' => 'subscribe-form', 'data-alert-container' => 'subscribe-wrapper', 'data-ajax-queue' => 'sync', 'class' => 'form-inline ajax-lock', 'role' => 'form']) !!}
            <div class="form-group{!! ($errors->has('subscribe_email') ? ' has-error has-feedback' : '') !!}">
                {!! Form::label('input-subscribe_email', trans(\Locales::getNamespace() . '/forms.emailLabel'), ['class' => 'sr-only']) !!}
                <div class="input-group">
                    {!! Form::email('subscribe_email', null, ['id' => 'input-subscribe_email', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.emailPlaceholder')]) !!}
                    <span class="input-group-btn">
                        {!! Form::submit(trans(\Locales::getNamespace() . '/forms.subscribeButton'), ['class' => 'btn btn-default']) !!}
                    </span>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
