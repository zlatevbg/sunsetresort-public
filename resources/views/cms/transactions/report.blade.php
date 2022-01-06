@extends(\Locales::getNamespace() . '.master')

@section('content')
    @include(\Locales::getNamespace() . '/shared.errors')

    <div class="report-wrapper">
        <h1 class="h2 text-center">{{ trans(\Locales::getNamespace() . '/datatables.titleReportBookings') }}</h1>

        {!! Form::open(['url' => \Locales::route('export-transactions/generate'), 'id' => 'report-form', 'data-ajax-queue' => 'sync', 'data-alert-position' => 'insertAfter', 'class' => 'ajax-lock form-inline', 'role' => 'form']) !!}

        <div class="row-wrapper">
            <div class="form-group">
                {!! Form::label('input-status', trans(\Locales::getNamespace() . '/forms.statusLabel')) !!}
                {!! Form::select('status', $status, null, ['id' => 'input-status', 'class' => 'form-control', 'style' => 'width:100%']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('input-transaction', trans(\Locales::getNamespace() . '/forms.transactionLabel')) !!}
                {!! Form::select('transaction', $transaction, null, ['id' => 'input-transaction', 'class' => 'form-control', 'style' => 'width:100%']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('input-locale', trans(\Locales::getNamespace() . '/forms.localeLabel')) !!}
                {!! Form::select('locale', $locale, null, ['id' => 'input-locale', 'class' => 'form-control', 'style' => 'width:100%']) !!}
            </div>
        </div>
        <div class="row-wrapper">
            <div class="form-group">
                {!! Form::label('input-dfrom', trans(\Locales::getNamespace() . '/forms.dfromLabel')) !!}
                {!! Form::text('dfrom', null, ['id' => 'input-dfrom', 'class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('input-dto', trans(\Locales::getNamespace() . '/forms.dtoLabel')) !!}
                {!! Form::text('dto', null, ['id' => 'input-dto', 'class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('input-order', trans(\Locales::getNamespace() . '/forms.orderByLabel')) !!}
                {!! Form::select('order', $order, 'created_at', ['id' => 'input-order', 'class' => 'form-control', 'style' => 'width:100%']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('input-sort', trans(\Locales::getNamespace() . '/forms.sortLabel')) !!}
                {!! Form::select('sort', $sort, 'desc', ['id' => 'input-sort', 'class' => 'form-control', 'style' => 'width:100%']) !!}
            </div>
        </div>
        <div class="row-wrapper">
            <div class="btn-group">{!! Form::button('<span class="glyphicon glyphicon-cog glyphicon-left"></span>' . trans(\Locales::getNamespace() . '/forms.showReportButton'), ['type' => 'submit', 'class' => 'btn btn-primary']) !!}</div>
            <div class="btn-group">{!! Form::button('<span class="glyphicon glyphicon-save glyphicon-left"></span>' . trans(\Locales::getNamespace() . '/forms.downloadReportButton'), ['id' => 'button-download-report', 'class' => 'btn btn-warning']) !!}</div>
        </div>
        {!! Form::close() !!}
    </div>

    @if (isset($datatables) && count($datatables) > 0)
        @include(\Locales::getNamespace() . '/partials.datatables')
    @endif
@endsection

@section('jsFiles')
    jsFiles.push('{{ \App\Helpers\autover('/js/' . \Locales::getNamespace() . '/vendor/jquery-ui.min.js') }}');

    @parent
@endsection

@section('script')
    @if (isset($datatables) && count($datatables) > 0)
        unikat.callback = function() {
            this.datatables({!! json_encode($datatables) !!});
        };
    @endif

    $.datepicker.regional.{{ \Locales::getCurrent() }} = unikat.variables.datepicker;
    $.datepicker.setDefaults($.datepicker.regional.{{ \Locales::getCurrent() }});

    $('#input-dfrom').datepicker({
        changeYear: true,
        changeMonth: true,
        onSelect: function(date) {
            var d = new Date(Date.parse($("#input-dfrom").datepicker("getDate")));
            $('#input-dto').datepicker('option', 'minDate', d);
        },
    });
    $('#input-dto').datepicker({
        changeYear: true,
        changeMonth: true,
    });

    $('#button-download-report').click(function(e) {
        e.preventDefault();

        var that = $(this).closest('.ajax-lock');

        unikat.ajaxify({
            that: that,
            method: 'post',
            skipErrors: true,
            queue: that.data('ajaxQueue'),
            action: that.attr('action'),
            data: that.serialize() + '&generate=excel',
            functionParams: ['uuid'],
            function: function(params) {
                window.location.href = '{{ \Locales::route('export-transactions/download') }}?uuid=' + params.uuid;
            },
        });
    });
@endsection
