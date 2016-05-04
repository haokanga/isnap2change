<?php
	session_start();
    require_once("connection.php");
    
    $conn;
    
	//if true, echo debug output in dev mode, else production mode
	$DEBUG_MODE = true;
   
    if($DEBUG_MODE) {
    	echo "DEBUG_MODE<br>";
    }
	
	
	if(isset($_POST['submit'])){
		$errMsg = '';
		//username and password
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$usertype = $_POST['usertype'];
		$tablename = "";
		$idcolumnname = "";
		if(!strcmp($usertype , "student")){
			$tablename = "`Student`";
			$idcolumnname = "StudentID";
		} else if (!strcmp($usertype , "teacher")){
			$tablename = "`Teacher`";
			$idcolumnname = "TeacherID";
		}

        if($DEBUG_MODE) {
    		echo $username."\t".$password."<br>";
    		echo $tablename;
   		}
		
		if($username == '')
			$errMsg .= 'You must enter your Username<br>';
		else {
    		$username = security_check_input($_POST["username"]);
  		}
		
		if($password == '')
			$errMsg .= 'You must enter your Password<br>';
		
		
		if($errMsg == ''){
			$conn = db_connect();
			$sql = $conn->prepare('SELECT COUNT(*) FROM '.$tablename.' WHERE `Username` = BINARY :username AND `Password` = BINARY :password');
			$sql->bindParam(':username', $username);
			$sql->bindParam(':password', md5($password));
			$sql->execute();
			if ($sql->fetchColumn() > 0) {
			    $sql = $conn->prepare('SELECT '.$idcolumnname.',`Username`,`Password` FROM '.$tablename.' WHERE `Username` = BINARY :username AND `Password` = BINARY :password');
				$sql->bindParam(':username', $username);
				$sql->bindParam(':password', md5($password));
				$sql->execute();
				$results = $sql->fetch(PDO::FETCH_ASSOC);
	       		if($DEBUG_MODE) {
	    			echo "count($results)".count($results);
	   			}
				$_SESSION['userid'] = $results[0];
				$_SESSION['username'] = $results['Username'];
				header('location:welcome.php');
				exit;			
			}
			else {
			    $errMsg .= 'Invalid Username or Password<br>';
			}
			db_close($conn);
		}
	}

	//check input security to prevent malformed data/html injection
	function security_check_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
?>


<html>
<head><title>Login Page PHP Script</title></head>
<body>
	<div align="center">
		<div style="width:300px; border: solid 1px #006D9C; " align="left">			
			<?php
				if(isset($errMsg)){
					echo '<div style="color:#FF0000;text-align:center;font-size:12px;">'.$errMsg.'</div>';
				}
			?>
			<div style="background-color:#006D9C; color:#FFFFFF; padding:3px;"><b>Login</b></div>
			<div style="margin:30px">
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<input type="radio" name="usertype" value="student" checked> Student
					<input type="radio" name="usertype" value="teacher"> Teacher
					<br><br>
					<label>Username  :</label><input type="text" name="username" class="box"/><br/><br/>
					<label>Password  :</label><input type="password" name="password" class="box" /><br/><br/>
					<input type="submit" name='submit' value="Submit" class='submit'/><br />
				</form>
			</div>
		</div>
	</div>
</body>
</html>