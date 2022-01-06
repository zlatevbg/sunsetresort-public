@extends(\Locales::getNamespace() . '.master')

@section('content')
<article class="content-wrapper article">
    <h1>{{ $model->title }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    <div class="text">{!! $model->content !!}</div>

    <div class="galleries-wrapper">
    @foreach($categories as $category)
        @if (count($category->images) > 0)
        <div class="gallery">
            <a href="{{ \Locales::route($model->slug . '/' . $category->slug) }}">
                {!! HTML::image(\App\Helpers\autover('/upload/' . $category->getTable() . '/' . $category->directory . '/' . $category->images->first()->uuid . '/' . \Config::get('upload.galleryDirectory') . '/' . $category->images->first()->file), $category->images->first()->name, ['title' => $category->images->first()->title]) !!}
                <p>{{ $category->name }}</p>
            </a>
        </div>
        @endif
    @endforeach
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
