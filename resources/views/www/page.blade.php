@extends(\Locales::getNamespace() . '.master')

@section('content')
<{{ count($models) > 0 ? 'section' : 'article' }} class="content-wrapper article">
    <h1>{{ $model->title }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    @if (count($model->file) > 0)
    <div class="info-download">
        <a class="btn btn-default" href="{{ \Locales::route('download', $model->file->first()->id) }}">
            {!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/ext/' . $model->file->first()->extension . '.png'), $model->file->first()->name, ['title' => $model->file->first()->title]) . trans(\Locales::getNamespace() . '/forms.downloadButton') !!}
        </a>
    </div>
    @endif
    <div class="text">{!! $model->content !!}</div>
    @if (count($models) > 0)
    <div class="flex-container">
        @foreach($models as $m)
        <article class="articles">
            <div class="img">{!! $m->images->first() ? HTML::image(\App\Helpers\autover('/upload/' . $m->getTable() . '/' . \Locales::getCurrent() . '/' . $model->parent_slug . '/' . $model->slug . '/' . $m->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $m->images->first()->uuid . '/' . \Config::get('upload.pageDirectory') . '/' . $m->images->first()->file), $m->images->first()->name, ['title' => $m->images->first()->title]) : '' !!}</div>
            <div class="details">
                <h2>{{ $m->title }}</h2>
                <div class="text">{!! $m->content !!}</div>
            </div>
        </article>
        @endforeach
    </div>
    @endif
</{{ count($models) > 0 ? 'section' : 'article' }}>
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
