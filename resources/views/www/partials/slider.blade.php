@if (isset($slider) && count($slider) > 0)
    <section class="slider-pro" id="slider-header" style="width: 100%; height: {{ \Config::get('upload.sliderHeight') }}px">
        <div class="sp-slides">
        @foreach ($slider as $image)
            <div class="sp-slide">{!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/blank.gif'), $image->name, ['class' => 'sp-image', 'title' => $image->title, 'data-src' => \App\Helpers\autover('/upload/' . $model->getTable() . '/' . \Locales::getCurrent() . '/' . ($model->parent_slug ? $model->parent_slug . '/' : '') . $model->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . \Config::get('upload.sliderDirectory') . '/' . $image->file), 'data-small' => \App\Helpers\autover('/upload/' . $model->getTable() . '/' . \Locales::getCurrent() . '/' . ($model->parent_slug ? $model->parent_slug . '/' : '') . $model->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . \Config::get('upload.sliderSmallDirectory') . '/' . $image->file), 'data-medium' => \App\Helpers\autover('/upload/' . $model->getTable() . '/' . \Locales::getCurrent() . '/' . ($model->parent_slug ? $model->parent_slug . '/' : '') . $model->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . \Config::get('upload.sliderMediumDirectory') . '/' . $image->file), 'data-large' => \App\Helpers\autover('/upload/' . $model->getTable() . '/' . \Locales::getCurrent() . '/' . ($model->parent_slug ? $model->parent_slug . '/' : '') . $model->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . \Config::get('upload.sliderLargeDirectory') . '/' . $image->file)]) !!}</div>
        @endforeach
        </div>
    </section>
    @if (isset($model) && $model->type == 'home')
        <section class="bookings-home-overlay">
            <div class="bookings-home-overlay-form">
                {!! Form::open(['url' => \Locales::route('book', '2'), 'id' => 'contact-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock booking-home-wrapper book-form', 'role' => 'form']) !!}
                    <div class="booking-input-field">
                        {!! Form::label('input-dfrom', trans(\Locales::getNamespace() . '/forms.dfromLabel'), ['class' => 'sr-only']) !!}
                        <i class="glyphicon glyphicon-calendar"></i>
                        {!! Form::text('dfrom', null, ['id' => 'input-dfrom', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.dfromPlaceholder'), 'autocomplete' => 'off']) !!}
                    </div>
                    <div class="booking-input-field">
                        {!! Form::label('input-dto', trans(\Locales::getNamespace() . '/forms.dtoLabel'), ['class' => 'sr-only']) !!}
                        <i class="glyphicon glyphicon-calendar"></i>
                        {!! Form::text('dto', null, ['id' => 'input-dto', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.dtoPlaceholder'), 'autocomplete' => 'off', 'disabled']) !!}
                    </div>
                    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.bookButton'), ['class' => 'btn btn-primary', 'id' => 'booking-step1', 'disabled']) !!}
                {!! Form::close() !!}
            </div>
        </section>
    @endif
@elseif (isset($model) && $model->type == 'banner')
    <section class="slider-pro" id="slider-header" style="width: 100%; height: {{ \Config::get('upload.sliderHeight') }}px">
        <div class="sp-slides">
            <div class="sp-slide">{!! HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/blank.gif'), $model->name, ['class' => 'sp-image', 'title' => $model->title, 'data-src' => \App\Helpers\autover('/upload/' . $model->getTable() . '/' . \Locales::getCurrent() . '/' . $model->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $model->image->first()->uuid . '/' . \Config::get('upload.bannerDirectory') . '/' . $model->image->first()->file), 'data-small' => \App\Helpers\autover('/upload/' . $model->getTable() . '/' . \Locales::getCurrent() . '/' . $model->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $model->image->first()->uuid . '/' . \Config::get('upload.bannerSmallDirectory') . '/' . $model->image->first()->file), 'data-medium' => \App\Helpers\autover('/upload/' . $model->getTable() . '/' . \Locales::getCurrent() . '/' . $model->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $model->image->first()->uuid . '/' . \Config::get('upload.bannerMediumDirectory') . '/' . $model->image->first()->file), 'data-large' => \App\Helpers\autover('/upload/' . $model->getTable() . '/' . \Locales::getCurrent() . '/' . $model->slug . '/' . \Config::get('upload.imagesDirectory') . '/' . $model->image->first()->uuid . '/' . \Config::get('upload.bannerLargeDirectory') . '/' . $model->image->first()->file)]) !!}</div>
        </div>
    </section>
@elseif (isset($model) && $model->type == 'contact')
    {{-- <div class="contact-map-wrapper" style="height: {{ \Config::get('upload.sliderHeight') }}px">
        <div id="map"></div>
    </div> --}}
@endif
