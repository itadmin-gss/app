<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{$email_text}}</h2>
        <p> First Name: {{$first_name}} </p>
        <p> Last Name : {{$last_name}} </p>
        <p> Email     : {{$email}} </p>
        <p> User ID  : {{$id}} </p>
        <div>  <a href="{{ URL::to('active-user/'.$id) }}">Approve</a> <br/></div>
    </body>
</html>
