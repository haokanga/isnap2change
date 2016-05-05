<?php
	session_start();
	if(isset($_SESSION['userid'])){        
		echo 'User ID:  '.$_SESSION['userid']."<br>";
		echo 'Welcome '.$_SESSION['username'];
		echo '<form action="logout.php" method="post"><input type="submit" value="Log Out"></form>';
	}
	
?>