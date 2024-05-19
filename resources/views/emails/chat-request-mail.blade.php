<x-mail::message>
# Chat Request

Hello <b>{{ $data['name'] }}</b>,

You just received a chat request from <b>{{ $data['senderEmail'] }}</b>.

Kindly login to your VeriChat application to accept or reject the request.

If you do not have the app installed already, you can install it and register to accept the request BY clicking the button below.

<x-mail::button :url="'https://app.verivault.xyz'">
INSTALL VERICHAT
</x-mail::button>

Best regards,<br>
The VeriChat Team.
</x-mail::message>
