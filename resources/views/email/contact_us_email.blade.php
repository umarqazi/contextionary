<!DOCTYPE html>
<html>
    <head>
        <title>Contact Us Email</title>
    </head>

    <body>
        <p>{{$data->message}}</p>
        <br/>
        <p>
            <strong>From:</strong>
            <br/>
            {{$data->first_name}} {{$data->last_name}}
            <br/>
            {{$data->email}}
        </p>
    </body>
</html>
