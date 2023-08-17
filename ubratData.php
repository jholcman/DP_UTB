<?php

echo "Mažu data <br>";

include "inc/MySQL.inc";
include "inc/function.php";


//////////////////////
$id_akce = htmlspecialchars($_POST['id'], ENT_QUOTES);


// Check connection

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$conn->set_charset("utf8");


// check for existing record
$sql = "DELETE FROM akce WHERE id=".$id_akce;
if (mysqli_query($conn, $sql)) {
  echo "Record ".$id_akce." deleted successfully....<br>";
} else {
  echo "Error deleting record: " . mysqli_error($conn);
}

$sql = "SELECT * FROM etapy WHERE akce=".$id_akce;
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
     $sqld = "DELETE FROM ucastnici WHERE etapa=".$row['id'];
     $resultd = mysqli_query($conn, $sqld);
  }
}

$sql = "DELETE FROM etapy WHERE akce=".$id_akce;
$result = mysqli_query($conn, $sql);

mysqli_close($conn);

echo "Mazání ukončeno <br>";
echo "<script language=\"JavaScript\">window.location=\"https://dp.wz.cz/index.php?akce=administrace\";</script>";
exit;

?>