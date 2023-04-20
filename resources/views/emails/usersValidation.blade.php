@component('mail::message')
# Welcome, {{ $name }}

You have been added in a company by {{ $userSender->name}}

@component('mail::button', ['url' => 'http://127.0.0.1:3000/RedirectMail/'.$userSender->id.'/'.$invitation->id])
CLick here to validate your account
@endcomponent

Thanks,<br>
MiniProjet Team
@endcomponent
