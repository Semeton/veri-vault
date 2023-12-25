<x-mail::message>
    <h1>You have received an encrypted email</h1>
    <p>You have received an encrypted email. Please click the button below to view the encrypted email.</p>
    <x-mail::button :url="$url">
        View Encrypted Email
    </x-mail::button>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</x-mail::message>
```
