@include('email.header')
<h2>Hi {{$data['meaning']['users']['first_name']}} {{$data['meaning']['users']['last_name']}}</h2>
<br/>
    <p>Congratulations! Your <span class="text-transform">{!! $data['type'] !!}</span> against {!! $data['phrase_name'] !!} has got {!! $data['position'] !!} Position in the contest.
    {!! $data['points'] !!} has been added into your account</p>
<br/>
@include('email.footer')
