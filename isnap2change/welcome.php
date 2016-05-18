<?php
	session_start();
	if(isset($_SESSION['studentid'])){        
		echo 'User ID:  '.$_SESSION['studentid']."<br>";
		echo 'Welcome '.$_SESSION['username'];        
        echo '<form action="avatar.php" method="post"><input type="submit" value="Avatar"></form>';
		echo '<form action="logout.php" method="post"><input type="submit" value="Log Out"></form>';
	}
	
?>