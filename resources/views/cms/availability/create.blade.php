@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($period))
    {!! Form::model($period, ['method' => 'put', 'url' => \Locales::route('availability/update'), 'id' => 'edit-availability-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('availability/store'), 'id' => 'create-availability-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <div class="clearfix">
        <div class="form-group-left">
            <div class="form-group">
                {!! Form::label('input-dfrom', trans(\Locales::getNamespace() . '/forms.dfromLabel')) !!}
                {!! Form::text('dfrom', null, ['id' => 'input-dfrom', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.dfromPlaceholder'), 'autocomplete' => 'off']) !!}
            </div>
        </div>
        <div class="form-group-right">
            <div class="form-group">
                {!! Form::label('input-dto', trans(\Locales::getNamespace() . '/forms.dtoLabel')) !!}
                {!! Form::text('dto', null, ['id' => 'input-dto', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.dtoPlaceholder'), 'autocomplete' => 'off'] + ((isset($period) && $period->dfrom) ? [] : ['disabled'])) !!}
            </div>
        </div>
    </div>

    <table class="table table-avaiiability">
        <thead>
            <tr>
                <th>{!! Form::label(null, trans(\Locales::getNamespace() . '/forms.roomLabel')) !!}</th>
                @foreach ($views as $view)
                    <th class="text-center">{{ $view->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
        @foreach ($rooms as $room)
            <tr>
                <td>{{ $room->name }}</td>
                @foreach ($views as $view)
                    <td>
                        @if (in_array($room->slug, ['studio', 'one-bed-economy', 'two-bed-economy']) && $view->slug == 'sea')
                            {{-- skip --}}
                        @else
                            <div class="form-group form-inline text-center">
                                <div class="input-group input-group-availability"><span class="input-group-addon" id="availability-{{ $view->slug }}">{{ trans(\Locales::getNamespace() . '/forms.availabilityLabel') }}</span>
                                    {!! Form::text('availability[' . $room->slug . '][' . $view->slug . ']', (isset($period) && isset($availability[$room->slug][$view->slug])) ? $availability[$room->slug][$view->slug] : null, ['id' => 'input-availability', 'class' => 'form-control', 'aria-describedby' => 'availability-' . $view->slug]) !!} {{-- (isset($period) && isset($availability[$room->slug][$view->slug])) ? $availability[$room->slug][$view->slug] :  --}}
                                </div>
                                <div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-{{ $view->slug }}">Min stay</span>
                                    {!! Form::text('min_stay[' . $room->slug . '][' . $view->slug . ']', (isset($period) && isset($min_stay[$room->slug][$view->slug])) ? $min_stay[$room->slug][$view->slug] : null, ['id' => 'input-min_stay', 'class' => 'form-control', 'aria-describedby' => 'min-stay-' . $view->slug]) !!} {{-- (isset($period) && isset($min_stay[$room->slug][$view->slug])) ? $min_stay[$room->slug][$view->slug] :  --}}
                                </div>
                            </div>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    @if (isset($period))
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.updateButton'), ['class' => 'btn btn-warning btn-block']) !!}
    @else
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.storeButton'), ['class' => 'btn btn-primary btn-block']) !!}
    @endif

    {!! Form::close() !!}

    <script>
    @section('script')
        var dates = {!! json_encode($dates) !!};

        $.datepicker.regional.{{ \Locales::getCurrent() }} = unikat.variables.datepicker.{{ \Locales::getCurrent() }};
        $.datepicker.setDefaults($.datepicker.regional.{{ \Locales::getCurrent() }});

        function addZ(n) {
            return n < 10 ? '0' + n : '' + n;
        }

        var highlightLast = false;

        $('#input-dfrom').datepicker({
            minDate: 0,
            onSelect: function(date) {
                var d = new Date(Date.parse($("#input-dfrom").datepicker("getDate")));
                $('#input-dto').datepicker('option', 'minDate', d);

                @if (!isset($period))
                    for (var i = 0; i < dates.length; i++) {
                        var maxDate = new Date(dates[i].dfrom.substr(0, 4), parseInt(dates[i].dfrom.substr(4, 2)) - 1, dates[i].dfrom.substr(6, 2));
                        if (d < maxDate) {
                            $('#input-dto').datepicker('option', 'maxDate', maxDate);
                            break;
                        }
                    }
                @endif
                $('#input-dto').removeAttr('disabled');
            },
            beforeShowDay: @if (!isset($period)) function(date) {
                var current = date.getFullYear() + '' + addZ(date.getMonth() + 1) + '' + addZ(date.getDate());

                /*if (highlightLast) {
                    highlightLast = false;
                    return [false, 'booked-day']; // return [true, 'last-day'];
                }*/

                for (var i = 0; i < dates.length; i++) {
                    if (current == dates[i].dfrom) {
                        return [false, 'booked-day']; // return [true, 'first-day'];
                    } else if (current > dates[i].dfrom && current < dates[i].dto) {
                        /*if (parseInt(current) + 1 == dates[i].dto) {
                            highlightLast = true;
                        }*/

                        return [false, 'booked-day'];
                    }
                }
                return [true, 'available-day'];
            } @else null @endif,
        });

        $('#input-dto').datepicker({
            minDate: $("#input-dfrom").datepicker("getDate") ? new Date(Date.parse($("#input-dfrom").datepicker("getDate"))) : null,
            beforeShowDay: @if (!isset($period)) function(date) {
                var current = date.getFullYear() + '' + addZ(date.getMonth() + 1) + '' + addZ(date.getDate());

                if (highlightLast) {
                    highlightLast = false;
                    return [false, 'booked-day'];
                }

                for (var i = 0; i < dates.length; i++) {
                    if (current == dates[i].dfrom) {
                        return [false, 'booked-day']; // return [true, 'first-day'];
                    } else if (current > dates[i].dfrom && current < dates[i].dto) {
                        if (parseInt(current) + 1 == dates[i].dto) {
                            highlightLast = true;
                        }

                        return [false, 'booked-day'];
                    }
                }
                return [true, 'available-day'];
            } @else null @endif,
        });
    @show
    </script>
</div>
@endsection
