<x-mail::message>
# VeriVault Verification Code

Hello {{ $data['name'] }}

The body of your message.

# {{ $data['token'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
