@include('email.header')
<h2>Hi {{$data['first_name']}} {{$data['last_name']}}</h2>
<br/>
Congratulations! Contextionary is so glad you decided to share your knowledge. As a welcome gift, you are receiving 100 coins to contribute. Start contributing
<br/>
@include('email.footer')
