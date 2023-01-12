<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<a href="{{ $actionUrl }}" style="display: inline-block;">
    <img src="{{asset('assets/img/bienvenida-mail-plataforma.png')}}" class="Fondo Coorsa" alt="Coorsa Logo" style="width: 100%">
</a>
</td>
</tr>
{{-- Footer --}}
</table>
{{-- Subcopy --}}

<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border-top: 1px solid #e8e5ef; margin-top: 25px; padding-top: 25px;">
<tr>
<td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
<p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => $actionText,
    ]
)
<span class="break-all" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont,'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; word-break: break-all;">
 <a href="{{$actionUrl}}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4;">
 {{$actionUrl}}
</a>
</span>
</p>

</td>
</tr>
{{-- Copy Right --}}
<tr>
    <td>
    <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
    <td class="content-cell" align="center">
        © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
    </td>
    </tr>
    </table>
    </td>
    </tr>
</table>
</body>
</html>
