<!doctype html>
<html lang="{{ \Locales::getCurrent() }}">
<head dir="{{ \Locales::getScript() }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submit Booking Form</title>
</head>
<body>
    <form method="POST" action="https://3dsgate.borica.bg/cgi-bin/cgi_link" accept-charset="UTF-8" id="borica-form" class="none" role="form">
        <input id="BACKREF" name="BACKREF" type="hidden" value="{{ session('booking.BACKREF') }}">
        <input id="LANG" name="LANG" type="hidden" value="{{ session('booking.LANG') }}">
        <input id="AMOUNT" name="AMOUNT" type="hidden" value="{{ session('booking.AMOUNT') }}">
        <input id="CURRENCY" name="CURRENCY" type="hidden" value="{{ session('booking.CURRENCY') }}">
        <input id="DESC" name="DESC" type="hidden" value="{{ session('booking.DESC') }}">
        <input id="TERMINAL" name="TERMINAL" type="hidden" value="{{ session('booking.TERMINAL') }}">
        <input id="MERCH_NAME" name="MERCH_NAME" type="hidden" value="{{ session('booking.MERCH_NAME') }}">
        <input id="MERCH_URL" name="MERCH_URL" type="hidden" value="{{ session('booking.MERCH_URL') }}">
        <input id="MERCHANT" name="MERCHANT" type="hidden" value="{{ session('booking.MERCHANT') }}">
        <input id="EMAIL" name="EMAIL" type="hidden" value="{{ session('booking.EMAIL') }}">
        <input id="TRTYPE" name="TRTYPE" type="hidden" value="{{ session('booking.TRTYPE') }}">
        <input id="ORDER" name="ORDER" type="hidden" value="{{ session('booking.ORDER') }}">
        <input id="AD.CUST_BOR_ORDER_ID" name="AD.CUST_BOR_ORDER_ID" type="hidden" value="{{ session('booking.AD.CUST_BOR_ORDER_ID') }}">
        <input id="COUNTRY" name="COUNTRY" type="hidden" value="{{ session('booking.COUNTRY') }}">
        <input id="TIMESTAMP" name="TIMESTAMP" type="hidden" value="{{ session('booking.TIMESTAMP') }}">
        <input id="MERCH_GMT" name="MERCH_GMT" type="hidden" value="{{ session('booking.MERCH_GMT') }}">
        <input id="NONCE" name="NONCE" type="hidden" value="{{ session('booking.NONCE') }}">
        <input id="ADDENDUM" name="ADDENDUM" type="hidden" value="{{ session('booking.ADDENDUM') }}">
        <input id="P_SIGN" name="P_SIGN" type="hidden" value="{{ session('booking.P_SIGN') }}">
    </form>
    <script>
        document.getElementById('borica-form').submit();
    </script>
</body>
</html>
