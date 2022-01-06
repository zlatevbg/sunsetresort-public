@extends(\Locales::getNamespace() . '.master')

@section('content')
    <article class="content-wrapper article">
        <h1>{{ $model->title }}</h1>
        <div class="dots"><span></span><span></span><span></span><span></span></div>
        <div class="text">{!! $model->content !!}</div>
        <section>
            <h1>{{ trans(\Locales::getNamespace() . '/messages.contactForm') }}</h1>

            {!! Form::open(['url' => \Locales::route('contact'), 'id' => 'contact-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}

            <div class="clearfix">
                <div class="input-25">
                    <div class="form-group">
                        {!! Form::label('input-department', trans(\Locales::getNamespace() . '/forms.departmentLabel')) !!}
                        {!! Form::multiselect('department', $multiselect['department'], ['id' => 'input-department', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="input-25">
                    <div class="form-group">
                        {!! Form::label('input-name', trans(\Locales::getNamespace() . '/forms.nameLabel')) !!}
                        {!! Form::text('name', null, ['id' => 'input-name', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.namePlaceholder')]) !!}
                    </div>
                </div>
                <div class="input-25">
                    <div class="form-group">
                        {!! Form::label('input-email', trans(\Locales::getNamespace() . '/forms.emailLabel')) !!}
                        {!! Form::email('email', null, ['id' => 'input-email', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.emailPlaceholder')]) !!}
                    </div>
                </div>
                <div class="input-25">
                    <div class="form-group">
                        {!! Form::label('input-phone', trans(\Locales::getNamespace() . '/forms.phoneLabel')) !!}
                        {!! Form::text('phone', null, ['id' => 'input-phone', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.phonePlaceholder')]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('input-message', trans(\Locales::getNamespace() . '/forms.messageLabel')) !!}
                {!! Form::textarea('message', null, ['id' => 'input-message', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.messagePlaceholder')]) !!}
            </div>

            <div class="g-recaptcha" data-sitekey="{{ \Config::get('services.recaptcha.key') }}"></div>

            <div class="text-center">{!! Form::submit(trans(\Locales::getNamespace() . '/forms.contactButton'), ['class' => 'btn btn-primary']) !!}</div>

            {!! Form::close() !!}
        </section>
    </article>
    <div class="contact-map-wrapper" style="height: {{ \Config::get('upload.sliderHeight') }}px">
        <div id="map"></div>
    </div>
@endsection

@section('jsFiles')
    jsFiles.push('https://maps.googleapis.com/maps/api/js?key=AIzaSyDp1A1DQ8yyVO9yiTZtZFEjeGaD-Ao-81U&language={{ \Locales::getCurrent() }}');
    jsFiles.push('https://www.google.com/recaptcha/api.js?hl={{ \Locales::getCurrent() }}');
    jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jquery-ui.min.js') }}');
    jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jquery.multiselect.js') }}');

    @parent
@endsection

@section('script')
    unikat.multiselect = {
        'input-department': {
            multiple: false,
        },
    };

    var myLatLng = {lat: 42.563587, lng: 27.604003};

    var map = new google.maps.Map(document.getElementById('map'), {
        center: myLatLng,
        scrollwheel: false,
        zoom: 15,
    });

    var marker = new google.maps.Marker({
        map: null,
        animation: google.maps.Animation.DROP,
        position: myLatLng,
        title: '{{ trans(\Locales::getNamespace() . '/messages.name') }}',
    });

    window.setTimeout(function() {
        marker.setMap(map);
    }, 2000);

    var infoWindow = new google.maps.InfoWindow({
        content: '{{ trans(\Locales::getNamespace() . '/messages.name') }}',
        maxWidth: 300,
    })
    infoWindow.open(map, marker);

    marker.addListener('click', function() {
        infoWindow.open(map, marker);
    });

    google.maps.event.addDomListener(window, 'resize', function() {
        var center = map.getCenter();
        google.maps.event.trigger(map, 'resize');
        map.setCenter(center);
    });
@endsection
