@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($testimonial))
    {!! Form::model($testimonial, ['method' => 'put', 'url' => \Locales::route('testimonials/update'), 'id' => 'edit-testimonial-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('testimonials/store'), 'id' => 'create-testimonial-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <div class="form-group">
        {!! Form::label('input-name', trans(\Locales::getNamespace() . '/forms.nameLabel')) !!}
        {!! Form::text('name', null, ['id' => 'input-name', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.namePlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-country', trans(\Locales::getNamespace() . '/forms.countryLabel')) !!}
        {!! Form::text('country', null, ['id' => 'input-country', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.countryPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-order', trans(\Locales::getNamespace() . '/forms.orderLabel')) !!}
        {!! Form::text('order', null, ['id' => 'input-order', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.orderPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-content', trans(\Locales::getNamespace() . '/forms.contentLabel')) !!}
        {!! Form::textarea('content', null, ['id' => 'input-content', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.contentPlaceholder')]) !!}
    </div>

    @if (isset($testimonial))
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.updateButton'), ['class' => 'btn btn-warning btn-block']) !!}
    @else
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.storeButton'), ['class' => 'btn btn-primary btn-block']) !!}
    @endif

    {!! Form::close() !!}
</div>
@endsection
