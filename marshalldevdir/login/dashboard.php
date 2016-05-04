<?php
	session_start();
	echo 'Welcome '.$_SESSION['username'];
?>
<br /><a href='logout.php'>Logout</a>