@include('email.header')
<h2>Hi {{$data['first_name']}} {{$data['last_name']}}</h2>
<br/>
Your request for {!! $data['points'] !!} Points ${!! $data['earning'] !!} has been sent to the Admin. Admin will verify that request and ${!! $data['earning'] !!} will transfer into your account
<br/>
@include('email.footer')
