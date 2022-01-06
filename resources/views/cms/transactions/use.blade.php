@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1 class="text-center">{{ \Locales::getMetaTitle() }}</h1>

    {!! Form::open(['method' => 'delete', 'url' => \Locales::route('transactions/used'), 'id' => 'used-transaction-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.useButton'), ['class' => 'btn btn-success btn-block']) !!}
    {!! Form::close() !!}
</div>
@endsection
