@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1 class="text-center">{{ \Locales::getMetaTitle() }}</h1>

    {!! Form::open(['method' => 'delete', 'url' => \Locales::route('transactions/paid'), 'id' => 'paid-transaction-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <p class="text-center">Поръчка номер: {{ $transaction->id }}</p>

    <div class="form-group">
        {!! Form::label('input-payment', trans(\Locales::getNamespace() . '/forms.paymentLabel')) !!}
        {!! Form::text('payment', null, ['id' => 'input-payment', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.paymentPlaceholder')]) !!}
    </div>

    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.payButton'), ['class' => 'btn btn-info btn-block']) !!}
    {!! Form::close() !!}
</div>
@endsection
