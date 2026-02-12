<x-mail::message>
# You're Invited!

Hi {{ $contributor->name }},

You've been invited to collaborate on translations. Click the button below to set your password and get started.

<x-mail::button :url="$inviteUrl">
Accept Invitation
</x-mail::button>

This invitation link will expire in {{ $expiresDays }} {{ Str::plural('day', $expiresDays) }}.

If you didn't expect this invitation, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
