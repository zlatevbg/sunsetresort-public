@extends(\Locales::getNamespace() . '.master')

@section('content')
    <section class="content-wrapper article">
        <h1>{{ $model->title }}</h1>
        <div class="dots"><span></span><span></span><span></span><span></span></div>
        <div class="text">{!! $model->content !!}</div>
        <div class="flex-container">
        @foreach($rooms as $room)
            <article class="room">
                <div class="room-image popup-galleries">
                    <div class="room-image-icon">
                        <span class="glyphicon glyphicon-camera"></span>
                    </div>
                    <a class="popup" title="{{ $room->images->first()->title }}" href="/upload/{{ $room->getTable() . '/' . \Locales::getCurrent() . '/' . $room->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $room->images->first()->uuid . '/' . $room->images->first()->file }}">{!! HTML::image(\App\Helpers\autover('/upload/' . $room->getTable() . '/' . \Locales::getCurrent() . '/' . $room->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $room->images->first()->uuid . '/' . \Config::get('upload.roomDirectory') . '/' . $room->images->first()->file), $room->images->first()->name, ['title' => $room->images->first()->title]) !!}</a>
                    @foreach($room->images->slice(1) as $image)
                        <a class="popup" title="{{ $image->title }}" href="/upload/{{ $room->getTable() . '/' . \Locales::getCurrent() . '/' . $room->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . $image->file }}"></a>
                    @endforeach
                </div>
                <div class="room-details">
                    <div class="room-header">
                        <h1>{{ $room->name }}</h1>
                        <span>{{ trans(\Locales::getNamespace() . '/messages.areaUpTo') . ' ' . $room->area . ' ' . trans(\Locales::getNamespace() . '/messages.areaMeters') }}</span>
                    </div>
                    <div class="text">{!! $room->content !!}</div>
                </div>
            </article>
        @endforeach
        </div>
    </section>
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
