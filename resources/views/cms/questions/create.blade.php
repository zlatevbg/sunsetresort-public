@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($question))
    {!! Form::model($question, ['method' => 'put', 'url' => \Locales::route('questions/update'), 'id' => 'edit-question-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('questions/store'), 'id' => 'create-question-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <div class="form-group">
        {!! Form::label('input-question', trans(\Locales::getNamespace() . '/forms.questionLabel')) !!}
        {!! Form::text('question', null, ['id' => 'input-question', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.questionPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-answer', trans(\Locales::getNamespace() . '/forms.answerLabel')) !!}
        {!! Form::textarea('answer', null, ['id' => 'input-answer', 'class' => 'form-control ckeditor', 'placeholder' => trans(\Locales::getNamespace() . '/forms.answerPlaceholder')]) !!}
    </div>

    <div class="form-group">
        {!! Form::label('input-order', trans(\Locales::getNamespace() . '/forms.orderLabel')) !!}
        {!! Form::text('order', null, ['id' => 'input-order', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.orderPlaceholder')]) !!}
    </div>

    @if (isset($question))
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.updateButton'), ['class' => 'btn btn-warning btn-block']) !!}
    @else
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.storeButton'), ['class' => 'btn btn-primary btn-block']) !!}
    @endif

    {!! Form::close() !!}
</div>
@endsection
