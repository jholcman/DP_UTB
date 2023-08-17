 <?php
session_start();
session_unset();    
session_destroy();
unset($_POST);
unset($_REQUEST);
header("location: index.php");
?>