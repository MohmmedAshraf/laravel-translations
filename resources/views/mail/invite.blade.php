@component('mail::message')
    # Hello

    Someone has invited you to join their  translation team on {{ config('app.name') }}.

    @component('mail::button', ['url' => $link])
        Accept Invitation
    @endcomponent

    This invitation link will expire in 24 hours.

    If you did not request a password reset, no further action is required.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
