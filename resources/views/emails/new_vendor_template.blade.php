<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>

        <div> {!!$user_email_template!!} </div>
        <h2>Vendor Account has been created {!!isset($first_name) ? $first_name : ''!!}.</h2>
        <h2>Email: {!!isset($email) ? $email : ''!!}</h2>
        <h2>Username: {!!isset($username) ? $username : ''!!}</h2>
        <div>
            Please Login and complete your profile. <a href="{!! URL::to($token) !!}">Click here</a><br/>
        </div>
    </body>
</html>
