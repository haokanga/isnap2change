<?php
	session_start();
	if(isset($_SESSION['userid'])){
		echo 'Welcome '.$_SESSION['username'];
		echo 'User ID:  '.$_SESSION['userid'];
		echo '<form action="logout.php" method="post"><input type="submit" value="Log Out"></form>';
	}
	
?>