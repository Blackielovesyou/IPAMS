@component('mail::message')
# Password Reset Request

Click the button below to reset your password:

@component('mail::button', ['url' => $resetLink])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent