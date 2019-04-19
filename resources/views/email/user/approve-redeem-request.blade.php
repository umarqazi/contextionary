@include('email.header')
<h2>Hi {{$data['first_name']}} {{$data['last_name']}}</h2>
<br/>
 Congratulations! Your redeem request has been approved. Thank you for being a valuable contributor at Contextionary.
<br/>
@include('email.footer')
