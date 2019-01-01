@include('email.header')
<h2>Hi {{$data['first_name']}} {{$data['last_name']}}</h2>
<br/>
Congratulations! You have assign Basic Plan to your account, You can switch to contributor plan and start contributions and upgrade your package
<br/>
@include('email.footer')
