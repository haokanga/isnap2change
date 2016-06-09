<?php

        function db_connect(){

			$conn;

			$servername = "localhost";
			$username = "root";
			$password = ".kHdGCD2Un%P";

			try {
				$conn = new PDO("mysql:host=$servername; dbname=isnap2changedb", $username, $password);
				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch(PDOException $e){
				return null;
			}
			
        }

        function db_close($connection){
			$connection = null;
        }

?>
