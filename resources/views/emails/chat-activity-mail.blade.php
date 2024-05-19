<x-mail::message>
# {{ $data['type'] }}

Hello {{ $data['name'] }},

<?php echo $data["message"]; ?>
<br>

Best regards,<br>
The VeriChat Team.
</x-mail::message>
