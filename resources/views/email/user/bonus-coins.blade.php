@include('email.header')
<h2>Hi {{$data['first_name']}} {{$data['last_name']}}</h2>
<br/>
<?php if($data['type']=='translate'){$data['type']='Translation';}elseif($data['type']=='illustrate'){$data['type']='Illustration';}?>
Thank you! Your vote against ‘<span class="text-transform">{!! $data['phrase_name'] !!}</span>’ <span class="text-transform">{!! $data['type'] !!}</span> was a winning vote. 1 bonus coin has been added to your account
<br/>
@include('email.footer')
