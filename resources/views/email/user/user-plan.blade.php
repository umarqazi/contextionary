@include('email.header')
<h2>Hi {{$data['first_name']}} {{$data['last_name']}}</h2>
<br/>
Congratulations! You are now a Basic Plan user. Enjoy your visit.
<br/>
@include('email.footer')
