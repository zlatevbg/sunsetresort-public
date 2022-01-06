@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    <h1>{{ \Locales::getMetaTitle() }}</h1>

    @if (isset($period))
    {!! Form::model($period, ['method' => 'put', 'url' => \Locales::route('discounts/update'), 'id' => 'edit-discount-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @else
    {!! Form::open(['url' => \Locales::route('discounts/store'), 'id' => 'create-discount-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock', 'role' => 'form']) !!}
    @endif

    {!! Form::hidden('table', $table, ['id' => 'input-table']) !!}

    <div class="clearfix">
        <div class="form-group-3-left">
            <div class="form-group">
                {!! Form::label('input-dfrom', trans(\Locales::getNamespace() . '/forms.dfromLabel')) !!}
                {!! Form::text('dfrom', null, ['id' => 'input-dfrom', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.dfromPlaceholder'), 'autocomplete' => 'off']) !!}
            </div>
        </div>
        <div class="form-group-3-left">
            <div class="form-group">
                {!! Form::label('input-dto', trans(\Locales::getNamespace() . '/forms.dtoLabel')) !!}
                {!! Form::text('dto', null, ['id' => 'input-dto', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.dtoPlaceholder'), 'autocomplete' => 'off'] + ((isset($period) && $period->dfrom) ? [] : ['disabled'])) !!}
            </div>
        </div>
        <div class="form-group-3-right">
            <div class="form-group">
                {!! Form::label('input-discount', trans(\Locales::getNamespace() . '/forms.discountLabel')) !!}
                {!! Form::text('discount', null, ['id' => 'input-discount', 'class' => 'form-control', 'placeholder' => trans(\Locales::getNamespace() . '/forms.discountPlaceholder')]) !!}
            </div>
        </div>
    </div>

    @foreach ($rooms as $room)
        <p class="text-center form-control form-control-heading">{{ $room->name }}</p>
        <table class="table table-bordered table-prices">
            <thead>
                <tr>
                    <th></th>
                    @foreach ($meals as $meal)
                        @if ($meal->slug == 'sc')
                            @if (in_array($room->slug, ['one-bed-economy', 'two-bed-economy']))
                                <th class="text-center">{{ $meal->name }}</th>
                            @endif
                        @else
                            @if (!in_array($room->slug, ['one-bed-economy', 'two-bed-economy']))
                                <th class="text-center">{{ $meal->name }}</th>
                            @endif
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach ($views as $view)
                @if (in_array($room->slug, ['studio', 'one-bed-economy', 'two-bed-economy']) && $view->slug == 'sea')
                    {{-- skip --}}
                @else
                    <tr>
                        <td class="text-center">{{ $view->name }}</td>
                        @foreach ($meals as $meal)
                            @if ($meal->slug == 'sc')
                                @if (in_array($room->slug, ['one-bed-economy', 'two-bed-economy']))
                                    <td>
                                        <div class="form-group">
                                            {!! Form::label('input-discounts-' . $room->slug . '-' . $view->slug . '-' . $meal->slug, trans(\Locales::getNamespace() . '/forms.discountLabel')) !!}
                                            {!! Form::text('discounts[' . $room->slug . '][' . $view->slug . '][' . $meal->slug . '][discount]', (isset($discounts[$room->slug][$view->slug][$meal->slug]['discount'])) ? $discounts[$room->slug][$view->slug][$meal->slug]['discount'] : null, ['id' => 'input-discounts-' . $room->slug . '-' . $view->slug . '-' . $meal->slug, 'class' => 'form-control']) !!}
                                        </div>
                                    </td>
                                @endif
                            @else
                                @if (!in_array($room->slug, ['one-bed-economy', 'two-bed-economy']))
                                    <td>
                                        <div class="form-group">
                                            {!! Form::label('input-discounts-' . $room->slug . '-' . $view->slug . '-' . $meal->slug, trans(\Locales::getNamespace() . '/forms.discountLabel')) !!}
                                            {!! Form::text('discounts[' . $room->slug . '][' . $view->slug . '][' . $meal->slug . '][discount]', (isset($discounts[$room->slug][$view->slug][$meal->slug]['discount'])) ? $discounts[$room->slug][$view->slug][$meal->slug]['discount'] : null, ['id' => 'input-discounts-' . $room->slug . '-' . $view->slug . '-' . $meal->slug, 'class' => 'form-control']) !!}
                                        </div>
                                    </td>
                                @endif
                            @endif
                        @endforeach
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    @endforeach

    @if (isset($period))
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.updateButton'), ['class' => 'btn btn-warning btn-block']) !!}
    @else
    {!! Form::submit(trans(\Locales::getNamespace() . '/forms.storeButton'), ['class' => 'btn btn-primary btn-block']) !!}
    @endif

    {!! Form::close() !!}

    <script>
    @section('script')
        var dates = {!! json_encode($dates) !!};
        var minDate = dates[0].dfrom;
        var maxDate = dates[dates.length - 1].dto;

        $.datepicker.regional.{{ \Locales::getCurrent() }} = unikat.variables.datepicker.{{ \Locales::getCurrent() }};
        $.datepicker.setDefaults($.datepicker.regional.{{ \Locales::getCurrent() }});

        function addZ(n) {
            return n < 10 ? '0' + n : '' + n;
        }

        var highlightLast = false;

        $('#input-dfrom').datepicker({
            minDate: new Date(minDate.substring(0, 4), minDate.substring(4, 6) - 1, minDate.substring(6, 8)),
            maxDate: new Date(maxDate.substring(0, 4), maxDate.substring(4, 6) - 1, maxDate.substring(6, 8)),
            onSelect: function(date) {
                var d = new Date(Date.parse($("#input-dfrom").datepicker("getDate")));
                $('#input-dto').datepicker('option', 'minDate', d);
                $('#input-dto').removeAttr('disabled');
            },
            beforeShowDay: function(date) {
                var current = date.getFullYear() + '' + addZ(date.getMonth() + 1) + '' + addZ(date.getDate());

                if (highlightLast) {
                    highlightLast = false;
                    return [true, 'available-day']; // return [true, 'last-day'];
                }

                for (var i = 0; i < dates.length; i++) {
                    if (current == dates[i].dfrom) {
                        return [true, 'available-day']; // return [true, 'first-day'];
                    } else if (current > dates[i].dfrom && current < dates[i].dto) {
                        if (parseInt(current) + 1 == dates[i].dto) {
                            highlightLast = true;
                        }

                        return [true, 'available-day'];
                    }
                }
                return [false, 'booked-day'];
            },
        });

        $('#input-dto').datepicker({
            // minDate: new Date(minDate.substring(0, 4), minDate.substring(4, 6) - 1, minDate.substring(6, 8)),
            minDate: $("#input-dfrom").datepicker("getDate") ? new Date(Date.parse($("#input-dfrom").datepicker("getDate"))) : null,
            maxDate: new Date(maxDate.substring(0, 4), maxDate.substring(4, 6) - 1, maxDate.substring(6, 8)),
            beforeShowDay: function(date) {
                var current = date.getFullYear() + '' + addZ(date.getMonth() + 1) + '' + addZ(date.getDate());

                if (highlightLast) {
                    highlightLast = false;
                    return [true, 'available-day']; // return [true, 'last-day'];
                }

                for (var i = 0; i < dates.length; i++) {
                    if (current == dates[i].dfrom) {
                        return [true, 'available-day']; // return [true, 'first-day'];
                    } else if (current > dates[i].dfrom && current < dates[i].dto) {
                        if (parseInt(current) + 1 == dates[i].dto) {
                            highlightLast = true;
                        }

                        return [true, 'available-day'];
                    }
                }
                return [false, 'booked-day'];
            },
        });
    @show
    </script>
</div>
@endsection
