@include('email.header')
<h2>Welcome to the Contextionary {{$data['first_name']}} {{$data['last_name']}}</h2>
<br/>
Your registered email-id is {{$data['email']}} , Please click on the below link to verify your email account
<br/>
<a href="{{lang_url('verifyEmail', ['token'=>$data['email_token']])}}">Verify Email</a>
@include('email.footer')
