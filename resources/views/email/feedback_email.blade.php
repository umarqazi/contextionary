<!DOCTYPE html>
<html>
    <head>
        <title>Feedback Email</title>
    </head>

    <body>

        <p>
            <strong>
                {{$data->question}}
            </strong>
            <br/>
            {{$data->message}}
        </p>
        <br/>
        <p>
            <strong>From:</strong>
            <br/>
            {{$user->first_name}} {{$user->last_name}}
            <br/>
            {{$data->email}}
        </p>
    </body>
</html>
