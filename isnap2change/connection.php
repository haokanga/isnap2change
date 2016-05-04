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
                }
                catch(PDOException $e){
                        echo "Connection failed: " . $e->getMessage();
                }

                return $conn;
        }

        function db_close($connection){
                $connection = null;
        }

?>
