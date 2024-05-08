@component('mail::message')
    # {{ ltu_trans('Hello') }}

    {{ ltu_trans('You are receiving this email because we received a password reset request for your account') }}.

    @component('mail::button', ['url' => $link])
        {{ ltu_trans('Reset Password') }}
    @endcomponent

    {{ ltu_trans('This password reset link will expire in :time :unit', ['time' => '60', 'unit' => ltu_trans('minutes')]) }}.

    {{ ltu_trans('If you did not request a password reset, no further action is required') }}.

    {{ ('Thanks') }},<br>
    {{ config('app.name') }}
@endcomponent
