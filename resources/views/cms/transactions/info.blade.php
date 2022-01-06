@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1 class="text-center">{{ \Locales::getMetaTitle() }}</h1>

    @if ($status)
        <p class="lead">{{ $status }}</p>
    @endif

    @if ($info)
        <table class="table">
            <thead>
                <tr>
                    <th>КЛЮЧ</th>
                    <th>СТОЙНОСТ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($info as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{!! ($key == 'RC' && array_key_exists($value, trans('messages.errCodes'))) ? $value . '<br>' . trans('messages.errCodes')[$value] : $value !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2 class="text-center">Първоначална заявка</h2>

    <table class="table">
        <thead>
            <tr>
                <th>КЛЮЧ</th>
                <th>СТОЙНОСТ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>MERCHANT</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">9200200117</td>
            </tr>
            <tr>
                <td>TERMINAL</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">V6200020</td>
            </tr>
            <tr>
                <td>TYPE</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->type }}</td>
            </tr>
            <tr>
                <td>ORDER</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ str_pad($transaction->order, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>AMOUNT</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->amount }}</td>
            </tr>
            <tr>
                <td>DESCRIPTION</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->description }}</td>
            </tr>
            <tr>
                <td>GMT</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->gmt }}</td>
            </tr>
            <tr>
                <td>MERCHANT_TIMESTAMP</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->merchant_timestamp }}</td>
            </tr>
            <tr>
                <td>MERCHANT_NONCE</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->merchant_nonce }}</td>
            </tr>
            <tr>
                <td>MERCHANT_SIGNATURE</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->merchant_signature }}</td>
            </tr>
            <tr>
                <td>CURRENCY</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">BGN</td>
            </tr>
            <tr>
                <td>COUNTRY</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">BG</td>
            </tr>
            {{-- <tr>
                <td>status_msg</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->status_msg }}</td>
            </tr>
            <tr>
                <td>card</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->card }}</td>
            </tr> --}}
        </tbody>
    </table>

    <h2 class="text-center">Първоначален отговор</h2>

    <table class="table">
        <thead>
            <tr>
                <th>КЛЮЧ</th>
                <th>СТОЙНОСТ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ACTION</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->action }}</td>
            </tr>
            <tr>
                <td>RC</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->rc }}</td>
            </tr>
            <tr>
                <td>APPROVAL</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->approval }}</td>
            </tr>
            <tr>
                <td>RRN</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->rrn }}</td>
            </tr>
            <tr>
                <td>INT_REF</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->int_ref }}</td>
            </tr>
            <tr>
                <td>BORICA_TIMESTAMP</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->borica_timestamp }}</td>
            </tr>
            <tr>
                <td>PARES_STATUS</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->pares_status }}</td>
            </tr>
            <tr>
                <td>ECI</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->eci }}</td>
            </tr>
            <tr>
                <td>STATUS_MSG</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->status_msg }}</td>
            </tr>
            <tr>
                <td>CARD</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->card }}</td>
            </tr>
            <tr>
                <td>BORICA_NONCE</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->borica_nonce }}</td>
            </tr>
            <tr>
                <td>BORICA_SIGNATURE</td>
                <td class="text-break" style="overflow-wrap: break-word; word-break: break-word;">{{ $transaction->borica_signature }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
