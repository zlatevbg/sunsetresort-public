@extends(\Locales::getNamespace() . '.master')

@section('content')
    @include(\Locales::getNamespace() . '/shared.errors')

    @if (isset($datatables) && count($datatables) > 0)
        @include(\Locales::getNamespace() . '/partials.datatables')
    @endif
@endsection

@section('script')
    @if (isset($datatables) && count($datatables) > 0)
        unikat.callback = function() {
            this.datatables({!! json_encode($datatables) !!});
        };
    @endif

    {{-- @if (Session::get('ajax-url'))
        var transaction = '{{ Session::get('transaction-id') }}';

        window.setTimeout(function() {
            var tableId = $(document).find('table').attr('id');
            $('#' + tableId + ' #\\3' + transaction.charAt(0) + ' ' + transaction.substring(1) + ' input[type="checkbox"]').trigger('click');
            $('.btn-danger.js-edit').trigger('click');
        }, 1000);
    @endif --}}
@endsection
