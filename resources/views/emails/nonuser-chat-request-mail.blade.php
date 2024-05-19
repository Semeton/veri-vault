<x-mail::message>
# You have been invited to VeriChat

Hello there,

You just received a chat request from <b>{{ $email }}</b>.

To accept the request, please install the VeriChat application and register your account by clicking the button below:

<x-mail::button :url="'https://app.verivault.xyz'">
INSTALL VERICHAT
</x-mail::button>

Best regards,<br>
The VeriChat Team.
</x-mail::message>
