@component('mail::message')
# Device Assigned

Hello {{ $userName }},

Your new device **{{ $deviceName }}** has been successfully assigned to you.


@component('mail::panel')
API KEY: `{{ $ApiKey }}`
@endcomponent

{{-- @component('mail::button', ['url' => '#', 'color' => 'success'])
Activate Device
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
