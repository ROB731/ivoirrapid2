@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Ivoirrapid')
<img src="https://ivoirrapid.ci/asset/Logo IRN.png" class="logo" alt="Ivoirrapid Logo">
@else 
{{ $slot }}
@endif
</a>
</td>
</tr>
