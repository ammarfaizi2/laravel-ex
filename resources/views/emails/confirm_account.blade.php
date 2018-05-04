<h1>{{ Lang::get('confide::confide.email.account_confirmation.subject') }}</h1>

<p>{{ Lang::get('confide::confide.email.account_confirmation.greetings', array('name' => (isset($user['username'])) ? $user['username'] : $user['email'])) }},</p>

<p>{{ Lang::get('confide::confide.email.account_confirmation.body') }}</p>
<a href='{{route('user.confirm_account')."?code={$user['confirmation_code']}"}}'>
    {{ route('user.confirm_account')."?code={$user['confirmation_code']}" }}
</a>

<p>{{ Lang::get('confide::confide.email.account_confirmation.farewell') }}</p>
