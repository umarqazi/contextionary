<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>

<body>
<h2>Hi {{$data['meaning']['users']['first_name']}} {{$data['meaning']['users']['last_name']}}</h2>
<br/>
    Congratulations! Your meaning "{!! $data['meaning']['meaning'] !!}}}" has got {!! $data['position'] !!} Position in the contest.
    {!! $data['points'] !!} has been added into your account
<br/>

Thank You!
</body>

</html>
