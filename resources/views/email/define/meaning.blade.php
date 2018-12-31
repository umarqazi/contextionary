@include('email.header')
<h2>Hi {{$data['meaning']['users']['first_name']}} {{$data['meaning']['users']['last_name']}}</h2>
<br/>
<?php if($data['type']=='translate'){$data['type']='Translation';}elseif($data['type']=='illustrate'){$data['type']='Illustration';}?>
    <p>Congratulations! Your <span class="text-transform">{!! $data['type'] !!}</span> against '<span class="text-transform">{!! $data['phrase_name'] !!}</span>' has got {!! $data['position'] !!} Position in the contest.
    {!! $data['points'] !!} points have been added into your account</p>
<br/>
@include('email.footer')
