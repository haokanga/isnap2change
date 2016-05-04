<?php
	require_once('connection.php');
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$conn = db_connect();
		
		$classid = $_POST["classid"];
		$type = $_POST["type"];
		$username = $_POST["username"];
		$password = $_POST["password"];
		$fname = $_POST["fname"];
		$lname =$_POST["lname"];
		
		
		if($type == "STUDENT"){
			
			$gender = $_POST["gender"];
			$dob = $_POST["dob"];
			
			$insertStudentSql = "INSERT INTO Student(Username, `Password`, FName, LName, Gender, DOB, Score, ClassID)
							     VALUES (?,?,?,?,?,?,?,?);";
			
			$insertStudentSql = $conn->prepare($insertStudentSql);
			
			if($insertStudentSql -> execute(array($username, md5($password), $fname, $lname, $gender, $dob, 0, $classid))){
				echo "<script language=\"javascript\">  alert(\"You have successfully signed up!\"); </script>";	
			} else {
				echo "<script language=\"javascript\">  alert(\"You have failed sign up!\"); </script>";
			}
			
			db_close($conn);
			
		//	header('location:welcome.php');
		}
		
		if($type == "TEACHER"){
			$insertTeacherSql = "INSERT INTO Teacher(Username, `Password`, fname, lname, ClassID)
							     VALUES (?,?,?,?,?);";
								 
			$insertTeacherQuery = db_connect() -> prepare($insertTeacherSql);
			
			if($insertTeacherQuery -> execute(array($username, md5($password), $fname, $lname, $classid))){
				echo "<script language=\"javascript\">  alert(\"You have successfully signed up!\"); </script>";
			} else {
				echo "<script language=\"javascript\">  alert(\"You have failed sign up!\"); </script>";
			}
			
			db_close($conn);
			
		//	header('location:welcome.php');
		}
	 
	}

?>

<html>
<head>
<script>
function validToken(){
	
	var token = document.getElementById("token").value;
	
	if (token.length == 0){
		alert("The field cannot be empty!");
	} else {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("signupForm").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "signup-control.php?token="+token+"&&action="+"VALIDTOKEN", true);
        xmlhttp.send();
	}
}

function validUsername(){
	
	var username = document.getElementById("txtUsername").value;
	var type = document.getElementById("txtType").value;
	
	if (username.length == 0){
		alert("The field cannot be empty!");
	} else {
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        };
        xmlhttp.open("GET", "signup-control.php?username="+username+"&type="+type+"&action="+"VALIDUSERNAME", true);
        xmlhttp.send();
	}
	
	
}

</script>
</head>
<body>

<div id="a" align="center">

<table>
<tr>
<td class="ta">Token</td>
<td class="ta"><input type=text id="token"></td>
</tr>
</table>

<button type=button onclick="validToken()"> Valid </button>
<form id="signupForm" action=signup.php method=post></form>
</div>

</body>
</html>

