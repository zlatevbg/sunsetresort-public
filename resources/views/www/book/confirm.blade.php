@extends(\Locales::getNamespace() . '.master')

@section('head')
    @if (\Locales::getCurrent() == 'bg')
        <!-- Event snippet for Book BG - Confirm conversion page -->
        <script>
          gtag('event', 'conversion', {'send_to': 'AW-384610942/8NdeCK6g4MMCEP7ksrcB'});
        </script>
    @elseif (\Locales::getCurrent() == 'en')
        <!-- Event snippet for Book EN - Confirm conversion page -->
        <script>
          gtag('event', 'conversion', {'send_to': 'AW-384610942/P-P7CKmG3sMCEP7ksrcB'});
        </script>
    @elseif (\Locales::getCurrent() == 'de')
        <!-- Event snippet for Book DE - Confirm conversion page -->
        <script>
          gtag('event', 'conversion', {'send_to': 'AW-384610942/mUGZCPWK3sMCEP7ksrcB'});
        </script>
    @elseif (\Locales::getCurrent() == 'ru')
        <!-- Event snippet for Book RU - Confirm conversion page -->
        <script>
          gtag('event', 'conversion', {'send_to': 'AW-384610942/9s5bCI6z4MMCEP7ksrcB'});
        </script>
    @endif
@endsection

@section('content')
<article class="content-wrapper article">
    <h1>{{ $model->title . ' #SRB' . date('Y') . '-' . $booking->id }}</h1>
    <div class="dots"><span></span><span></span><span></span><span></span></div>
    <div class="text">{!! $model->content !!}</div>

    <div class="booking-summary">
        {!! trans(\Locales::getNamespace() . '/newsletters.confirmation') !!}
        <hr>
        <p>{{ trans(\Locales::getNamespace() . '/forms.nameLabel') }}: <strong>{{ $booking->name }}</strong></p>
        <p>{{ trans(\Locales::getNamespace() . '/forms.emailLabel') }}: <strong>{{ $booking->email }}</strong></p>
        <p>{{ trans(\Locales::getNamespace() . '/forms.phoneLabel') }}: <strong>{{ $booking->phone }}</strong></p>
        @if ($booking->message)<p>{{ $booking->message }}</p>@endif
        @if ($booking->company)
            <hr>
            <p>{{ trans(\Locales::getNamespace() . '/forms.companyLabel') }}: <strong>{{ $booking->company }}</strong></p>
            <p>{{ trans(\Locales::getNamespace() . '/forms.eikLabel') }}: <strong>{{ $booking->eik }}</strong></p>
            <p>{{ trans(\Locales::getNamespace() . '/forms.vatLabel') }}: <strong>{{ $booking->vat }}</strong></p>
            <p>{{ trans(\Locales::getNamespace() . '/forms.molLabel') }}: <strong>{{ $booking->mol }}</strong></p>
            <p>{{ trans(\Locales::getNamespace() . '/forms.countryLabel') }}: <strong>{{ $booking->country }}</strong></p>
            <p>{{ trans(\Locales::getNamespace() . '/forms.cityLabel') }}: <strong>{{ $booking->city }}</strong></p>
            <p>{{ trans(\Locales::getNamespace() . '/forms.addressLabel') }}: <strong>{{ $booking->address }}</strong></p>
        @endif
        <hr>
        <p>{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}: <strong>{{ \Carbon\Carbon::parse($booking->from)->format('d.m.Y') }}</strong></p>
        <p>{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}: <strong>{{ \Carbon\Carbon::parse($booking->to)->format('d.m.Y') }}</strong></p>
        <p>{{ trans(\Locales::getNamespace() . '/messages.nights') }}: <strong>{{ $booking->nights }}</strong></p>
        <hr>
        {{-- <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ trans(\Locales::getNamespace() . '/forms.nameLabel') }}</th>
                        <th>{{ trans(\Locales::getNamespace() . '/forms.emailLabel') }}</th>
                        <th>{{ trans(\Locales::getNamespace() . '/forms.phoneLabel') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking->name }}</td>
                        <td>{{ $booking->email }}</td>
                        <td>{{ $booking->phone }}</td>
                    </tr>
                    @if ($booking->message)
                        <tr>
                            <td colspan="3">{!! nl2br($booking->message) !!}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div> --}}
        {{-- <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}</th>
                        <th>{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}</th>
                        <th class="text-center">{{ trans(\Locales::getNamespace() . '/messages.nights') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($booking->from)->format('d.m.Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->to)->format('d.m.Y') }}</td>
                        <td class="text-center">{{ $booking->nights }}</td>
                    </tr>
                </tbody>
            </table>
        </div> --}}
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    @foreach ($booking->rooms as $slug => $room)
                        <tr>
                            <th colspan="5" class="text-center bg-secondary">{{ $booking->roomsArray[$slug]['name'] }}</th>
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
                                <td>{{ $booking->viewsArray[$r['view']]['name'] }}</td>
                                <td>{{ $booking->mealsArray[$r['meal']]['name'] }}</td>
                                <td class="text-center">{{ $r['guests'] }}</td>
                                <td class="text-right">{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($r['priceTotal'], 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($r['priceTotal'] / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="total-price text-right text-primary">{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($booking->price, 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($booking->price / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <hr>
        <p ><strong>{{ trans(\Locales::getNamespace() . '/messages.paidOnline') }}: {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($booking->price / $booking->nights, 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($booking->price / $booking->nights / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}</strong></p>
        <hr>
        {!! trans(\Locales::getNamespace() . '/newsletters.terms') !!}
    </div>
</article>
@endsection
