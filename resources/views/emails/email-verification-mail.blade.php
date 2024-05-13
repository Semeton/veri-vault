<x-mail::message>
# VeriVault Verification Code

Hello {{ $data['name'] }}

Thank you for registering VeriChat. Here is your verification code:

# {{ $data['token'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
