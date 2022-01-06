@extends(\Locales::getNamespace() . '.master')

@section('content')
<article class="content-wrapper article">
    <h1>{{ $model->title }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    <div class="text">{!! $model->content !!}</div>
    <div class="years-wrapper">
        <?php $i = 0; ?>
        @foreach($awards as $award)
        <span>{{ $award->name }}</span>
        <div class="awards-wrapper {{ $i % 2 == 0 ? 'even' : 'odd' }}">
            @foreach($award->images as $image)
            <div class="award">
                @if ($i % 2 == 0)
                <div class="award-image popup-galleries">
                    <a class="popup" title="{{ $image->name }}" href="{{ \App\Helpers\autover('/upload/' . $award->getTable() . '/' . \Locales::getCurrent() . '/' . $award->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . $image->file) }}">
                        {!! HTML::image(\App\Helpers\autover('/upload/' . $award->getTable() . '/' . \Locales::getCurrent() . '/' . $award->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . \Config::get('upload.awardDirectory') . '/' . $image->file), $image->name) !!}
                    </a>
                </div>
                <div class="award-details">
                    <h1>{{ $image->name }}</h1>
                    <div class="text">{{ $image->content }}</div>
                </div>
                @else
                <div class="award-details">
                    <h1>{{ $image->name }}</h1>
                    <div class="text">{{ $image->content }}</div>
                </div>
                <div class="award-image popup-galleries">
                    <a class="popup" title="{{ $image->name }}" href="{{ \App\Helpers\autover('/upload/' . $award->getTable() . '/' . \Locales::getCurrent() . '/' . $award->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . $image->file) }}">
                        {!! HTML::image(\App\Helpers\autover('/upload/' . $award->getTable() . '/' . \Locales::getCurrent() . '/' . $award->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . \Config::get('upload.awardDirectory') . '/' . $image->file), $image->name) !!}
                    </a>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <?php $i++; ?>
        @endforeach
        {!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/awards.png')) !!}
    </div>
</article>
@endsection

@section('script')
    unikat.sliders = {};

    @if (count($model->images) > 0)
    unikat.sliders['slider-header'] = {
        buttons: false,
        loop: {{ count($model->images) > 2 ? 'true' : 'false' }},
        height: {{ \Config::get('upload.sliderHeight') }},
    };
    @endif
@endsection
