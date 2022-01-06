@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($page))
    {!! Form::model($page, ['method' => 'put', 'url' => \Locales::route('rooms/update'), 'id' => 'edit-page-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('rooms/store'), 'id' => 'create-page-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <div class="form-group">
        {!! Form::label('input-name', trans(\Locales::getNamespace() . '/forms.nameLabel')) !!}
        {!! Form::text('name', null, ['id' => 'input-name', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.namePlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-slug', trans(\Locales::getNamespace() . '/forms.slugLabel')) !!}
        {!! Form::text('slug', null, ['id' => 'input-slug', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.slugPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-area', trans(\Locales::getNamespace() . '/forms.areaLabel')) !!}
        {!! Form::text('area', null, ['id' => 'input-area', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.areaPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-capacity', trans(\Locales::getNamespace() . '/forms.capacityLabel')) !!}
        {!! Form::text('capacity', null, ['id' => 'input-capacity', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.capacityPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-adults', trans(\Locales::getNamespace() . '/forms.adultsLabel')) !!}
        {!! Form::text('adults', null, ['id' => 'input-adults', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.adultsPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-children', trans(\Locales::getNamespace() . '/forms.childrenLabel')) !!}
        {!! Form::text('children', null, ['id' => 'input-children', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.childrenPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-infants', trans(\Locales::getNamespace() . '/forms.infantsLabel')) !!}
        {!! Form::text('infants', null, ['id' => 'input-infants', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.infantsPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-order', trans(\Locales::getNamespace() . '/forms.orderLabel')) !!}
        {!! Form::text('order', null, ['id' => 'input-order', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.orderPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-content', trans(\Locales::getNamespace() . '/forms.contentLabel')) !!}
        {!! Form::textarea('content', null, ['id' => 'input-content', 'class' => 'form-control ckeditor', 'placeholder' => trans(\Locales::getNamespace() . '/forms.contentPlaceholder')]) !!}
    </div>

    @if (isset($page))
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.updateButton'), ['class' => 'btn btn-warning btn-block']) !!}
    @else
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.storeButton'), ['class' => 'btn btn-primary btn-block']) !!}
    @endif

    {!! Form::close() !!}
</div>
@endsection
