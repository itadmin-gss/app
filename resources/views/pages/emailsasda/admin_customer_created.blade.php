<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
            <h2>An Account has been created by Admin:</h2><br>

           <p> First Name: {!!$first_name!!} </p>
           <p> Last Name : {!!$last_name!!} </p>
           <p> Username     : {!!$email!!} </p>
           <p> Temporary Password  : {!!$password!!} </p>

		<div> Please Complete Your Profile by logging in here. You will be prompted to update your username and password. <a href="{!! URL::to('/') !!}">Here</a><br/>
		</div>
	</body>
</html>
