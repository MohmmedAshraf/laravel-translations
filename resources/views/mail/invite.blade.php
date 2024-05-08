@component('mail::message')
    # {{ ltu_trans('Hello') }}

    {{ ltu_trans('Someone has invited you to join their  translation team on :name', ['name' => config('app.name')]) }}.

    @component('mail::button', ['url' => $link])
        {{ ltu_trans('Accept Invitation') }}
    @endcomponent

    {{ ltu_trans('This invitation link will expire in :time :unit', ['time' => '24', 'unit' => ltu_trans('hours')]) }}.

    {{ ltu_trans('If you did not request a password reset, no further action is required') }}.

    {{ ('Thanks') }},<br>
    {{ config('app.name') }}
@endcomponent
