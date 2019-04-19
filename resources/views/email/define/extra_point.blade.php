@include('email.header')
<h2>Hi {{$data['users']['first_name']}} {{$data['users']['last_name']}}</h2>
<br/>
<p>Thank you for your input. You did not win the vote for best contribution. Because we value your effort and wish you win next time, you have earned {!! $data['points'] !!} point for your participation in <span class="text-transform">{!! $data['type'] !!}</span> against <span class="text-transform">‘{!! $data['phrase_name'] !!}’</span>.</p>
<br/>
@include('email.footer')
