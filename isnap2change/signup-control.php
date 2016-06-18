<?php
	require_once('mysql-lib.php');
	
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		
		$action = $_GET["action"];
		
			if($action == "VALIDTOKEN"){
				$conn = db_connect();
			
				$token = $_GET["token"];
			
				$tokenPreSql = "SELECT COUNT(*)
								FROM Token NATURAL JOIN `Class`
										   NATURAL JOIN  School
							WHERE TokenString = BINARY ?";
				
				$tokenSql = "SELECT ClassID, Type, SchoolName, ClassName 
							 FROM Token NATURAL JOIN `Class`
										NATURAL JOIN  School
							 WHERE TokenString = BINARY ?";
								
				$tokenPreQuery = $conn->prepare($tokenPreSql);
				$tokenPreQuery->execute(array(htmlspecialchars($token)));
				
				if($tokenPreQuery->fetchColumn() == 0){
					echo "No record found!";
					exit;
				}
				
				$tokenQuery = $conn->prepare($tokenSql);
				$tokenQuery->execute(array($token));
				$tokenRes = $tokenQuery->fetch(PDO::FETCH_OBJ);
					
				db_close($conn);	
					
				echo "
					<table>
					<tr>
					<td>School:</td>
					<td>".$tokenRes->SchoolName."</td>
					</tr>
					<td>Class:</td>
					<td>".$tokenRes->ClassName."</td>
					</tr>
					<tr>     
					<td>Type:</td>
					<td>".$tokenRes->Type."</td>
					</tr>
					<tr>
					<td><input type=hidden name=\"classid\" value=".$tokenRes->ClassID."></td>
					</tr>
					<tr>
					<td><input type=hidden id=\"txtType\" name=\"type\" value=".$tokenRes->Type."></td>
					</tr>
					</table>
					";
				
				echo "
				<table>
				<tr>
				<td>Username:</td>
				<td><input type=text id=\"txtUsername\" name=\"username\" onblur=\"validUsername()\"></td>
				</tr>
				<tr>
				<p>Suggestions: <span id=\"txtHint\"></span></p>
				</tr>
				<tr>
				<td>Password:</td>
				<td><input type=password name=\"password\"></td>
				</tr>
				<tr>
				<td>First Name:</td>
				<td><input type=text name=\"fname\"></td>
				</tr>
				<tr>
				<td>Last Name:</td>
				<td><input type=text name=\"lname\"></td>
				</tr>
				";
					
				if($tokenRes->Type == "TEACHER"){
					echo "
					<tr>
					<td><input type=submit></td>
					</tr>
					</table>
					";	
				}
				
				if($tokenRes->Type == "STUDENT"){
					echo "
					<tr>
					<td> Gender:</td>
					<td> <input type=radio name=\"gender\" value=\"Male\"> Male 
						 <input type=radio name=\"gender\" value=\"Female\"> Female </td>
					</tr>
					<tr>
					<td>DOB:</td>
					<td><input type=date name=\"dob\"></td>
					</tr>
					<tr>
					<td><input type=submit></td>
					</tr>
					
					</table>
					";	
				}
			}
			
			if($action == "VALIDUSERNAME"){
				$conn = db_connect();
		
				$username = $_GET["username"];
				
				$type = $_GET["type"];
				
				$userSql = "";
				
				if($type == "TEACHER"){
					$userSql = "SELECT COUNT(*)
								FROM Teacher
								WHERE Username = BINARY ?";
				}
				
				if($type == "STUDENT" ){
					$userSql = "SELECT COUNT(*)
								FROM Student
								WHERE Username = BINARY ?";
				}
				
				$userQuery = $conn->prepare($userSql);
				$userQuery->execute(array($username));
				
				db_close($conn);	
				
				if($userQuery->fetchColumn() == 0){
					echo "This username is valid.";
				} else {
					echo "This username has been occupied!";
				}
				
			}
		
		
	}
?>

