@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($page))
    {!! Form::model($page, ['method' => 'put', 'url' => \Locales::route('nav-guests/update'), 'id' => 'edit-page-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('nav-guests/store'), 'id' => 'create-page-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <div class="form-group">
        {!! Form::label('input-name', trans(\Locales::getNamespace() . '/forms.nameLabel')) !!}
        {!! Form::text('name', null, ['id' => 'input-name', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.namePlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-title', trans(\Locales::getNamespace() . '/forms.titleLabel')) !!}
        {!! Form::text('title', null, ['id' => 'input-title', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.titlePlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-slug', trans(\Locales::getNamespace() . '/forms.slugLabel')) !!}
        {!! Form::text('slug', null, ['id' => 'input-slug', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.slugPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-description', trans(\Locales::getNamespace() . '/forms.descriptionLabel')) !!}
        {!! Form::text('description', null, ['id' => 'input-description', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.descriptionPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-order', trans(\Locales::getNamespace() . '/forms.orderLabel')) !!}
        {!! Form::text('order', null, ['id' => 'input-order', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.orderPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-type', trans(\Locales::getNamespace() . '/forms.typeLabel')) !!}
        {!! Form::multiselect('type', $multiselect['type'], ['id' => 'input-type', 'class' => 'form-control']) !!}
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

    <script>
    @section('script')
        unikat.multiselect = {
            'input-type': {
                multiple: false,
            },
        };
    @show
    </script>
</div>
@endsection
