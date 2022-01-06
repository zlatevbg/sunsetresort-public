@extends(\Locales::getNamespace() . '.master')

@section('content')
<section class="content-wrapper article">
    <h1>{{ $model->title }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    <div class="text">{!! $model->content !!}</div>
    <div class="testimonials-wrapper">
        <?php $i = 0; ?>
        @foreach($testimonials as $testimonial)
        <article class="testimonial {{ $i % 2 == 0 ? 'even' : 'odd' }}">
            <h1>
                <span class="name">{{ $testimonial->name }}</span>
                <span class="country">{{ $testimonial->country }}</span>
            </h1>
            <div class="text">{{ $testimonial->content }}</div>
        </article>
        <?php $i++; ?>
        @endforeach
    </div>
    <div class="pagination-wrapper">{!! preg_replace('/(\?|&amp;)page=1"/', '"', $testimonials->links()) !!}</div>
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
