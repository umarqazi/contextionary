@include('email.header')
<h2 class="footer-text">Hi  {{$data['first_name']}} {{$data['last_name']}} </h2>
<br/>
<p>Your registered email-id is {{$data['email']}}, Please click on the below link to verify your Email account</p>
<br/>
<a href="{{lang_url('verifyEmail', ['token'=>$data['email_token']])}}" class="orangeBtn">Verify Account</a>
@include('email.footer')
