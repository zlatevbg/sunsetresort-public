@extends(\Locales::getNamespace() . '.master')

@section('content')
    <article class="content-wrapper article">
        <h1>{{ $model->title }}</h1>
        <div class="dots"><span></span><span></span><span></span><span></span></div>
        @if (count($model->file) > 0)
        <div class="info-download">
            <a class="btn btn-default" href="{{ \Locales::route('download-offer', $model->file->first()->id) }}">
                {!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/ext/' . $model->file->first()->extension . '.png'), $model->file->first()->name, ['title' => $model->file->first()->title]) . trans(\Locales::getNamespace() . '/forms.downloadButton') !!}
            </a>
        </div>
        @endif
        <div class="text">{!! $model->content !!}</div>
    </article>
@endsection

@section('script')
    unikat.sliders = {};

    @if (count($model->image) > 0)
    unikat.sliders['slider-header'] = {
        buttons: false,
        loop: {{ count($model->image) > 2 ? 'true' : 'false' }},
        height: {{ \Config::get('upload.sliderHeight') }},
    };
    @endif
@endsection
