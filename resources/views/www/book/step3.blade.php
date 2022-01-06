@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    {!! Form::open(['url' => \Locales::route('book', '4'), 'id' => 'book-step3-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock ' . (isset($formClass) ? $formClass : ''), 'role' => 'form']) !!}

    @include(\Locales::getNamespace() . '/partials.steps', ['step' => $step])

    @include(\Locales::getNamespace() . '/shared.errors')

    <div class="text">{!! $model->content !!}</div>

    <div class="booking-wrapper">
        <h3><span>3</span>{{ trans(\Locales::getNamespace() . '/messages.step3-active') }}</h3>
        <div class="booking-details">
            <div class="booking-personal-details form-group-left">
                <div class="form-group">
                    {!! Form::label('input-name', trans(\Locales::getNamespace() . '/forms.nameLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('name', session('name'), ['id' => 'input-name', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.namePlaceholder')]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-email', trans(\Locales::getNamespace() . '/forms.emailLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::email('email', session('email'), ['id' => 'input-email', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.emailPlaceholder')]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-phone', trans(\Locales::getNamespace() . '/forms.phoneLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('phone', session('phone'), ['id' => 'input-phone', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.phonePlaceholder')]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-message', trans(\Locales::getNamespace() . '/forms.messageLabel'), ['class' => 'book-label']) !!}
                    {!! Form::textarea('message', session('message'), ['id' => 'input-message', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.messagePlaceholder')]) !!}
                </div>
            </div>
            <div class="booking-company-details form-group-right">
                <div class="form-group">
                    {!! Form::label('input-invoice', trans(\Locales::getNamespace() . '/messages.invoice'), ['class' => 'book-label']) !!}
                    <div class="form-control-static">
                        <label><input id="input-invoice" name="invoice" value="1" type="checkbox">{!! trans(\Locales::getNamespace() . '/forms.invoiceLabel') !!}</label>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('input-company', trans(\Locales::getNamespace() . '/forms.companyLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('company', session('company'), ['id' => 'input-company', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.companyPlaceholder'), 'disabled']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-eik', trans(\Locales::getNamespace() . '/forms.eikLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('eik', session('eik'), ['id' => 'input-eik', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.eikPlaceholder'), 'disabled']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-vat', trans(\Locales::getNamespace() . '/forms.vatLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('vat', session('vat'), ['id' => 'input-vat', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.vatPlaceholder'), 'disabled']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-mol', trans(\Locales::getNamespace() . '/forms.molLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('mol', session('mol'), ['id' => 'input-mol', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.molPlaceholder'), 'disabled']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-country', trans(\Locales::getNamespace() . '/forms.countryLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('country', session('country'), ['id' => 'input-country', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.countryPlaceholder'), 'disabled']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-city', trans(\Locales::getNamespace() . '/forms.cityLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('city', session('city'), ['id' => 'input-city', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.cityPlaceholder'), 'disabled']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('input-address', trans(\Locales::getNamespace() . '/forms.addressLabel') . ' *', ['class' => 'book-label']) !!}
                    {!! Form::text('address', session('address'), ['id' => 'input-address', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.addressPlaceholder'), 'disabled']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="bookButtons">
        <button data-url="{{ \Locales::route('book-step', '2') }}" class="btn btn-primary bookPrev" type="button"><i class="glyphicon glyphicon-chevron-left"></i>{{ trans(\Locales::getNamespace() . '/forms.prevButton') }}</button>
        <button class="btn btn-primary bookNext" type="submit">{{ trans(\Locales::getNamespace() . '/forms.nextButton') }}<i class="glyphicon glyphicon-chevron-right"></i></button>
    </div>

    {!! Form::close() !!}

    <script>
    @section('script')
        Modernizr.load([{
            load: ['https://www.google.com/recaptcha/api.js?hl={{ \Locales::getCurrent() }}'],
        }]);

        $('.booking-wrapper').on('click', '#input-invoice', function () {
            if (this.checked) {
                $(this).closest('.booking-company-details').find('input[type="text"]').attr('disabled', false);
            } else {
                $(this).closest('.booking-company-details').find('input[type="text"]').val('').attr('disabled', true);
            }
        });

        @if (session('invoice') || old('invoice'))
            $('#input-invoice').trigger('click');
        @endif

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
