<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
            <h2>A Customer has been created by Admin:</h2><br>

           <p> First Name: {{$first_name}} </p>
           <p> Last Name : {{$last_name}} </p>
           <p> Email     : {{$email}} </p>
           <p> Password  : {{$password}} </p>
         

		<div>  <a href="{{ URL::to('active-customer/'.$user_id) }}">Approve</a> <br/>
		</div>
	</body>
</html>
