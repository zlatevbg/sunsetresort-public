@extends(\Locales::getNamespace() . '.master')

@section('content')
<article class="content-wrapper article">
    <h1>{{ $model->title }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    <div class="text">{!! $model->content !!}</div>

    <div class="two-columns">
        <nav class="left-column">
            <ul class="galleries-navigation slidedown-menu active menu-static">
                @each(\Locales::getNamespace() . '/galleries.navigation', $galleriesNavigation, 'item')
            </ul>
        </nav>
        <div class="right-column">
            @if ($model->directory == 'sunset-resort')
                <div style="margin-bottom: 20px">
                    <div id="{{ $model->directory }}"></div>
                </div>
            @endif
            <div class="gallery-wrapper popup-gallery">
            @foreach($model->images as $image)
                <a class="popup" href="/upload/{{ $model->getTable() . '/' . $image->directory . '/' . $image->uuid . '/' . $image->file }}">{!! HTML::image(\App\Helpers\autover('/upload/' . $model->getTable() . '/' . $image->directory . '/' . $image->uuid . '/' . \Config::get('upload.galleryDirectory') . '/' . $image->file), $image->name, ['title' => $image->title]) !!}</a>
            @endforeach
            </div>
            <div class="pagination-wrapper">{!! preg_replace('/(\?|&amp;)page=1"/', '"', $model->images->links()) !!}</div>
        </div>
    </div>
</article>
@endsection

@if ($model->directory == 'sunset-resort')
@section('jsFiles')
    jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jwplayer/jwplayer.js') }}');

    @parent
@endsection

@section('script')
    jwplayer.key="BDjNoUIAZYxSHTDCXGSDLcQHYzypUkzTxjs5pQ==";
    var player = jwplayer('{{ $model->directory }}');
    player.setup({
        file: '//www.youtube.com/watch?v=eWTJWUc5I48',
        image: '{{ \App\Helpers\autover('/img/www/video-2018.jpg') }}',
        width: '100%',
        height: 488,
        stretching: 'fill',
    });
@endsection
@endif
