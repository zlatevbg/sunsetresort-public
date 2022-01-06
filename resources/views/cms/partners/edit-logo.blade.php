@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    {!! Form::model($logo, ['method' => 'put', 'url' => \Locales::route('partners/update-logo'), 'id' => 'edit-logo-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <div class="form-group">
        {!! Form::label('input-name', trans(\Locales::getNamespace() . '/forms.nameLabel')) !!}
        {!! Form::text('name', null, ['id' => 'input-name', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.namePlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-order', trans(\Locales::getNamespace() . '/forms.orderLabel')) !!}
        {!! Form::text('order', null, ['id' => 'input-order', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.orderPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-url', trans(\Locales::getNamespace() . '/forms.urlLabel')) !!}
        {!! Form::text('url', null, ['id' => 'input-url', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.urlPlaceholder')]) !!}
    </div>

    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.updateButton'), ['class' => 'btn btn-warning btn-block']) !!}

    {!! Form::close() !!}
</div>
@endsection
