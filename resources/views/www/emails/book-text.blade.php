{{ trans(\Locales::getNamespace() . '/newsletters.title') . ' #SRB' . date('Y') . '-' . $booking->id }}
####################

--------------------
{{ trans(\Locales::getNamespace() . '/messages.step3-active') }}
--------------------

{{ trans(\Locales::getNamespace() . '/forms.nameLabel') }}: {{ $booking->name }}
{{ trans(\Locales::getNamespace() . '/forms.emailLabel') }}: {{ $booking->email }}
{{ trans(\Locales::getNamespace() . '/forms.phoneLabel') }}: {{ $booking->phone }}
@if ($booking->message){{ $booking->message }}@endif

@if ($booking->company)
{{ trans(\Locales::getNamespace() . '/forms.companyLabel') }}: {{ $booking->company }}
{{ trans(\Locales::getNamespace() . '/forms.eikLabel') }}: {{ $booking->eik }}
{{ trans(\Locales::getNamespace() . '/forms.vatLabel') }}: {{ $booking->vat }}
{{ trans(\Locales::getNamespace() . '/forms.molLabel') }}: {{ $booking->mol }}
{{ trans(\Locales::getNamespace() . '/forms.countryLabel') }}: {{ $booking->country }}
{{ trans(\Locales::getNamespace() . '/forms.cityLabel') }}: {{ $booking->city }}
{{ trans(\Locales::getNamespace() . '/forms.addressLabel') }}: {{ $booking->address }}
@endif

--------------------
{{ trans(\Locales::getNamespace() . '/messages.step1-active') }}
--------------------

{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}: {{ \Carbon\Carbon::parse($booking->from)->format('d.m.Y') }}
{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}: {{ \Carbon\Carbon::parse($booking->to)->format('d.m.Y') }}
{{ trans(\Locales::getNamespace() . '/messages.nights') }}: {{ $booking->nights }}

--------------------
{{ trans(\Locales::getNamespace() . '/messages.step2-active') }}
--------------------

 @foreach ($booking->rooms as $slug => $room)
    @foreach ($room as $id => $r)
{{ trans(\Locales::getNamespace() . '/forms.roomLabel') }}: {{ $booking->roomsArray[$slug]['name'] . ' #' . $id }}
{{ trans(\Locales::getNamespace() . '/forms.viewLabel') }}: {{ $booking->viewsArray[$r['view']]['name'] }}
{{ trans(\Locales::getNamespace() . '/forms.mealLabel') }}: {{ $booking->mealsArray[$r['meal']]['name'] }}
{{ trans(\Locales::getNamespace() . '/forms.guestsLabel') }}: {{ $r['guests'] }}
{{ trans(\Locales::getNamespace() . '/forms.priceLabel') }}: {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($r['priceTotal'], 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($r['priceTotal'] / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}
    @endforeach
@endforeach

{{ trans(\Locales::getNamespace() . '/messages.grandTotal') }}: {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($booking->price, 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($booking->price / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}
{{ trans(\Locales::getNamespace() . '/messages.paidOnline') }}: {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($booking->amount, 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($booking->amount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}

{!! trans(\Locales::getNamespace() . '/newsletters.terms') !!}
