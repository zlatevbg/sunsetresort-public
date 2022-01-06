@extends(\Locales::getNamespace() . '.master')

@section('head')
    @if (\Locales::getCurrent() == 'bg')
        <!-- Event snippet for Book BG - Step 4 conversion page -->
        <script>
          gtag('event', 'conversion', {'send_to': 'AW-384610942/jJ7SCOje1ZECEP7ksrcB'});
        </script>
    @elseif (\Locales::getCurrent() == 'en')
        <!-- Event snippet for Book EN - Step 4 conversion page -->
        <script>
          gtag('event', 'conversion', {'send_to': 'AW-384610942/-6FGCM6mucMCEP7ksrcB'});
        </script>
    @elseif (\Locales::getCurrent() == 'de')
        <!-- Event snippet for Book DE - Step 4 conversion page -->
        <script>
          gtag('event', 'conversion', {'send_to': 'AW-384610942/a7gaCKrrucMCEP7ksrcB'});
        </script>
    @elseif (\Locales::getCurrent() == 'ru')
        <!-- Event snippet for Book RU - Step 4 conversion page -->
        <script>
          gtag('event', 'conversion', {'send_to': 'AW-384610942/DPz2CKyY4MMCEP7ksrcB'});
        </script>
    @endif
@endsection

@section('content')
<div class="magnific-popup">
    {!! Form::open(['url' => \Locales::route('book'), 'id' => 'book-step4-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock ' . (isset($formClass) ? $formClass : ''), 'role' => 'form']) !!}

    @include(\Locales::getNamespace() . '/partials.steps', ['step' => $step])

    <div class="text">{!! $model->content !!}</div>

    <div class="booking-wrapper">
        <h3><span>4</span>{{ trans(\Locales::getNamespace() . '/messages.step4-active') }}</h3>
        <div class="booking-summary">
            <div>
                <p>{{ trans(\Locales::getNamespace() . '/forms.nameLabel') }}: <strong>{{ session('name') }}</strong></p>
                @if (session('email'))<p>{{ trans(\Locales::getNamespace() . '/forms.emailLabel') }}: <strong>{{ session('email') }}</strong></p>@endif
                @if (session('phone'))<p>{{ trans(\Locales::getNamespace() . '/forms.phoneLabel') }}: <strong>{{ session('phone') }}</strong></p>@endif
                @if (session('message'))<p>{{ session('message') }}</p>@endif
                {{-- <table class="table">
                    <thead>
                        <tr>
                            <th>{{ trans(\Locales::getNamespace() . '/forms.nameLabel') }}</th>
                            @if (session('email'))<th>{{ trans(\Locales::getNamespace() . '/forms.emailLabel') }}</th>@endif
                            @if (session('phone'))<th>{{ trans(\Locales::getNamespace() . '/forms.phoneLabel') }}</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ session('name') }}</td>
                            @if (session('email'))<td>{{ session('email') }}</td>@endif
                            @if (session('phone'))<td>{{ session('phone') }}</td>@endif
                        </tr>
                        @if (session('message'))
                            <tr>
                                <td colspan="3">{!! nl2br(session('message')) !!}</td>
                            </tr>
                        @endif
                    </tbody>
                </table> --}}
            </div>
            @if (session('company'))
                <div>
                    <p>{{ trans(\Locales::getNamespace() . '/forms.companyLabel') }}: <strong>{{ session('company') }}</strong></p>
                    <p>{{ trans(\Locales::getNamespace() . '/forms.eikLabel') }}: <strong>{{ session('eik') }}</strong></p>
                    <p>{{ trans(\Locales::getNamespace() . '/forms.vatLabel') }}: <strong>{{ session('vat') }}</strong></p>
                    <p>{{ trans(\Locales::getNamespace() . '/forms.molLabel') }}: <strong>{{ session('mol') }}</strong></p>
                    <p>{{ trans(\Locales::getNamespace() . '/forms.countryLabel') }}: <strong>{{ session('country') }}</strong></p>
                    <p>{{ trans(\Locales::getNamespace() . '/forms.cityLabel') }}: <strong>{{ session('city') }}</strong></p>
                    <p>{{ trans(\Locales::getNamespace() . '/forms.addressLabel') }}: <strong>{{ session('address') }}</strong></p>
                </div>
            @endif
            <div>
                <p>{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}: <strong>{{ session('dfrom')->format('d.m.Y') }}</strong></p>
                <p>{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}: <strong>{{ session('dto')->format('d.m.Y') }}</strong></p>
                <p>{{ trans(\Locales::getNamespace() . '/messages.nights') }}: <strong>{{ session('dfrom')->diffInDays(session('dto')) }}</strong></p>
                {{-- <table class="table">
                    <thead>
                        <tr>
                            <th>{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}</th>
                            <th>{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}</th>
                            <th class="text-center">{{ trans(\Locales::getNamespace() . '/messages.nights') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ session('dfrom')->format('d.m.Y') }}</td>
                            <td>{{ session('dto')->format('d.m.Y') }}</td>
                            <td class="text-center">{{ session('dfrom')->diffInDays(session('dto')) }}</td>
                        </tr>
                    </tbody>
                </table> --}}
            </div>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        @foreach (session('rooms') as $slug => $room)
                            <tr>
                                <th colspan="5" class="bg-primary">{{ session('roomsArray')[$slug]->name }}</th>
                            </tr>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ trans(\Locales::getNamespace() . '/forms.viewLabel') }}</th>
                                <th>{{ trans(\Locales::getNamespace() . '/forms.mealLabel') }}</th>
                                <th class="text-center">{{ trans(\Locales::getNamespace() . '/forms.guestsLabel') }}</th>
                                <th class="text-right">{{ trans(\Locales::getNamespace() . '/forms.priceLabel') }}</th>
                            </tr>
                            @foreach ($room as $id => $r)
                                <tr>
                                    <td class="text-center">{{ $id }}</td>
                                    <td>{{ session('viewsArray')[$r['view']]->name }}</td>
                                    <td>{{ session('mealsArray')[$r['meal']]->name }}</td>
                                    <td class="text-center">{{ $r['guests'] }}</td>
                                    <td class="text-right">{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($r['priceTotal'], 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($r['priceTotal'] / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}</td>
                                </tr>
                                @if ($r['periods'])
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-small" colspan="4">{!! $r['periods'] !!}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-center totalAmount">
                <h3>{{ trans(\Locales::getNamespace() . '/messages.reservationTotal') }}</h3>
                <p class="text-primary">{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format(session('grandTotal'), 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format(session('grandTotal') / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}</p>
                <p>{{ trans(\Locales::getNamespace() . '/messages.reservationDepositNote') }}</p>
            </div>
        </div>

        <div class="form-group text-center">
            <label class="checkbox-inline"><input id="input-consent" name="consent" value="1" type="checkbox">{!! trans(\Locales::getNamespace() . '/forms.consent') !!}</label>
        </div>

        <div id="grecaptcha" class="g-recaptcha" data-sitekey="{{ \Config::get('services.recaptcha.key') }}"></div>

        @include(\Locales::getNamespace() . '/shared.errors')

        <div class="booking-payment">
            {!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/cards.png')) !!}
            <button class="btn btn-success payButton" type="submit">{!! trans(\Locales::getNamespace() . '/forms.payButton') . '<span>' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format(session('priceFirstNight')/*(session('grandTotal') / session('nights'))*/, 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . '<br>(' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format(session('priceFirstNight')/*(session('grandTotal') / session('nights'))*/ / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')</span>' !!}</button>
            {!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/cards-security.png')) !!}
        </div>
    </div>

    <div class="bookButtons">
        <button data-url="{{ \Locales::route('book-step', '3') }}" class="btn btn-primary bookPrev" type="button"><i class="glyphicon glyphicon-chevron-left"></i>{{ trans(\Locales::getNamespace() . '/forms.prevButton') }}</button>
    </div>

    {!! Form::close() !!}

    <form method="POST" action="https://3dsgate.borica.bg/cgi-bin/cgi_link" accept-charset="UTF-8" id="borica-form" class="none" role="form">
        <input id="BACKREF" name="BACKREF" type="hidden">
        <input id="LANG" name="LANG" type="hidden">
        <input id="AMOUNT" name="AMOUNT" type="hidden">
        <input id="CURRENCY" name="CURRENCY" type="hidden">
        <input id="DESC" name="DESC" type="hidden">
        <input id="TERMINAL" name="TERMINAL" type="hidden">
        <input id="MERCH_NAME" name="MERCH_NAME" type="hidden">
        <input id="MERCH_URL" name="MERCH_URL" type="hidden">
        <input id="MERCHANT" name="MERCHANT" type="hidden">
        <input id="EMAIL" name="EMAIL" type="hidden">
        <input id="TRTYPE" name="TRTYPE" type="hidden">
        <input id="ORDER" name="ORDER" type="hidden">
        <input id="AD.CUST_BOR_ORDER_ID" name="AD.CUST_BOR_ORDER_ID" type="hidden">
        <input id="COUNTRY" name="COUNTRY" type="hidden">
        <input id="TIMESTAMP" name="TIMESTAMP" type="hidden">
        <input id="MERCH_GMT" name="MERCH_GMT" type="hidden">
        <input id="NONCE" name="NONCE" type="hidden">
        <input id="ADDENDUM" name="ADDENDUM" type="hidden">
        <input id="P_SIGN" name="P_SIGN" type="hidden">
    </form>

    <script>
    @section('script')
        Modernizr.load([{
            load: ['https://www.google.com/recaptcha/api.js?hl={{ \Locales::getCurrent() }}'],
            complete: function() {
                if (typeof grecaptcha.render != 'undefined') {
                    grecaptcha.render('grecaptcha', {
                      'sitekey' : '{{ \Config::get('services.recaptcha.key') }}'
                    });
                }
            },
        }]);

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
