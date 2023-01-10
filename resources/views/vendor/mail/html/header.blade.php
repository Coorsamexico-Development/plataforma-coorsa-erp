@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://coorsamexico.com/assets/img/PNG/logos/Logo_Rosa_Coorsa.png" class="logo" alt="Coorsamexico Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
