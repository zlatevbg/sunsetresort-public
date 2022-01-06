@inject('carbon', '\Carbon\Carbon')

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <title></title>
        <!--[if !mso]><!-- -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <style type="text/css">
            #outlook a {
              padding: 0
            }

            .ReadMsgBody {
              width: 100%
            }

            .ExternalClass {
              width: 100%
            }

            .ExternalClass * {
              line-height: 100%
            }

            body {
              margin: 0;
              padding: 0;
              -webkit-text-size-adjust: 100%;
              -ms-text-size-adjust: 100%
            }

            table,
            td {
              border-collapse: collapse;
              mso-table-lspace: 0;
              mso-table-rspace: 0
            }

            img {
              border: 0;
              height: auto;
              line-height: 100%;
              outline: 0;
              text-decoration: none;
              -ms-interpolation-mode: bicubic
            }

            p {
              display: block;
              margin: 13px 0
            }
        </style>
        <!--[if !mso]><!-->
        <style type="text/css">
            @media only screen and (max-width:480px) {
              @-ms-viewport {
                width: 320px
              }
              @viewport {
                width: 320px
              }
            }
        </style>
        <!--<![endif]-->
        <!--[if mso]>
        <xml>
          <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
          </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
        <!--[if lte mso 11]>
        <style type="text/css">
            .outlook-group-fix {
              width:100% !important;
            }
        </style>
        <![endif]-->
        <!--[if !mso]><!-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet" type="text/css">
        <style type="text/css">
            @import url(https://fonts.googleapis.com/css?family=Roboto:400,700);
        </style>
        <!--<![endif]-->
        <style type="text/css">
            @media only screen and (min-width:480px) {
              .mj-column-per-66 {
                width: 66.66666666666666%!important
              }

              .mj-column-per-33 {
                width: 33.33333333333333%!important
              }

              .mj-column-per-100 {
                width: 100%!important
              }

              .mj-column-per-25 {
                width: 25%!important
              }

              .mj-column-per-50 {
                width: 50%!important
              }
            }

            @media only screen and (max-width:480px) {
              .mobile-center {
                text-align: center!important
              }
            }
        </style>
    </head>

    <body style="background:#f2f2f2">
        <div class="mj-container" style="background-color:#f2f2f2">
            <!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <![endif]-->
                        <div style="margin:0 auto;max-width:600px">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0;width:100%" align="center" border="0">
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:20px 0;padding-bottom:20px;padding-top:20px">
                                            <!--[if mso | IE]>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="vertical-align:middle;width:396px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-66 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:11px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:0;padding-bottom:0;padding-right:25px;padding-left:25px" align="left">
                                                                            <div class="mobile-center" style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px"><span style="font-size:11px">{{ trans(\Locales::getNamespace() . '/newsletters.subject') }}</span></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                    <td style="vertical-align:middle;width:198px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-33 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:11px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:0;padding-bottom:0;padding-right:25px;padding-left:25px" align="right">
                                                                            <div class="mobile-center" style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px"><span style="font-size:11px">{{ $carbon->now()->format('d.m.Y H:i:s') }}</span></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            <!--[if mso | IE]>
                    </td>
                </tr>
            </table>
            <![endif]-->

            <!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <![endif]-->
                        <div style="margin:0 auto;max-width:600px;background:#ddd">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0;width:100%;background:#ddd" align="center" border="0">
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:0 0">
                                            <!--[if mso | IE]>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="vertical-align:top;width:600px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:top" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:0 0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0" align="center">
                                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0" align="center" border="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td style="width:600px"><a href="{{ env('APP_URL') }}" target="_blank"><img alt="" height="auto" src="cid:header-emails.jpg" style="border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto" width="600"></a></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            <!--[if mso | IE]>
                    </td>
                </tr>
            </table>
            <![endif]-->

            <!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <![endif]-->
                        <div style="margin:0 auto;max-width:600px;background:#fff">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0;width:100%;background:#fff" align="center" border="0">
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:0 0;padding-bottom:0;padding-top:20px">
                                            <!--[if mso | IE]>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="vertical-align:top;width:600px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:top" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:0;padding-bottom:0;padding-right:25px;padding-left:25px" align="center">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:16px;line-height:22px;text-align:center">
                                                                                <p><span style="color:#a20b35"><span style="font-weight:700"><span style="font-size:16px">{{ trans(\Locales::getNamespace() . '/newsletters.title') . ' #SRB' . date('Y') . '-' . $booking->id }}</span></span></span></p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px" align="justify">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left">
                                                                                {!! trans(\Locales::getNamespace() . '/newsletters.confirmation') !!}
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:20px;padding-bottom:0;padding-right:25px;padding-left:25px" align="justify">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left">
                                                                                {{-- <table cellpadding="0" cellspacing="0" style="width:100%;vertical-align:top;border-collapse:collapse;border-spacing:0" align="center" border="0">
                                                                                    <thead>
                                                                                        <tr style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0">
                                                                                            <th style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 15px 5px 0;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:0" align="left">{{ trans(\Locales::getNamespace() . '/forms.nameLabel') }}</th>
                                                                                            <th style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left">{{ trans(\Locales::getNamespace() . '/forms.emailLabel') }}</th>
                                                                                            <th style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left">{{ trans(\Locales::getNamespace() . '/forms.phoneLabel') }}</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr style="text-align:left;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0">
                                                                                            <td style="text-align:left;padding:5px 15px 5px 0;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:0" align="left">{{ $booking->name }}</td>
                                                                                            <td style="text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left">{{ $booking->email }}</td>
                                                                                            <td style="text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left">{{ $booking->phone }}</td>
                                                                                        </tr>
                                                                                        @if ($booking->message)
                                                                                            <tr style="text-align:left;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0">
                                                                                                <td colspan="3" style="text-align:left;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0" align="left">{!! nl2br($booking->message) !!}</td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    </tbody>
                                                                                </table> --}}
                                                                                <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.nameLabel') }}: <strong>{{ $booking->name }}</strong></p>
                                                                                <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.emailLabel') }}: <strong>{{ $booking->email }}</strong></p>
                                                                                <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.phoneLabel') }}: <strong>{{ $booking->phone }}</strong></p>
                                                                                @if ($booking->message)<p style="text-align:left">{{ $booking->message }}</p>@endif
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @if ($booking->company)
                                                                        <tr>
                                                                            <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:20px;padding-bottom:0;padding-right:25px;padding-left:25px" align="justify">
                                                                                <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left">
                                                                                    <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.companyLabel') }}: <strong>{{ $booking->company }}</strong></p>
                                                                                    <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.eikLabel') }}: <strong>{{ $booking->eik }}</strong></p>
                                                                                    <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.vatLabel') }}: <strong>{{ $booking->vat }}</strong></p>
                                                                                    <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.molLabel') }}: <strong>{{ $booking->mol }}</strong></p>
                                                                                    <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.countryLabel') }}: <strong>{{ $booking->country }}</strong></p>
                                                                                    <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.cityLabel') }}: <strong>{{ $booking->city }}</strong></p>
                                                                                    <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.addressLabel') }}: <strong>{{ $booking->address }}</strong></p>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:20px;padding-bottom:0;padding-right:25px;padding-left:25px" align="justify">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left">
                                                                                {{-- <table cellpadding="0" cellspacing="0" style="width:100%;vertical-align:top;border-collapse:collapse;border-spacing:0" align="center" border="0">
                                                                                    <thead>
                                                                                        <tr style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0">
                                                                                            <th style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 15px 5px 0;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:0" align="left">{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}</th>
                                                                                            <th style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left">{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}</th>
                                                                                            <th style="border-bottom:2px solid #ecedee;text-align:center;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="center">{{ trans(\Locales::getNamespace() . '/messages.nights') }}</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr style="text-align:left;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0">
                                                                                            <td style="text-align:left;padding:5px 15px 5px 0;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:0" align="left">{{ \Carbon\Carbon::parse($booking->from)->format('d.m.Y') }}</td>
                                                                                            <td style="text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left">{{ \Carbon\Carbon::parse($booking->to)->format('d.m.Y') }}</td>
                                                                                            <td style="text-align:center;padding:5px 0 5px 15px;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:15px" align="center">{{ $booking->nights }}</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table> --}}
                                                                                <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.dfromLabel') }}: <strong>{{ \Carbon\Carbon::parse($booking->from)->format('d.m.Y') }}</strong></p>
                                                                                <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/forms.dtoLabel') }}: <strong>{{ \Carbon\Carbon::parse($booking->to)->format('d.m.Y') }}</strong></p>
                                                                                <p style="text-align:left">{{ trans(\Locales::getNamespace() . '/messages.nights') }}: <strong>{{ $booking->nights }}</strong></p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:20px;padding-bottom:0;padding-right:25px;padding-left:25px" align="justify">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:center">
                                                                                <table cellpadding="0" cellspacing="0" style="width:100%;vertical-align:top;border-collapse:collapse;border-spacing:0" align="center" border="0">
                                                                                    <tbody>
                                                                                        @foreach ($booking->rooms as $slug => $room)
                                                                                            <tr style="text-align:center;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0;background-color:#d2ab67">
                                                                                                <td style="text-align:center;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0;background-color:#d2ab67" colspan="5" align="center" bgcolor="#d2ab67"><strong style="background-color:#d2ab67;display:block">{{ $booking->roomsArray[$slug]['name'] }}</strong></td>
                                                                                            </tr>
                                                                                            <tr style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0">
                                                                                                <td style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 15px 5px 0;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:0" align="left"><strong>#</strong></td>
                                                                                                <td style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left"><strong>{{ trans(\Locales::getNamespace() . '/forms.viewLabel') }}</strong></td>
                                                                                                <td style="border-bottom:2px solid #ecedee;text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left"><strong>{{ trans(\Locales::getNamespace() . '/forms.mealLabel') }}</strong></td>
                                                                                                <td style="border-bottom:2px solid #ecedee;text-align:center;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="center"><strong>{{ trans(\Locales::getNamespace() . '/forms.guestsLabel') }}</strong></td>
                                                                                                <td style="border-bottom:2px solid #ecedee;text-align:right;padding:5px 0 5px 15px;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:15px" align="right"><strong>{{ trans(\Locales::getNamespace() . '/forms.priceLabel') }}</strong></td>
                                                                                            </tr>
                                                                                            @foreach ($room as $id => $r)
                                                                                                <tr style="text-align:left;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0">
                                                                                                    <td style="text-align:left;padding:5px 15px 5px 0;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:0" align="left">{{ $id }}</td>
                                                                                                    <td style="text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left">{{ $booking->viewsArray[$r['view']]['name'] }}</td>
                                                                                                    <td style="text-align:left;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="left">{{ $booking->mealsArray[$r['meal']]['name'] }}</td>
                                                                                                    <td style="text-align:center;padding:5px 15px;padding-top:5px;padding-bottom:5px;padding-right:15px;padding-left:15px" align="center">{{ $r['guests'] }}</td>
                                                                                                    <td style="text-align:right;padding:5px 0 5px 15px;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:15px" align="right">{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($r['priceTotal'], 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($r['priceTotal'] / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}</td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                        <tr style="border-top:2px solid #ecedee;text-align:right;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0">
                                                                                            <th style="border-top:2px solid #ecedee;text-align:right;padding:5px 0;padding-top:5px;padding-bottom:5px;padding-right:0;padding-left:0" colspan="5" align="right"><strong style="color:#a20b35">{{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($booking->price, 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($booking->price / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}</strong></th>
                                                                                        </tr>
                                                                                    </tfoot>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:20px;padding-bottom:0;padding-right:25px;padding-left:25px" align="justify">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left">
                                                                                <p style="text-align:left"><strong>{{ trans(\Locales::getNamespace() . '/messages.paidOnline') }}: {{ trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . number_format($booking->amount, 2) . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . number_format($booking->amount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')' }}</strong></p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px" align="justify">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:left">
                                                                                {!! trans(\Locales::getNamespace() . '/newsletters.terms') !!}
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            <!--[if mso | IE]>
                    </td>
                </tr>
            </table>
            <![endif]-->

            <!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <![endif]-->
                        <div style="margin:0 auto;max-width:600px;background:#fff">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0;width:100%;background:#fff" align="center" border="0">
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:20px 0;padding-bottom:0;padding-top:20px">
                                            <!--[if mso | IE]>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="vertical-align:top;width:600px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:top" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px" align="center">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:center">
                                                                                <p>{{ trans(\Locales::getNamespace() . '/newsletters.contact') }}</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:20px 0;padding-bottom:20px;padding-top:0">
                                            <!--[if mso | IE]>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="vertical-align:middle;width:300px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-50 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px" align="center">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:center">
                                                                                <p>{!! trans(\Locales::getNamespace() . '/newsletters.address') !!}</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                    <td style="vertical-align:middle;width:300px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-50 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px" align="center">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:13px;line-height:22px;text-align:center">
                                                                                <p>{!! trans(\Locales::getNamespace() . '/newsletters.contacts') !!}</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            <!--[if mso | IE]>
                    </td>
                </tr>
            </table>
            <![endif]-->

            <!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <![endif]-->
                        <div style="margin:0 auto;max-width:600px;background:#ddd">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0;width:100%;background:#ddd" align="center" border="0">
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:20px 0;padding-bottom:0;padding-top:0">
                                            <!--[if mso | IE]>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="vertical-align:middle;width:300px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-50 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px" align="center">
                                                                            <div>
                                                                                <!--[if mso | IE]>
                                                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="left">
                                                                                    <tr>
                                                                                        <td>
                                                                                <![endif]-->
                                                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="float:none;display:inline-table" align="left" border="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td style="padding:4px;vertical-align:middle;width:89px"><a href="{{ env('APP_URL') }}" target="_blank"><img alt="" height="auto" src="cid:logo-emails-small.png" style="border:none;border-radius:0;display:block;font-size:13px;outline:0;text-decoration:none;width:100%;height:auto" width="89"></a></td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                <!--[if mso | IE]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                    <td style="vertical-align:middle;width:300px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-50 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:13px;text-align:right;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:10px;padding-bottom:10px;padding-right:25px;padding-left:25px" align="center">
                                                                            <div>
                                                                                <!--[if mso | IE]>
                                                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="right">
                                                                                    <tr>
                                                                                        <td>
                                                                                <![endif]-->
                                                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="float:none;display:inline-table" align="right" border="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td style="padding:4px;vertical-align:middle">
                                                                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="background:#3b5998;border-radius:3px;width:20px" border="0">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td style="font-size:0;vertical-align:middle;width:20px;height:20px;padding:4px 4px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px"><a href="https://www.facebook.com/SunsetResortPomorie"><img alt="facebook" height="20" src="cid:facebook.png" style="display:block;border-radius:3px" width="20"></a></td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                <!--[if mso | IE]>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <![endif]-->
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            <!--[if mso | IE]>
                    </td>
                </tr>
            </table>
            <![endif]-->

            <!--[if mso | IE]>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" align="center" style="width:600px;">
                <tr>
                    <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
            <![endif]-->
                        <div style="margin:0 auto;max-width:600px">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="font-size:0;width:100%" align="center" border="0">
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:20px 0;padding-bottom:20px;padding-top:20px">
                                            <!--[if mso | IE]>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="vertical-align:middle;width:600px;">
                                            <![endif]-->
                                                        <div class="mj-column-per-100 outlook-group-fix" style="vertical-align:middle;display:inline-block;direction:ltr;font-size:11px;text-align:left;width:100%">
                                                            <table role="presentation" cellpadding="0" cellspacing="0" style="vertical-align:middle" width="100%" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:0;padding-bottom:0;padding-right:25px;padding-left:25px" align="center">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center">
                                                                                <p style="font-size:11px">{!! trans(\Locales::getNamespace() . '/newsletters.copyright') !!}</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px;padding-top:0;padding-bottom:0;padding-right:25px;padding-left:25px" align="center">
                                                                            <div style="cursor:auto;color:#000;font-family:Roboto,Helvetica,Arial,sans-serif;font-size:11px;line-height:22px;text-align:center">
                                                                                <p style="font-size:11px">{!! trans(\Locales::getNamespace() . '/newsletters.disclaimer') !!}</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                            <!--[if mso | IE]>
                                                    </td>
                                                </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            <!--[if mso | IE]>
                    </td>
                </tr>
            </table>
            <![endif]-->
        </div>
    </body>
</html>
