<!DOCTYPE html>
<html>
<body>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  Username: <input type="text" name="fusername"><br>
  Password: <input type="text" name="fpassword"><br>
  <input type="submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $username = $_REQUEST['fusername']; 
    if (empty($username)) {
        echo "Username is empty<br>";
    } else {
        echo $username;
    }
    $password = $_REQUEST['fpassword']; 
    if (empty($password)) {
        echo "Password is empty<br>";
    } else {
        echo $password;
    }
}
?>

</body>
</html>