@extends(\Locales::getNamespace() . '.master')

@section('content')
<section class="content-wrapper article">
    <h1>{{ $model->title }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    <div class="text">{!! $model->content !!}</div>
    <div class="questions-wrapper">
        @foreach($questions as $question)
            <section class="question-group-wrapper">
                <h1>{{ $question['name'] }}</h1>
                <small><a class="text-muted" id="expand-all" href="#">{{ trans(\Locales::getNamespace() . '/messages.expand-all') }}</a> | <a class="text-muted" id="collapse-all" href="#">{{ trans(\Locales::getNamespace() . '/messages.collapse-all') }}</a></small>
                @foreach($question['children'] as $q)
                    <article class="question">
                        <a href="#" class="slidedown-toggle"><h4>{{ $q['question'] }}</h4></a>
                        <div class="slidedown-menu menu-static text">{!! $q['answer'] !!}</div>
                    </article>
                @endforeach
            </section>
        @endforeach
    </div>
</section>
@endsection

@section('script')
    unikat.sliders = {};

    $(document).on('click', '#expand-all', function(e) {
        e.preventDefault();

        var group = $(this).closest('.question-group-wrapper');
        $('.slidedown-menu', group).slideDown();
    });

    $(document).on('click', '#collapse-all', function(e) {
        e.preventDefault();

        var group = $(this).closest('.question-group-wrapper');
        $('.slidedown-menu', group).slideUp();
    });

    @if (count($model->images) > 0)
    unikat.sliders['slider-header'] = {
        buttons: false,
        loop: {{ count($model->images) > 2 ? 'true' : 'false' }},
        height: {{ \Config::get('upload.sliderHeight') }},
    };
    @endif
@endsection
