<div class="subscribe-wrapper">
    <div class="content-wrapper">
        <h1>{{ trans(\Locales::getNamespace() . '/messages.subscribeTitle') }}</h1>
        <p>{{ trans(\Locales::getNamespace() . '/messages.subscribeText') }}</p>
        {!! Form::open(['url' => \Locales::route('subscribe'), 'id' => 'subscribe-form', 'data-alert-container' => 'subscribe-wrapper', 'data-ajax-queue' => 'sync', 'class' => 'form-inline ajax-lock', 'role' => 'form']) !!}
            <div class="form-group{!! ($errors->has('subscribe_email') ? ' has-error has-feedback' : '') !!}">
                {!! Form::label('input-subscribe_email', trans(\Locales::getNamespace() . '/forms.emailLabel'), ['class' => 'sr-only']) !!}
                <div class="input-group">
                    {!! Form::email('subscribe_email', null, ['id' => 'input-subscribe_email', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.emailPlaceholder')]) !!}
                    <span class="input-group-btn">
                        {!! Form::submit(trans(\Locales::getNamespace() . '/forms.subscribeButton'), ['class' => 'btn btn-default']) !!}
                    </span>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
