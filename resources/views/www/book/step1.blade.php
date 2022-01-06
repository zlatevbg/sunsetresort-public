@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    {!! Form::open(['url' => \Locales::route('book', '2'), 'id' => 'book-step1-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock ' . (isset($formClass) ? $formClass : ''), 'role' => 'form']) !!}

    @include(\Locales::getNamespace() . '/partials.steps', ['step' => $step])

    @include(\Locales::getNamespace() . '/shared.errors')

    <div class="text">{!! $model->content !!}</div>

    {!! Form::hidden('dfrom', session('dfrom'), ['id' => 'input-dfrom-hidden']) !!}
    {!! Form::hidden('dto', session('dto'), ['id' => 'input-dto-hidden']) !!}

    <div class="booking-wrapper">
        <h3><span>1</span>{{ trans(\Locales::getNamespace() . '/messages.step1-active') }}</h3>
        <section class="booking-date">
            <div class="form-group">
                <label for="input-dfrom" class="book-label book-date-label">{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}<strong class="selected" id="input-dfrom-text">&nbsp;</strong></label>
                <div id="input-dfrom"></div>
            </div>
            <div class="form-group">
                <label for="input-dto" class="book-label book-date-label">{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}<strong class="selected" id="input-dto-text">&nbsp;</strong></label>
                <div id="input-dto"></div>
            </div>
        </section>
    </div>

    <div class="bookButtons">
        <button class="btn btn-primary bookNext" type="submit">{{ trans(\Locales::getNamespace() . '/forms.nextButton') }}<i class="glyphicon glyphicon-chevron-right"></i></button>
    </div>

    {!! Form::close() !!}

    <script>
    @section('script')
        unikat.bookDates('{{ session()->has('dfrom') ? session('dfrom')->toATOMString() : '' }}', '{{ session()->has('dto') ? session('dto')->toATOMString() : '' }}', '{{ $firstDate }}');

        window.setTimeout(function() {
            $('.mfp-wrap').scrollTop(0);
        }, 200);
    <?php if (isset($formClass)) { ?>
        @endsection
    <?php } else { ?>
        @show
    <?php } ?>
    </script>
</div>
@endsection
