@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($page))
    {!! Form::model($page, ['method' => 'put', 'url' => \Locales::route('nav/update'), 'id' => 'edit-category-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('nav/store'), 'id' => 'create-category-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}
    {!! Form::hidden('is_category', 1, ['id' => 'input-is_category']) !!}

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
        {!! Form::checkboxInline('is_dropdown', 1, null, ['id' => 'input-is_dropdown'], trans(\Locales::getNamespace() . '/forms.isDropdownOption'), ['class' => 'checkbox-inline']) !!}
    </div>

    <div class="form-group">
        {!! Form::checkboxInline('is_multi_page', 1, null, ['id' => 'input-is_multi_page'], trans(\Locales::getNamespace() . '/forms.isMultiPageOption'), ['class' => 'checkbox-inline']) !!}
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
