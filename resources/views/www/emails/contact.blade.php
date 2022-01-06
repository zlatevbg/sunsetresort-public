<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- Using xHTML strict based on the fact that gmail & hotmail/Outlook.com uses it. -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ trans(\Locales::getNamespace() . '/forms.contactSubject') }}</title>
 <style type="text/css">
  /** Client-specific Reset Styles **/
        #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
        html {font-size:14px;line-height:120%;}
  body {font-size:14px;line-height:120%;width:100%!important;min-width:100%;height:100%!important;min-height:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin:0;padding:0;background:#ffffff;color:#000000;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;} /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
        .ExternalClass {width:100%;} /* Force Hotmail/Outlook.com to display emails at full width */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;} /* Force Hotmail/Outlook.com to display normal line spacing. */
        #backgroundTable {background:#ffffff;margin:0;padding:0;width:100%!important;line-height:120%!important;}
  /* Yahoo! Beta shortcuts are back */
  span.yshortcuts {color:#000;background-color:none;border:none;}
  span.yshortcuts:hover, span.yshortcuts:active, span.yshortcuts:focus {color:#000;background-color:none;border:none;}
        /** END Client-specific Reset Styles **/
 </style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="width:100%;min-width:100%;height:100%;min-height:100%;line-height:120%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin:0;padding:0;background:#ffffff;color:#000000;font-family:Helvetica,Arial,sans-serif;font-size:14px" alink="#2a5db0" link="#2a5db0" bgcolor="#ffffff" text="#000000">

 <!-- Wrapper/Container Table:width:100%. Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
 <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" align="center" id="backgroundTable" style="width:100%;min-width:100%;height:100%;min-height:100%;line-height:120%;background:#ffffff;color:#000000;font-family:Helvetica,Arial,sans-serif;font-size:14px;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0;border-collapse:collapse;margin:0;padding:0;">
     <tr>
         <td valign="top" width="100%" height="100%" align="center" style="width:100%;min-width:100%;height:100%;min-height:100%;padding:0;margin:0;border-spacing:0;border-collapse:collapse;">
   <center style="width:100%;min-width:100%;height:100%;min-height:100%;">

    <!--
    Header
    -->

    <table cellpadding="0" cellspacing="0" border="0" width="100%" align="center" style="width:100%;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0;border-collapse:collapse;padding:0;margin:0;">
     <tr>
      <td valign="top" width="100%" align="center" style="width:100%;padding:0;margin:0;border-spacing:0;border-collapse:collapse;">
      <center style="width:100%;">
       <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="max-width:960px;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0;border-collapse:collapse;padding:0;margin:0 auto;">
        <tr>
         <td valign="top" align="center" style="padding:10px;margin:0;text-align:center;border-spacing:0;border-collapse:collapse;"><img src="{{ \App\Helpers\autover('/img/' . $data['locale'] . '/logo-emails.png') }}" width="222" height="150" align="center" border="0" style="border:none;height:auto;-ms-interpolation-mode:bicubic;line-height:100%;outline:none;text-decoration:none;display:inline;text-align:center;"></td>
        </tr>
       </table>
      </center>
      </td>
     </tr>
    </table>

    <!-- Wrapper/Container Table max-width:960px -->
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="max-width:960px;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0;border-collapse:collapse;padding:0;margin:0 auto;">
     <tr>
      <td valign="top" width="10" align="left" style="width:10px;min-width:10px;padding:0;margin:0;border-spacing:0;border-collapse:collapse;">&nbsp;</td>
      <td valign="top" width="100%" align="center" style="max-width:940px;padding:0;margin:0;border-spacing:0;border-collapse:collapse;">
      <center style="width:100%;">

       <!--
       Content
       -->

       <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0;border-collapse:collapse;padding:0;margin:0 auto;">
        <tr>
         <td colspan="3" valign="top" width="100%" height="20" style="width:100%;height:20px;padding:0;margin:0;border-spacing:0;border-collapse:collapse;"> </td>
        </tr>
        <tr>
         <td colspan="3" valign="top" width="100%" align="left" style="width:100%;padding:0;margin:0;border-spacing:0;border-collapse:collapse;">
          <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0;border-collapse:collapse;padding:0;margin:0 auto;">
           <tr>
            <td valign="top" width="100%" align="left" style="width:100%;padding:0;margin:0;border-spacing:0;border-collapse:collapse;">
             <p><strong>{{ trans(\Locales::getNamespace() . '/forms.nameLabel') }}</strong>: {{ $data['name'] }}</p>
             <p><strong>{{ trans(\Locales::getNamespace() . '/forms.emailLabel') }}</strong>: {{ $data['email'] }}</p>
             <p><strong>{{ trans(\Locales::getNamespace() . '/forms.phoneLabel') }}</strong>: {{ $data['phone'] }}</p>
             <p><strong>{{ trans(\Locales::getNamespace() . '/forms.messageLabel') }}</strong>: {!! nl2br($data['message']) !!}</p>
            </td>
           </tr>
          </table>
         </td>
        </tr>
        <tr>
         <td colspan="3" valign="top" width="100%" height="20" style="width:100%;height:20px;padding:0;margin:0;border-spacing:0;border-collapse:collapse;"> </td>
        </tr>
       </table>

      </center>
      </td>
      <td valign="top" width="10" align="left" style="width:10px;min-width:10px;padding:0;margin:0;border-spacing:0;border-collapse:collapse;">&nbsp;</td>
     </tr>
    </table>
    <!-- End of wrapper table max-width:960px -->

   </center>
   </td>
     </tr>
    </table>
    <!-- End of wrapper table width:100% -->
</body>
</html>
