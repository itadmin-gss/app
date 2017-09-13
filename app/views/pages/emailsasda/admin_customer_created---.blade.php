<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
            <h2>An Account has been created by Admin:</h2><br>
            
           <p> First Name: {{$first_name}} </p>
           <p> Last Name : {{$last_name}} </p>
           <p> Email     : {{$email}} </p>
           <p> Password  : {{$password}} </p> 
            
           

		<div>       
			Please Complete Your Profile by login.{{ URL::to('/') }}<br/>
		</div>
	</body>
</html>
