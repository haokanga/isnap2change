<?php
$output = shell_exec("echo %JAVA_HOME%");
echo "<strong>JAVA_HOME</strong>";
echo "<hr/>";
echo "$output<br/><br/><br/><br/>";

// Show Java Version
$output = shell_exec("java -version 2>&1");
echo "<strong>Java Version</strong>";
echo "<hr/>";
echo "$output<br/><br/><br/><br/>";

// Show Javac Version
$output = shell_exec("javac -version 2>&1");

echo "<strong>Javac Version</strong>";
echo "<hr/>";
echo "$output<br/><br/><br/><br/>";

// Show The Java Version
$output = shell_exec("javac HelloWorld.java && java HelloWorld");
echo "<strong>Java Exec Demo</strong>";
echo "<hr/>";
echo "$output<br/><br/><br/><br/>";
?>