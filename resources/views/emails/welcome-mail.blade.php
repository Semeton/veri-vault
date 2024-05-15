<x-mail::message>
# Welcome to VeriVault!

Hello <b>{{ $data['name'] }}</b>,

Welcome to VeriVault!

I'm Semeton, the founder and primary engineer behind this innovative platform. At VeriVault, our mission is to ensure your data security and privacy through advanced encryption techniques.

<b>Key Features of VeriVault:</b>

<b>Secure Messaging:</b> Send encrypted emails and in-app messages using a unique secret code.<br>
<b>Zero-Knowledge Proof:</b> Built on sodium hashing, ensuring no one but you can access your data.<br>
<b>Vault:</b> Encrypt and save your sensitive data securely.<br>
<b>Access Permissions:</b> Manage who can view your encrypted messages and data.<br>


For developers, we have built our encryption algorithm into a Composer package, which you can use to develop your own applications or for secure encryption in general. You can find it here:

<a href="https://packagist.org/packages/semeton/crypto-service">CryptoService(composer)</a><br>
<a href="https://www.npmjs.com/package/sm-crypto-service">CryptoService(npm)</a>

Additionally, we offer REST services (which the messaging app PWA is built upon) that you can leverage for your own projects.

<x-mail::button :url="'https://documenter.getpostman.com/view/19842116/2s9YsGhD6t'">
API DOCS
</x-mail::button>

As an open-source project, your contributions and feedback are invaluable to us. Feel free to explore our <a href="https://github.com/Semeton/veri-vault">GitHub repository</a> and get involved.

Thank you for joining us on this journey towards better data privacy.

Best regards,

Thanks,<br>
<b>Semeton Balogun,</b><br>
Founder & Lead Engineer.<br>
{{ config('app.name') }}.
</x-mail::message>
