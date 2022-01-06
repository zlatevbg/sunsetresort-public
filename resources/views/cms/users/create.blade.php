@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($user))
    {!! Form::model($user, ['method' => 'put', 'url' => \Locales::route('users/update'), 'id' => 'edit-user-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('users/store'), 'id' => 'create-user-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <div class="form-group">
        {!! Form::label('input-group', trans(\Locales::getNamespace() . '/forms.groupLabel')) !!}
        {!! Form::select('group', $roles, $group, ['id' => 'input-group', 'class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-name', trans(\Locales::getNamespace() . '/forms.nameLabel')) !!}
        {!! Form::text('name', null, ['id' => 'input-name', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.namePlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-email', trans(\Locales::getNamespace() . '/forms.emailLabel')) !!}
        {!! Form::email('email', null, ['id' => 'input-email', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.emailPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-password', trans(\Locales::getNamespace() . '/forms.passwordLabel')) !!}
        {!! Form::password('password', ['id' => 'input-password', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.passwordPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-password_confirmation', trans(\Locales::getNamespace() . '/forms.confirmPasswordLabel')) !!}
        {!! Form::password('password_confirmation', ['id' => 'input-password_confirmation', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.confirmPasswordPlaceholder')]) !!}
    </div>

    @if (isset($user))
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.updateButton'), ['class' => 'btn btn-warning btn-block']) !!}
    @else
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.storeButton'), ['class' => 'btn btn-primary btn-block']) !!}
    @endif

    {!! Form::close() !!}
</div>
@endsection
