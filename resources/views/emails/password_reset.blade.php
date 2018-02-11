<h1>{{ Config::get('config_custom.company_name_domain').' - '.Lang::get('confide::confide.email.password_reset.subject') }}</h1>

<p>{{ Lang::get('confide::confide.email.password_reset.greetings', array( 'name' => (isset($user['username'])) ? $user['username'] : $user['email'])) }},</p>

<p>{{ Lang::get('confide::confide.email.password_reset.body') }}</p>
<a href='{{ URL::to('users/reset_password?token='.$token) }}'>
    {{ URL::to('users/reset_password?token='.$token)  }}
</a>

<p>{{ Lang::get('confide::confide.email.password_reset.farewell') }}</p>
