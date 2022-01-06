@extends(\Locales::getNamespace() . '.master')

@section('content')
<div class="magnific-popup">
    {!! Form::open(['url' => \Locales::route('book', '3'), 'id' => 'book-step2-form', 'data-ajax-queue' => 'sync', 'class' => 'ajax-lock ' . (isset($formClass) ? $formClass : ''), 'role' => 'form']) !!}

    @include(\Locales::getNamespace() . '/partials.steps', ['step' => $step])

    @include(\Locales::getNamespace() . '/shared.errors')

    <div class="text">{!! $model->content !!}</div>

    <div class="booking-wrapper">
        <h3><span>2</span>{{ trans(\Locales::getNamespace() . '/messages.step2-active') }}</h3>

        <section class="booking-info">
            <p class="book-label"><span class="glyphicon glyphicon-ok-circle"></span>{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}:<strong class="selected">{{ session('dfrom')->format('d.m.Y') }}</strong></p>
            <p class="book-label"><span class="glyphicon glyphicon-ok-circle"></span>{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}:<strong class="selected">{{ session('dto')->format('d.m.Y') }}</strong></p>
            <p class="book-label"><span class="glyphicon glyphicon-ok-circle"></span>{{ trans(\Locales::getNamespace() . '/messages.nights') }}:<strong class="selected">{{ session('dfrom')->diffInDays(session('dto')) }}</strong></p>
        </section>

        @foreach($rooms as $room)
            <article class="room-popup" data-room="{{ $room['slug'] }}">
                <div class="room-image">
                    <div class="tooltip"><i class="glyphicon glyphicon-info-sign"></i><div class="tooltip-content tooltip-content-small">{!! $room['content'] !!}</div></div>
                    <section class="slider-pro" id="slider-room-{{ $room['slug'] }}" style="width: 100%; height: {{ \Config::get('upload.roomHeight') }}px">
                        <div class="sp-slides">
                            @foreach($room['images'] as $image)
                                <div class="sp-slide">{!! HTML::image(\App\Helpers\autover('/upload/rooms/' . \Locales::getCurrent() . '/' . $room['slug'] . '/' . \Config::get('upload.imagesDirectory') . '/' . $image->uuid . '/' . \Config::get('upload.roomDirectory') . '/' . $image->file)) !!}</div>
                            @endforeach
                        </div>
                    </section>
                </div>
                <div class="room-wrapper">
                    <section class="room-details">
                        <div class="room-header">
                            <h1>{{ $room['name'] }}</h1>
                            <span>{{ trans(\Locales::getNamespace() . '/messages.areaUpTo') . ' ' . $room['area'] . ' ' . trans(\Locales::getNamespace() . '/messages.areaMeters') }}</span>
                        </div>
                        <div class="text">{!! $room['content'] !!}</div>
                    </section>
                    <div class="text-center">
                        @if ($room['availability'] > 0)
                            @if ($room['minStay'] && session('nights') < $room['minStay'])
                                <p class="book-warning"><span class="glyphicon glyphicon-left glyphicon-warning-sign"></span>{{ trans(\Locales::getNamespace() . '/messages.minStay', ['min' => $room['minStay']]) }}</p>
                            @else
                                <p class="priceFrom text-center">{{ trans(\Locales::getNamespace() . '/messages.priceFrom') }}{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}<strong class="bg-success" style="padding: 0.25em 0.5em"></strong>{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }}</p>
                                <button type="button" class="btn btn-primary btn-block button-book-room"><span class="glyphicon glyphicon-left glyphicon-ok"></span>{{ trans(\Locales::getNamespace() . '/messages.book') . ' ' . $room['name'] }}</button>
                            @endif
                        @else
                            <p class="book-warning"><span class="glyphicon glyphicon-left glyphicon-warning-sign"></span>{{ trans(\Locales::getNamespace() . '/messages.no-availability') }}</p>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <section class="rooms-summary table-responsive">
        <table class="table">
            <caption>
                <p><span class="glyphicon glyphicon-ok-circle"></span>{{ trans(\Locales::getNamespace() . '/messages.selectedApartments') }}</p>
            </caption>
            <thead>
                <tr>
                    <th>{{ trans(\Locales::getNamespace() . '/forms.roomLabel') }}</th>
                    <th>{{ trans(\Locales::getNamespace() . '/forms.viewLabel') }}</th>
                    <th>{{ trans(\Locales::getNamespace() . '/forms.mealLabel') }}</th>
                    <th class="text-center">{{ trans(\Locales::getNamespace() . '/forms.guestsLabel') }}</th>
                    <th class="text-right">{{ trans(\Locales::getNamespace() . '/forms.priceLabel') }}</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="5" class="total-price text-right"></th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </section>

    <div class="bookButtons">
        <button data-url="{{ \Locales::route('book-step', '1') }}" class="btn btn-primary bookPrev" type="button"><i class="glyphicon glyphicon-chevron-left"></i>{{ trans(\Locales::getNamespace() . '/forms.prevButton') }}</button>
        <button class="btn btn-primary bookNext" type="submit">{{ trans(\Locales::getNamespace() . '/forms.nextButton') }}<i class="glyphicon glyphicon-chevron-right"></i></button>
    </div>

    {!! Form::close() !!}

    <script>
    @section('script')
        var _template = '\
<section class="room-details" data-room-index="[[ROOM_NUMBER]]">\
    <div class="room-header">\
        <h1 class="room-title">[[ROOM_NAME]] #[[ROOM_NUMBER]]</h1>\
        <i class="glyphicon glyphicon-ok"></i>\
    </div>\
    <div class="clearfix">\
        <div class="form-group input-book-view">\
            <label for="input-view-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">{{ trans(\Locales::getNamespace() . '/forms.viewLabel') }}</label>\
            <select id="input-view-[[ROOM_SLUG]]-[[ROOM_NUMBER]]" class="form-control" name="[[ROOM_SLUG]][[[ROOM_NUMBER]]][view]"><option value="" selected="selected">{{ trans(\Locales::getNamespace() . '/messages.select') }}</option></select>\
        </div>\
        <div class="form-group input-book-meal">\
            <label for="input-meal-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">{{ trans(\Locales::getNamespace() . '/forms.mealLabel') }}</label>\
            <select disabled id="input-meal-[[ROOM_SLUG]]-[[ROOM_NUMBER]]" class="form-control" name="[[ROOM_SLUG]][[[ROOM_NUMBER]]][meal]">{{-- <option value="" selected="selected">{{ trans(\Locales::getNamespace() . '/messages.select') }}</option>{!! $meals->map(function ($value, $key) { return '<option value="' . $value->slug . '">' . $value->name . '</option>'; })->implode('') !!} --}}</select>\
        </div>\
        <div class="form-group input-book-guests">\
            <label for="input-guests-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">{{ trans(\Locales::getNamespace() . '/forms.guestsLabel') }}</label>\
            <input id="input-guests-[[ROOM_SLUG]]-[[ROOM_NUMBER]]" name="[[ROOM_SLUG]][[[ROOM_NUMBER]]][guests]" type="hidden" value="0">\
            <div class="dropdown dropdown-guests">\
                <button type="button" class="btn btn-block btn-default dropdown-toggle button-guest" data-max="[[ROOM_CAPACITY]]" data-max-adults="[[ROOM_ADULTS]]" data-max-children="[[ROOM_CHILDREN]]" data-max-infants="[[ROOM_INFANTS]]" data-toggle="dropdown"><p>{{ trans(\Locales::getNamespace() . '/forms.guestsLabel') }}: <span class="guests-counter">0</span></p><span class="caret"></span></button>\
                <div class="dropdown-menu">\
                    <div class="guest-wrapper">\
                        <div>\
                            <label for="input-adults-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">{{ trans(\Locales::getNamespace() . '/forms.adultsLabel') }}</label>\
                            <span class="help-block">{{ trans(\Locales::getNamespace() . '/forms.adults-help') }}</span>\
                        </div>\
                        <div class="input-guest">\
                            <button type="button" class="btn btn-default button-guest-minus"><span class="glyphicon glyphicon-minus"></span></button>\
                            <input id="input-adults-[[ROOM_SLUG]]-[[ROOM_NUMBER]]" class="form-control adults" placeholder="{{ trans(\Locales::getNamespace() . '/forms.adultsPlaceholder') }}" readonly="readonly" name="[[ROOM_SLUG]][[[ROOM_NUMBER]]][adults]" type="text">\
                            <button type="button" class="btn btn-default button-guest-plus"><span class="glyphicon glyphicon-plus"></span></button>\
                        </div>\
                    </div>\
                    <div class="guest-wrapper">\
                        <div>\
                            <label for="input-children-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">{{ trans(\Locales::getNamespace() . '/forms.childrenLabel') }}</label>\
                            <span class="help-block">{{ trans(\Locales::getNamespace() . '/forms.children-help') }}</span>\
                        </div>\
                        <div class="input-guest">\
                            <button type="button" class="btn btn-default button-guest-minus"><span class="glyphicon glyphicon-minus"></span></button>\
                            <input id="input-children-[[ROOM_SLUG]]-[[ROOM_NUMBER]]" class="form-control children" placeholder="{{ trans(\Locales::getNamespace() . '/forms.childrenPlaceholder') }}" readonly="readonly" name="[[ROOM_SLUG]][[[ROOM_NUMBER]]][children]" type="text">\
                            <button type="button" class="btn btn-default button-guest-plus"><span class="glyphicon glyphicon-plus"></span></button>\
                        </div>\
                    </div>\
                    <div class="guest-wrapper">\
                        <div>\
                            <label for="input-infants-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">{{ trans(\Locales::getNamespace() . '/forms.infantsLabel') }}</label>\
                            <span class="help-block">{{ trans(\Locales::getNamespace() . '/forms.infants-help') }}</span>\
                        </div>\
                        <div class="input-guest">\
                            <button type="button" class="btn btn-default button-guest-minus"><span class="glyphicon glyphicon-minus"></span></button>\
                            <input id="input-infants-[[ROOM_SLUG]]-[[ROOM_NUMBER]]" class="form-control infants" placeholder="{{ trans(\Locales::getNamespace() . '/forms.infantsPlaceholder') }}" readonly="readonly" name="[[ROOM_SLUG]][[[ROOM_NUMBER]]][infants]" type="text">\
                            <button type="button" class="btn btn-default button-guest-plus"><span class="glyphicon glyphicon-plus"></span></button>\
                        </div>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>\
    <div class="room-prices">\
        <h6 class="pricePerDay">\
            <div>\
                <p>{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}<span id="price-per-day-bgn-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">0</span>{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }}</p>\
                <div class="tooltip tooltip-right"><i id="icon-price-periods-[[ROOM_SLUG]]-[[ROOM_NUMBER]]" class="glyphicon glyphicon-question-sign"></i><div id="price-periods-[[ROOM_SLUG]]-[[ROOM_NUMBER]]" class="tooltip-content tooltip-content-small"></div></div>\
                <p class="small">({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}<span id="price-per-day-eur-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">0</span>{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})</p>\
            </div>\
            {{ trans(\Locales::getNamespace() . '/messages.pricePerDay') }}\
        </h6>\
        <h6 class="priceTotal">\
            <p>{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}<span id="price-total-bgn-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">0</span>{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }}</p>\
            <p class="small">({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}<span id="price-total-eur-[[ROOM_SLUG]]-[[ROOM_NUMBER]]">0</span>{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})</p>\
            {{ trans(\Locales::getNamespace() . '/messages.priceTotal') }}\
        </h6>\
    </div>\
</section>';

        var _button = '\
<div class="text-center room-buttons">\
    <button type="button" class="btn btn-danger btn-link button-remove-room"><span class="glyphicon glyphicon-left glyphicon-remove"></span>{{ trans(\Locales::getNamespace() . '/forms.removeButton') }}</button>\
    <button type="button" class="btn btn-success btn-link button-add-room"><span class="glyphicon glyphicon-left glyphicon-plus"></span>{{ trans(\Locales::getNamespace() . '/forms.addButton') }}</button>\
    <button type="submit" class="btn btn-primary bookNext">{{ trans(\Locales::getNamespace() . '/forms.nextButton') }}<i class="glyphicon glyphicon-chevron-right"></i></button>\
</div>';

        var _row = '\
<tr class="room-row" data-room-row="[[ROOM_SLUG]]-[[ROOM_NUMBER]]">\
    <td class="room-room">[[ROOM_NAME]]</td>\
    <td class="room-view">[[ROOM_VIEW]]</td>\
    <td class="room-meal">[[ROOM_MEAL]]</td>\
    <td class="room-guests text-center">[[ROOM_GUESTS]]</td>\
    <td class="room-price text-right">[[ROOM_PRICE]]</td>\
</tr>';

        var today = {!! json_encode($today) !!};
        var pricePeriods = {!! json_encode($pricePeriods) !!};
        var rooms = {!! json_encode($rooms) !!};
        var meals = {!! json_encode($meals) !!};
        var dfrom = {{ session('dfrom')->format('Ymd') }};
        var dto = {{ session('dto')->format('Ymd') }};
        var nights = {{ session('nights') }};

        Modernizr.load([{
            load: ['{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jquery.qtip.js') }}', '{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jquery-ui.min.js') }}', '{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jquery.multiselect.js') }}'],
            complete: function() {
                @if (session('rooms'))
                    @foreach (session('rooms') as $slug => $data)
                        var roomWrapper = $('[data-room="{{ $slug }}"]');
                        roomWrapper.find('.button-book-room').trigger('click');

                        @foreach ($data as $index => $room)
                            @if ($index > 1)
                                roomWrapper.find('.button-add-room').trigger('click');
                            @endif

                            @if ($room['view'])
                                // $('#input-view-{{ $slug }}-{{ $index }} option[value="{{ $room['view'] }}"]').attr('selected', true);
                                var viewsSelect = $('#input-view-{{ $slug }}-{{ $index }}');
                                viewsSelect.val('{{ $room['view'] }}');
                                viewsSelect.multiselect('refresh');

                                updateViews(viewsSelect, '{{ $slug }}', '{{ $room['view'] }}', '{{ $room['meal'] }}');
                            @endif

                            @if ($room['adults'])
                                $('#input-adults-{{ $slug }}-{{ $index }}').next().trigger('click');
                            @endif

                            @if ($room['children'])
                                $('#input-children-{{ $slug }}-{{ $index }}').next().trigger('click');
                            @endif

                            @if ($room['infants'])
                                $('#input-infants-{{ $slug }}-{{ $index }}').next().trigger('click');
                            @endif

                            $('.mfp-wrap').scrollTop(0);
                        @endforeach
                    @endforeach
                @endif
            },
        }]);

        var sliders = {};
        @foreach($rooms as $room)
            sliders['slider-room-{{ $room['slug'] }}'] = {
                buttons: false,
                loop: {{ count($room['images']) > 2 ? 'true' : 'false' }},
                height: {{ \Config::get('upload.roomHeight') }},
            };

            var prices = null;
            if (typeof pricePeriods[0]['prices']['{{ $room['slug'] }}'] !== 'undefined' && typeof pricePeriods[0]['prices']['{{ $room['slug'] }}']['park'] !== 'undefined') {
                prices = pricePeriods[0]['prices']['{{ $room['slug'] }}']['park'];
            } else if (typeof pricePeriods[0]['prices']['{{ $room['slug'] }}'] !== 'undefined' && typeof pricePeriods[0]['prices']['{{ $room['slug'] }}']['sea'] !== 'undefined') {
                prices = pricePeriods[0]['prices']['{{ $room['slug'] }}']['sea'];
            }

            var minPrice = 999999999999999;
            $.each(meals, function(meal) {
                if (prices && prices[meal] > 0 && prices[meal] < minPrice) {
                    minPrice = prices[meal];
                }
            });

            var priceFrom = $('[data-room="{{ $room['slug'] }}"] .priceFrom');
            if (priceFrom) {
                if (minPrice == 999999999999999) {
                    priceFrom.remove();
                } else {
                    priceFrom.find('strong').text(minPrice);
                }
            }
        @endforeach
        unikat.slidersSetup(sliders);

        function resetRoom(room, index) {
            rooms[room]['rooms'][index].completed = false;
            rooms[room]['rooms'][index].view = null;
            rooms[room]['rooms'][index].meal = null;
            rooms[room]['rooms'][index].guests = 0;
            rooms[room]['rooms'][index].adults = 0;
            rooms[room]['rooms'][index].children = 0;
            rooms[room]['rooms'][index].infants = 0;
            rooms[room]['rooms'][index].price = 0;
        }

        function updateSummary() {
            var table = $('.rooms-summary');
            var body = table.find('tbody');
            var active = false;
            var total = 0;

            for (var room in rooms) {
                for (var index in rooms[room]['rooms']) {
                    var r = rooms[room]['rooms'][index];
                    var row = $('[data-room-row="' + room + '-' + index + '"]');
                    if (r.completed) {
                        if (!row.length) {
                            row = _row.replace(/\[\[ROOM_SLUG\]\]/g, room);
                            row = row.replace(/\[\[ROOM_NUMBER\]\]/g, index);
                            row = row.replace(/\[\[ROOM_NAME\]\]/g, rooms[room]['name'] + ' #' + index);
                            row = row.replace(/\[\[ROOM_VIEW\]\]/g, r.view);
                            row = row.replace(/\[\[ROOM_MEAL\]\]/g, r.meal);
                            row = row.replace(/\[\[ROOM_GUESTS\]\]/g, r.guests);
                            row = row.replace(/\[\[ROOM_PRICE\]\]/g, '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}' + parseFloat(r.price).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + parseFloat(r.price / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})');

                            body.append(row);
                        } else {
                            row.find('.room-room')[0].textContent = rooms[room]['name'] + ' #' + index;
                            row.find('.room-view')[0].textContent = r.view;
                            row.find('.room-meal')[0].textContent = r.meal;
                            row.find('.room-guests')[0].textContent = r.guests;
                            row.find('.room-price').html('{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}' + parseFloat(r.price).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + parseFloat(r.price / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})');
                        }

                        total += r.price;
                        active = true;
                    } else {
                        row.remove();
                    }
                }
            }

            if (active) {
                table.find('.total-price').html('{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}' + parseFloat(total).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + parseFloat(total / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})');
                table.addClass('rooms-summary-active');
            } else {
                table.removeClass('rooms-summary-active');
            }
        }

        function updateHtml(that) {
            var roomWrapper = $(that).closest('.room-popup');
            var room = roomWrapper.data('room');
            var section = $(that).closest('.room-details');
            var roomIndex = section.data('room-index');
            var totalNights = 0;
            var pricePerDay = 0;
            var priceTotal = 0;
            var periods = '';
            var iconActive = false;

            var view = $('#input-view-' + rooms[room]['slug'] + '-' + roomIndex).val();
            var meal = $('#input-meal-' + rooms[room]['slug'] + '-' + roomIndex).val();
            var guests = $('#input-guests-' + rooms[room]['slug'] + '-' + roomIndex).val();
            var adults = parseInt($('#input-adults-' + rooms[room]['slug'] + '-' + roomIndex).val());
            var children = parseInt($('#input-children-' + rooms[room]['slug'] + '-' + roomIndex).val());
            var infants = parseInt($('#input-infants-' + rooms[room]['slug'] + '-' + roomIndex).val());

            if (view && meal && guests > 0) {
                var periodFrom = pricePeriods[Object.keys(pricePeriods)[0]].from;
                var periodTo = pricePeriods[Object.keys(pricePeriods)[Object.keys(pricePeriods).length - 1]].to;
                var periodPrice = pricePeriods[Object.keys(pricePeriods)[0]].prices[room][view][meal];
                var samePeriod = true;

                $.each(pricePeriods, function(periodIndex, period) {
                    if (period.prices[room][view][meal] != periodPrice) {
                        samePeriod = false;
                        return false;
                    }

                    totalNights += period.nights;
                });

                if (samePeriod) {
                    iconActive = true;
                    periods += periodFrom + ' - ' + periodTo + ' (' + totalNights + ' {{ trans(\Locales::getNamespace() . '/js.nights') }}) x {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}' + parseFloat(periodPrice).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(periodPrice) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                    priceTotal += parseFloat(periodPrice) * parseInt(totalNights);
                } else {
                    totalNights = 0;
                    $.each(pricePeriods, function(periodIndex, period) {
                        if (Object.keys(pricePeriods).length > 1) {
                            iconActive = true;
                            periods += period.from + ' - ' + period.to + ' (' + period.nights + ' {{ trans(\Locales::getNamespace() . '/js.nights') }}) x {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}' + parseFloat(period.prices[room][view][meal]).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(period.prices[room][view][meal]) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                        }

                        totalNights += parseInt(period.nights);
                        priceTotal += parseFloat(period.prices[room][view][meal]) * parseInt(period.nights);
                    });
                }

                var discount = 0;
                var discountTotal = 0;
                if (room != 'one-bed-economy' && room != 'two-bed-economy') {
                    var from = periodFrom.split('.');
                    var to = periodTo.split('.');

                    if (today <= '20220131') {
                        discount = (priceTotal * 0.25); // 25% early booking
                        priceTotal -= discount;
                        discountTotal += discount;
                        iconActive = true;
                        periods += '{{ trans(\Locales::getNamespace() . '/js.discount25') }}: ' + parseFloat(discount).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(discount) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                    } else if (today <= '20220331') {
                        discount = (priceTotal * 0.20); // 20% early booking
                        priceTotal -= discount;
                        discountTotal += discount;
                        iconActive = true;
                        periods += '{{ trans(\Locales::getNamespace() . '/js.discount20') }}: ' + parseFloat(discount).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(discount) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                    }

                    if (totalNights == 5 || totalNights == 6) {
                        if (from[2] == '2021') { // promotional (COVID) year
                            discount = (priceTotal * 0.05);
                            priceTotal -= discount;
                            discountTotal += discount;
                            iconActive = true;
                            periods += '{{ trans(\Locales::getNamespace() . '/js.discount5') }}: ' + parseFloat(discount).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(discount) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                        } else if (to[1] + to[0] <= '0630' || from[1] + from[0] >= '0901') {
                            discount = (priceTotal * 0.05);
                            priceTotal -= discount;
                            discountTotal += discount;
                            iconActive = true;
                            periods += '{{ trans(\Locales::getNamespace() . '/js.discount5') }}: ' + parseFloat(discount).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(discount) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                        }
                    } else if (totalNights >= 7) {
                        if (from[2] == '2021') { // promotional (COVID) year
                            discount = (priceTotal * 0.1);
                            priceTotal -= discount;
                            discountTotal += discount;
                            iconActive = true;
                            periods += '{{ trans(\Locales::getNamespace() . '/js.discount10') }}: ' + parseFloat(discount).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(discount) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                        } else if (to[1] + to[0] <= '0630' || from[1] + from[0] >= '0901') {
                            discount = (priceTotal * 0.1);
                            priceTotal -= discount;
                            discountTotal += discount;
                            iconActive = true;
                            periods += '{{ trans(\Locales::getNamespace() . '/js.discount10') }}: ' + parseFloat(discount).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(discount) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                        }
                    }
                }

                // priceTotal = priceTotal - discountTotal;
                pricePerDay = priceTotal / totalNights;

                if (adults && parseFloat(meals[meal].price_adult)) {
                    iconActive = true;
                    pricePerDay += (adults * parseFloat(meals[meal].price_adult));
                    priceTotal += (totalNights * adults * parseFloat(meals[meal].price_adult));
                    periods += '{{ trans(\Locales::getNamespace() . '/js.priceAdult') }}: ' + adults + ' x {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}' + parseFloat(meals[meal].price_adult).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(meals[meal].price_adult) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                }

                if (children && parseFloat(meals[meal].price_child)) {
                    iconActive = true;
                    pricePerDay += (children * parseFloat(meals[meal].price_child));
                    priceTotal += (totalNights * children * parseFloat(meals[meal].price_child));
                    periods += '{{ trans(\Locales::getNamespace() . '/js.priceChild') }}: ' + children + ' x {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') }}' + parseFloat(meals[meal].price_child).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-after') }} ({{ trans(\Locales::getNamespace() . '/messages.eur-sign-before') }}' + (parseFloat(meals[meal].price_child) / 1.95).toFixed(2) + '{{ trans(\Locales::getNamespace() . '/messages.eur-sign-after') }})<br>';
                }

                rooms[room]['rooms'][roomIndex].completed = true;
                rooms[room]['rooms'][roomIndex].view = rooms[room]['views'][view]['name'];
                rooms[room]['rooms'][roomIndex].meal = meals[meal].name; // rooms[room]['meals'][meal]['name'];
                rooms[room]['rooms'][roomIndex].guests = guests;
                rooms[room]['rooms'][roomIndex].adults = adults;
                rooms[room]['rooms'][roomIndex].children = children;
                rooms[room]['rooms'][roomIndex].infants = infants;
                rooms[room]['rooms'][roomIndex].price = priceTotal;

                section.find('.room-header .glyphicon-ok').addClass('glyphicon-active');
                if (iconActive) {
                    $('#icon-price-periods-' + rooms[room]['slug'] + '-' + roomIndex).addClass('glyphicon-active');
                } else {
                    $('#icon-price-periods-' + rooms[room]['slug'] + '-' + roomIndex).removeClass('glyphicon-active');
                }
            } else {
                periods = '';
                pricePerDay = 0;
                priceTotal = 0;

                resetRoom(room, roomIndex);

                section.find('.room-header .glyphicon-ok').removeClass('glyphicon-active');
                $('#icon-price-periods-' + rooms[room]['slug'] + '-' + roomIndex).removeClass('glyphicon-active');
            }

            $('#price-per-day-bgn-' + rooms[room]['slug'] + '-' + roomIndex).text(pricePerDay ? pricePerDay.toFixed(2) : pricePerDay);
            $('#price-per-day-eur-' + rooms[room]['slug'] + '-' + roomIndex).text(pricePerDay ? (pricePerDay / 1.95).toFixed(2) : pricePerDay);
            $('#price-total-bgn-' + rooms[room]['slug'] + '-' + roomIndex).text(priceTotal ? (priceTotal).toFixed(2) : priceTotal);
            $('#price-total-eur-' + rooms[room]['slug'] + '-' + roomIndex).text(priceTotal ? (priceTotal / 1.95).toFixed(2) : priceTotal);
            $('#price-periods-' + rooms[room]['slug'] + '-' + roomIndex).html(periods);

            updateSummary();
        }

        function getTemplate(room, template) {
            var t = _template + (template || '');
            t = t.replace(/\[\[ROOM_NAME\]\]/g, rooms[room]['name']);
            t = t.replace(/\[\[ROOM_CONTENT\]\]/g, rooms[room]['content']);
            t = t.replace(/\[\[ROOM_SLUG\]\]/g, rooms[room]['slug']);
            t = t.replace(/\[\[ROOM_NUMBER\]\]/g, rooms[room]['counter']);
            t = t.replace(/\[\[ROOM_CAPACITY\]\]/g, rooms[room]['capacity']);
            t = t.replace(/\[\[ROOM_ADULTS\]\]/g, rooms[room]['adults']);
            t = t.replace(/\[\[ROOM_CHILDREN\]\]/g, rooms[room]['children']);
            t = t.replace(/\[\[ROOM_INFANTS\]\]/g, rooms[room]['infants']);

            return t;
        }

        function initMultiselect(room, humanClick) {
            if (typeof humanClick === 'undefined') {
                humanClick = true;
            }

            var multiselect = {};

            initViews(room);

            multiselect['input-view-' + rooms[room]['slug'] + '-' + rooms[room]['counter']] = {
                multiple: false,
                header: false,
                close: function() {
                    updateViews(this, room);
                },
            };

            multiselect['input-meal-' + rooms[room]['slug'] + '-' + rooms[room]['counter']] = {
                multiple: false,
                header: false,
                close: function() {
                    var meal = $($(this).multiselect('getChecked')).val();
                    rooms[room]['meal'] = meal;

                    updateHtml(this);
                },
            };

            unikat.multiselectSetup(multiselect);

            if (humanClick) {
                updateViews($('#input-view-' + rooms[room]['slug'] + '-' + rooms[room]['counter']), room); // if single view option (e.g. for studio - 'park') just select it
            }
        }

        function updateViews(that, room, view, meal) {
            view = view || $(that).multiselect('getChecked').val();
            rooms[room]['view'] = view;

            var mealsSelect = $('#input-meal-' + rooms[room]['slug'] + '-' + rooms[room]['counter']);
            var selectedMeal = meal || mealsSelect.val();
            mealsSelect.empty();

            if (view) {
                initMeals(room, mealsSelect, view);
                var disabled = mealsSelect.find('option[value="' + selectedMeal + '"]').prop('disabled');
                if (typeof disabled === 'undefined') {
                    mealsSelect.children().each(function() {
                        if (!this.disabled) {
                            mealsSelect.prop('selectedIndex', this.index);
                            return false;
                        }
                    });
                } else if (!disabled) {
                    mealsSelect.val(selectedMeal);
                }

                mealsSelect.multiselect('enable');
            } else {
                mealsSelect.multiselect('disable');
            }

            mealsSelect.multiselect('refresh');

            updateHtml(that);
        }

        function initViews(room) {
            var views = $('#input-view-' + rooms[room]['slug'] + '-' + rooms[room]['counter']);
            for (var slug in rooms[room]['views']) {
                var view = rooms[room]['views'][slug];
                if ((rooms[room]['slug'] == 'studio' || rooms[room]['slug'] == 'one-bed-economy' || rooms[room]['slug'] == 'two-bed-economy') && slug == 'sea') {
                    // skip sea view
                } else {
                    var minStay = view.min_stay > 0 && nights < view.min_stay;
                    views.append('<option class="' + (minStay ? 'minStayLabel' : '') + '" value="' + view.slug + '"' + ((view.availability && (view.min_stay == 0 || (view.min_stay > 0 && nights >= view.min_stay))) ? '' : ' disabled') + '>' + view.name + (minStay ? ' ({{ trans(\Locales::getNamespace() . '/messages.minStay') }})'.replace(':min', view.min_stay) : '') + '</option>');
                }
            };

            if (views.find('option').length == 2) {
                views[0].remove(0);
            }
        }

        function initMeals(room, mealsSelect, view) {
            // mealsSelect.append('<option value="" selected="selected">{{ trans(\Locales::getNamespace() . '/messages.select') }}</option>');

            for (var slug in meals) {
                if (slug == 'sc' && room != 'one-bed-economy' && room != 'two-bed-economy') {
                    continue;
                }

                /*if (slug == 'ai' && nights < 3 && dto > '20210701' && dfrom < '20210831') {
                    continue;
                }*/

                var minStay = false;
                if (slug == 'ai' && nights < 3 && (/*(dto >= '20210610' && dfrom <= '20210630') || */(dto >= '20210901' && dfrom <= '20210910'))) {
                    minStay = 3;
                }

                if (slug == 'ai' && nights < 3 && (dfrom.toString().substring(0, 4) == '2021' || dfrom.toString().substring(0, 4) == '2022')) {
                    minStay = 3;
                }

                var meal = meals[slug];
                if (typeof pricePeriods[0]['prices'][room] !== 'undefined' && pricePeriods[0]['prices'][room][view][slug]) {
                    mealsSelect.append('<option class="' + (minStay ? 'minStayLabel' : '') + '" value="' + meal.slug + '"' + (minStay ? ' disabled' : '') + '>' + meal.name + (minStay ? ' ({{ trans(\Locales::getNamespace() . '/messages.minStay') }})'.replace(':min', minStay) : '') + '</option>');
                } else {
                    // mealsSelect.append('<option value="' + meal.slug + '" disabled>' + meal.name + '</option>');
                }
            };
        }

        $('.booking-wrapper').on('click', '.button-book-room', function (e) {
            var humanClick = e.hasOwnProperty('originalEvent');
            var roomWrapper = $(this).closest('.room-popup');
            var room = roomWrapper.data('room');
            var wrapper = $(this).closest('.room-wrapper');

            rooms[room]['counter']++;
            rooms[room]['html'] = wrapper.html();
            roomWrapper.find('.room-image .tooltip').show();

            var t = getTemplate(room, _button);

            wrapper.html(t);

            if (rooms[room].availability == 1) {
                wrapper.find('.button-add-room').hide();
            }

            initMultiselect(room, humanClick);
            unikat.scrollIntoView(wrapper[0]);
        });

        $('.booking-wrapper').on('click', '.button-add-room', function () {
            var roomWrapper = $(this).closest('.room-popup');
            var room = roomWrapper.data('room');
            var wrapper = $(this).closest('.room-wrapper');

            if (rooms[room].counter < rooms[room].availability) {
                rooms[room]['counter']++;

                if (rooms[room].counter == rooms[room].availability) {
                    // $(this)[0].disabled = true;
                    $(this).hide();
                }

                var t = getTemplate(room);

                $(this).parent().before(t);

                initMultiselect(room);
            }
        });

        $('.booking-wrapper').on('click', '.button-remove-room', function () {
            var roomWrapper = $(this).closest('.room-popup');
            var room = roomWrapper.data('room');
            var wrapper = $(this).closest('.room-wrapper');
            var section = wrapper.find('.room-details').last();
            var roomIndex = section.data('room-index');

            rooms[room]['counter']--;
            // $('.button-add-room')[0].disabled = false;
            wrapper.find('.button-add-room').show();

            resetRoom(room, roomIndex);
            updateSummary();
            section.remove();

            if (rooms[room]['counter'] == 0) {
                wrapper.html(rooms[room]['html']);
                roomWrapper.find('.room-image .tooltip').hide();
            }
        });

        $('.booking-wrapper').on('click', '.button-guest-plus', function () {
            var input = $(this).prev();
            var room = $(this).closest('.room-popup').data('room');
            var roomIndex = $(this).closest('.room-details').data('room-index');
            var guests = $('#input-guests-' + rooms[room]['slug'] + '-' + roomIndex)[0];
            var dropdown = $(this).closest('.dropdown-guests');
            var adults = dropdown.find('.adults')[0].value || 0;
            var children = dropdown.find('.children')[0].value || 0;
            var infants = dropdown.find('.infants')[0].value || 0;
            var guestsCounter = dropdown.find('.guests-counter')[0];
            var button = dropdown.find('.button-guest');
            var maxAdults = button.data('max-adults') || 0;
            var maxChildren = button.data('max-children') || 0;
            var maxInfants = button.data('max-infants') || 0;
            var max = button.data('max') || 0;

            if (input.hasClass('adults')) {
                if (input[0].value == maxAdults || (guestsCounter.textContent - infants >= max)) {
                    return;
                }
            }

            if (input.hasClass('children')) {
                if (input[0].value == maxChildren || (guestsCounter.textContent - infants >= max)) {
                    return;
                }
            }

            if (input.hasClass('infants')) {
                if (input[0].value == maxInfants) {
                    return;
                }
            }

            if (guestsCounter.textContent - maxInfants < max) {
                input[0].value++;
                guestsCounter.textContent++;
                guests.value++;
                updateHtml(this);
            }
        });

        $('.booking-wrapper').on('click', '.button-guest-minus', function () {
            var input = $(this).next()[0];
            var guestsCounter = $(this).closest('.dropdown-guests').find('.guests-counter')[0];
            var room = $(this).closest('.room-popup').data('room');
            var roomIndex = $(this).closest('.room-details').data('room-index');
            var guests = $('#input-guests-' + rooms[room]['slug'] + '-' + roomIndex)[0];

            if (input.value > 0) {
                input.value--;

                if (guestsCounter.textContent > 0) {
                    guestsCounter.textContent--;
                    guests.value--;
                    updateHtml(this);
                }
            }
        });

        window.setTimeout(function() {
            $('.mfp-wrap').scrollTop(0);
        }, 200);
    <?php if (isset($formClass)) { ?>
        @endsection
    <?php } else { ?>
        @show
    <?php } ?>
    </script>
</div>
@endsection
