@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1 class="text-center">{{ \Locales::getMetaTitle() }}</h1>

    <p class="text-center">Поръчка номер: {{ $transaction->id }}</p>

    {{-- @if (Session::get('success'))
        <div class="alert-messages">
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
                <ul>
                    <li><span class="glyphicon glyphicon-ok"></span>{{ Session::get('success') }}</li>
                </ul>
            </div>
        </div>
    @else
        @if (Session::get('error'))
            <div class="alert-messages">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
                    <ul>
                        <li><span class="glyphicon glyphicon-warning-sign"></span>{{ Session::get('error') }}</li>
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="https://3dsgate.borica.bg/cgi-bin/cgi_link" accept-charset="UTF-8" id="borica-form" class="none normal" role="form">
            <input id="BACKREF" name="BACKREF" type="hidden" value="{{ $fields['BACKREF'] }}">
            <input id="AMOUNT" name="AMOUNT" type="hidden" value="{{ $fields['AMOUNT'] }}">
            <input id="CURRENCY" name="CURRENCY" type="hidden" value="{{ $fields['CURRENCY'] }}">
            <input id="TERMINAL" name="TERMINAL" type="hidden" value="{{ $fields['TERMINAL'] }}">
            <input id="MERCH_NAME" name="MERCH_NAME" type="hidden" value="{{ $fields['MERCH_NAME'] }}">
            <input id="MERCH_URL" name="MERCH_URL" type="hidden" value="{{ $fields['MERCH_URL'] }}">
            <input id="MERCHANT" name="MERCHANT" type="hidden" value="{{ $fields['MERCHANT'] }}">
            <input id="EMAIL" name="EMAIL" type="hidden" value="{{ $fields['EMAIL'] }}">
            <input id="TRTYPE" name="TRTYPE" type="hidden" value="{{ $fields['TRTYPE'] }}">
            <input id="ORDER" name="ORDER" type="hidden" value="{{ $fields['ORDER'] }}">
            <input id="AD.CUST_BOR_ORDER_ID" name="AD.CUST_BOR_ORDER_ID" type="hidden" value="{{ $fields['ORDER_ID'] }}">
            <input id="COUNTRY" name="COUNTRY" type="hidden" value="{{ $fields['COUNTRY'] }}">
            <input id="TIMESTAMP" name="TIMESTAMP" type="hidden" value="{{ $fields['TIMESTAMP'] }}">
            <input id="MERCH_GMT" name="MERCH_GMT" type="hidden" value="{{ $fields['MERCH_GMT'] }}">
            <input id="NONCE" name="NONCE" type="hidden" value="{{ $fields['NONCE'] }}">
            <input id="ADDENDUM" name="ADDENDUM" type="hidden" value="{{ $fields['ADDENDUM'] }}">
            <input id="RRN" name="RRN" type="hidden" value="{{ $fields['RRN'] }}">
            <input id="INT_REF" name="INT_REF" type="hidden" value="{{ $fields['INT_REF'] }}">
            <input id="DESC" name="DESC" type="hidden" value="{{ $fields['DESC'] }}">
            <input id="P_SIGN" name="P_SIGN" type="hidden" value="{{ $fields['P_SIGN'] }}">
            <input type="submit" value="Изпрати заявка за връщане на парите" class="btn btn-warning" />
        </form>
        <br><br>
    @endif --}}

    <div class="text-center">
        {!! Form::open(['method' => 'put', 'url' => \Locales::route('dashboard/refund', ['transaction' => $transaction->id]), 'id' => 'refund-transaction-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
        {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}
        {!! Form::submit(trans(\Locales::getNamespace() . '/forms.refundButton'), ['class' => 'btn btn-danger']) !!}
        {!! Form::close() !!}
    </div>
    <br><br>
    <div class="text-center">
        {!! Form::open(['method' => 'delete', 'url' => \Locales::route('dashboard/cancelled'), 'id' => 'cancelled-transaction-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
        {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}
        {!! Form::submit(trans(\Locales::getNamespace() . '/forms.cancelledButton'), ['class' => 'btn btn-warning']) !!}
        {!! Form::close() !!}
    </div>
</div>
@endsection
