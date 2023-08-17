<?php
  session_start();
  echo "<!-- ".session_id()." -->";
  
  if (isset($_SESSION['user'])) {
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        // 1800 = 30 minut
        //echo "Odhlášeno";
        session_unset();    
        session_destroy();
        unset($_POST);
        unset($_REQUEST);
        echo "<script>location.href = 'index.php';</script>"; 
    } else {
      $_SESSION['LAST_ACTIVITY'] = time();
    }
  }


  include "inc/MySQL.inc";
  include "inc/function.php";
  include "inc/grafy.php";
  include "inc/simple_html_dom.php";

  testExistenceDat();
  
  $prihlasen = "N";
  $vlozit = "inc/default.php";
  $message = "";
  $colour = "red";
  if (!empty($_SESSION['prihlasen']) && $_SESSION['prihlasen']=="A") {
    $prihlasen = "A";
  }
  $isadmin = "0";
  if (!empty($_SESSION['isadmin'])) {
    $isadmin = $_SESSION['isadmin'];
  }

  if (!empty($_POST["akce"])) {    


    if (htmlspecialchars($_POST["akce"]) == "prihlaseni") {    
      $uname = trim($_POST['uname']);
  		$psw = trim($_POST['psw']);
  		$uname = filter_var($uname, FILTER_SANITIZE_STRING);
  		$psw = filter_var($psw, FILTER_SANITIZE_STRING);
      if($uname==$admusr && $psw==$admpass) {
          $_SESSION['user']=$uname;
          $_SESSION['userJmeno']="Administrátor";
          $_SESSION['isadmin']="1";
          $_SESSION['prihlasen']="A";
          $isadmin = "1";
          $prihlasen = "A";        
      } else {
          $conn = mysqli_connect($servername, $username, $password, $dbname);
      		$sql = "SELECT * FROM users WHERE username = ?";
      		$stmt = $conn->prepare($sql);
      		$stmt->bind_param("s", $uname);
      		$stmt->execute();
      		$result = $stmt->get_result();
      		$row = $result->fetch_assoc();
          //Kontrola, zda neni posunuta session
          $sqllg = "SELECT * FROM logapp WHERE sessid = ?";
          $lgmt = $conn->prepare($sqllg);
          $sesid = session_id();
      		$lgmt->bind_param("s", $sesid);
      		$lgmt->execute();
      		$resultlg = $lgmt->get_result();
      		$lgrow = $resultlg->fetch_assoc();
          if($lgrow != NULL){
            $message = "Chyba SESSION přihlášení.";
            $_SESSION['prihlasen']="N";
            $prihlasen = "N";
            $vlozit = "inc/login.php";
          }
          //-----------------------------------------------
      		if($row == NULL){
      			$message = "Špatné jméno nebo heslo.";
            $_SESSION['prihlasen']="N";
            $prihlasen = "N";
            $vlozit = "inc/login.php";
      		} else {
        		if(password_verify($psw, $row["pass"]) == FALSE){
         			$message = "Špatné jméno nebo heslo.";
              $_SESSION['prihlasen']="N";
              $vlozit = "inc/login.php";
        		}else{
              $_SESSION['LAST_ACTIVITY'] = time();
              $_SESSION['user']=$uname;
              $_SESSION['userID']=$row["id"];
              $_SESSION['userJmeno']=$row["jmeno"];
              $_SESSION['pohlavi']=$row["pohlavi"];
              $_SESSION['stravaID']=$row["strava"];
              $_SESSION['isadmin']="0";
              $_SESSION['prihlasen']="A";
              $isadmin = "0";
              $prihlasen = "A";
              //nactiMojeCasy($conn); 
              zapisLog($uname, session_id(), $conn);
              
        		}
            mysqli_close($conn);
          }
      }
      unset($_POST);
      unset($_REQUEST);

    } elseif (htmlspecialchars($_POST["akce"]) == "registrace") {

        	$vlozit = "inc/login.php";
          $conn = mysqli_connect($servername, $username, $password, $dbname);
      		
          $uname=htmlspecialchars($_POST['uname']);
          $pass=htmlspecialchars($_POST['pass']);
          $cpass=htmlspecialchars($_POST['cpass']);
          $name=htmlspecialchars($_POST['name']);
          $email=htmlspecialchars($_POST['email']);
          $strava=htmlspecialchars($_POST['strava']);
          $gender=$_POST['gender'];
          
          $email = trim($email);
      		$uname = trim($uname);
      		$strava = trim($strava);
      		$pass = trim($pass);
      		$cpass = trim($cpass);
      
      		$stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
      		$stmt->bind_param("s", $email);
      		$stmt->execute();
      		$result = $stmt->get_result();
      		$data = $result->fetch_assoc();
      		if($data != NULL){
      			$message = "Email je již zaregistrován, tuto emailovou adresu není možné již použít.";
      		} else {
        		$stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        		$stmt->bind_param("s", $uname);
        		$stmt->execute();
        		$result = $stmt->get_result();
        		$data = $result->fetch_assoc();
        		if($data != NULL){
        			$message = "Uživatel tohoto jména již existuje.";
        		} else {
            		$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            		$stmt = $conn->prepare("INSERT INTO users(jmeno, username, pass, email, strava, pohlavi) VALUES(?,?,?,?,?,?)");
            		$stmt->bind_param("ssssis", $name, $uname, $hashed_password, $email, $strava, $gender);
            		$stmt->execute();
            		if($stmt->affected_rows != 1){
            			$message = "Nepodařilo se uložit uživatele do databáze, zkuste to prosím znovu.";
            		}else{
            			$message = "Byl jste úspěšně zaregistrován.";
                  $colour = "green";
                  unset($_POST['uname']);
                  unset($_POST['pass']);
                  unset($_POST['cpass']);
                  unset($_POST['name']);
                  unset($_POST['email']);
                  unset($_POST['strava']);
                  unset($_POST['gender']);
                  unset($uname);
                  unset($pass);
                  unset($cpass);
                  unset($name);
                  unset($email);
                  unset($strava);
                  unset($gender);
      
            		}
             }
          }
          mysqli_close($conn);
          unset($_POST);
          unset($_REQUEST);

   
    } elseif (htmlspecialchars($_POST["akce"]) == "smazatucet") {
          $conn = mysqli_connect($servername, $username, $password, $dbname);
          if (!$conn) {
             die("Connection failed: " . mysqli_connect_error());
          }
          if (!empty($_SESSION["userID"])) {
            $sql = "DELETE FROM users WHERE id=".$_SESSION['userID'];
            $result = mysqli_query($conn, $sql);
            $sql = "DELETE FROM moje_data WHERE id_jmeno=".$_SESSION['userID'];
            $result = mysqli_query($conn, $sql);            
          }
          session_destroy();
          unset($_POST);
          unset($_REQUEST);
          echo "<script>
          alert('Účet smazán!');
          window.location.href='index.php';  
          </script>";
          exit();

    } elseif (htmlspecialchars($_POST["akce"]) == "zmenahesla") {
          $ppass=$_POST["ppass"];
          $npass=$_POST["npass"];
          $cpass=$_POST["cpass"];
          unset($_POST["akce"]);
          $conn = mysqli_connect($servername, $username, $password, $dbname);
          if (password_verify($ppass,aktualniHeslo($_SESSION['userID'], $conn) == FALSE)) { 
                echo "<script>
                alert('Heslo NEBYLO změněno! Původní heslo nesouhlasí.');
                window.location.href='index.php?akce=muj_ucet';  
                </script>";
          exit();         
          } else {
                zmenitHeslo($_SESSION['userID'], password_hash($npass, PASSWORD_DEFAULT),  $conn);
                echo "<script>
                alert('Heslo změněno!');
                window.location.href='index.php?akce=muj_ucet';  
                </script>";
                exit();
          }
          mysqli_close($conn);
          unset($_POST);
          unset($_REQUEST);

    } elseif (htmlspecialchars($_POST["akce"]) == "zapomenute") {
      		
          $conn = mysqli_connect($servername, $username, $password, $dbname);
      		$femail=htmlspecialchars($_POST['femail']);
          $femail = trim($femail);
          $vlozit = "inc/login.php";
      		if(!filter_var($femail, FILTER_VALIDATE_EMAIL)){
      			$message = "Neplatná emailová adresa.";
      		} else {
        		$stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        		$stmt->bind_param("s", $femail);
        		$stmt->execute();
        		$result = $stmt->get_result();
        		$data = $result->fetch_assoc();
        
        		if($data == NULL){
        			$message = "Emailová adresa není v databázi.";
        		} else {
        
          		$str = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz";
          		$password_length = 8;
          		$new_pass = substr(str_shuffle($str), 0, $password_length);
          		
          		$hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
          
          		$stmt = $conn->prepare("UPDATE users SET pass = ? WHERE email = ?");
          		$stmt->bind_param("ss", $hashed_password, $femail);
          		$stmt->execute();
          		if($stmt->affected_rows != 1){
          			$message =  "Chyba ve spojení s databází."; 
          		} else {
            		$to = $femail; 
            		$subject = "Obnova hesla - Tournament"; 
            		$body = "Můžete se přihlásit s novým heslem.". "\r\n";
            		$body .= $new_pass; 
            
            		$headers = "MIME-Version: 1.0" . "\r\n";
            		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            		$headers .= "From: info@tournament.cz" . "\r\n";
            
            		$send = mail($to, $subject, $body, $headers); 
            		if(!$send){ 
            			$message = "Email nebyl odeslán. Zkuste to znovu.";
            		}else{
            			$message = "Heslo bylo úspěšně obnoveno a zasláno na zadaný email.";
                  $colour = "green";
                  unset($femail);
            		}
              }
            }  
          }
          mysqli_close($conn);
          unset($_POST);
          unset($_REQUEST);
    }
  }
  
  
  if (isset($_GET["akce"])) {
    switch(htmlspecialchars($_GET["akce"])) {
    
      case "trate": $vlozit =  "inc/trate.php"; break;
      case "trat": $vlozit =  "inc/trat.php"; break;
      case "vysledky": $vlozit =  "inc/vysledky.php"; break;
      case "vysledky_final": $vlozit =  "inc/vysledky_final.php"; break;
      case "moje_data": $vlozit =  "inc/moje_data.php"; break;
      case "moje_vysledky": $vlozit =  "inc/moje_vysledky.php"; break;
      case "statistiky": $vlozit =  "inc/statistiky.php"; break;
      case "administrace": $vlozit =  "inc/administrace.php"; break;
      case "login": $vlozit =  "inc/login.php"; break;
      case "muj_ucet": $vlozit =  "inc/useraccount.php"; break;
      case "user": $vlozit =  "inc/user.php"; break;
      case "logoff":  header("location: logoff.php");  exit(); break;       //session_unset();session_destroy(); header("location: index.php");
      
    }
   }
    
?>

<!DOCTYPE html>
<html lang="cs">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
      <meta http-equiv="cache-control" content="no-cache" />
      <meta http-equiv="Pragma" content="no-cache" />
      <meta http-equiv="Expires" content="-1" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">

    <title>TRAILTOUR - analýza a predikce</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!--
Ramayana CSS Template
https://templatemo.com/tm-529-ramayana
-->

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-style.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="style.css">

  </head>

<body class="is-preload">

    <!-- Wrapper -->
    <div id="wrapper">

      <!-- Main -->
        <div id="main">
          <div class="inner">



            <!-- Header -->
            <header id="header">
              <div class="logo">

                        <div class="button-login">
 <?php
   if ($prihlasen == "N") {
      echo "                    <a href=\"?akce=login\">Přihlášení | registrace</a>";
   } else {
      //echo "                    <a href=\"?akce=user\" style=\"margin-right: 0px\">Uživatel: ".$_SESSION['userJmeno']."</a> | <a href=\"?akce=logoff\">Odhlásit</a>";
      echo "                    <span style=\"color: white;\">Uživatel: ".$_SESSION['userJmeno']." | </span><a href=\"?akce=logoff\">Odhlásit</a>";
   }   
?>
                        </div>

              </div>
            </header>

<?php
  include $vlozit;
?>


          </div>
        </div>

      <!-- Sidebar -->
        <div id="sidebar">

          <div class="inner">

            <!-- Search Box -->
            <section id="search" class="alt">
              <div class="logo">
                <a href="index.php">
                 <img src="assets/images/logo_tournament.jpg" alt="Tournament">
                </a>
              </div>
            </section>
              
            <!-- Menu -->
            <nav id="menu">
              <ul>
                <li><a href="index.php">Úvodní strana</a></li>
<?php                if ($prihlasen == "A") {
                        if ($isadmin == "1") {
                           echo "<li><a href=\"?akce=administrace\">Administrace</a></li>";
                        } else {
                           echo "<li><a href=\"?akce=muj_ucet\">Můj účet</a></li>";
                        }
                     }
?>
                <li><a href="?akce=statistiky">Statistiky závodů</a></li>
<?php
                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }
                //$sql = "SELECT stat,rok FROM `etapy` GROUP BY stat,rok ORDER BY rok DESC, stat ASC"; 
                $sql = "SELECT * FROM `akce` ORDER BY poradi DESC";
                $result = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($result) > 0) {
                  while($row = mysqli_fetch_assoc($result)) {
                    
                    echo "<li>\n";
                    echo " <span class=\"opener\">".$row["nazev"]."</span>\n";       // hlavní akce
                
                    $sql1 = "SELECT * FROM etapy WHERE akce=".$row["id"]." ORDER BY poradi";
                    $result1 = mysqli_query($conn, $sql1);
                    
                    if (mysqli_num_rows($result1) > 0) {
                      echo "               <ul>\n";
                      echo "                 <li><a href=\"?id_akce=".$row["id"]."&akce=trate\">Etapy</a></li>\n";
                      echo "                 <li><a href=\"?id_akce=".$row["id"]."&akce=vysledky\">Výsledky</a></li>\n";
                      //echo "                 <li><a href=\"?id_akce=".$row["id"]."&akce=vysledky_final\">Celkové pořadí</a></li>\n";
                      if ($prihlasen == "A") {
                         if ($isadmin == "0") {
                            echo "                 <li><a href=\"?id_akce=".$row["id"]."&akce=moje_vysledky\">Moje výsledky</a></li>\n";
                        }
                      }
                      /*while($row1 = mysqli_fetch_assoc($result1)) {
                        echo "                 <li><a href=\"?id_trate=".$row1["id"]."&akce=trat\"> ".$row1["poradi"].". ".$row1["etapa"]."</a></li>\n";   //tratě
                      }*/
                      echo "               </ul>\n";
                    }
               
                    echo "</li>\n";
                    
                  }
                }
                mysqli_close($conn);

?>
                <!-- <li><a href="https://www.google.com">Užitečné odkazy</a></li> -->
              </ul>
            </nav>

            <!-- Užitečné odkazy -->
            <div class="featured-posts">
              <div class="heading">
                <h2>Užitečné odkazy</h2>
              </div>
              <div class="owl-carousel owl-theme">
                <a href="http://www.trailtour.cz/" target="_blank">
                  <div class="featured-item">
                    <img src="assets/images/odkaz_trailtour.jpg" alt="odkaz Trailtour.cz">
                    <p>Český a slovenský portál etapového běžeckého závodu TRAILTOUR.</p>
                  </div>
                </a>
                <a href="https://www.strava.com/" target="_blank">
                  <div class="featured-item">
                    <img src="assets/images/odkaz_strava.jpg" alt="odkaz Strava.com">
                    <p>Mezinárodní webová a mobilní aplikace etapových cyklistických nebo běžeckých závodů.</p>
                  </div>
                </a>
                <a href="#" target="_blank">
                  <div class="featured-item">
                    <img src="assets/images/odkaz_knn.jpg" alt="odkaz K-nn">
                    <p>K-nn - Algoritmus pro klasifikaci a regresi na principu učení pod dohledem.</p>
                  </div>
                </a>
              </div>
            </div>

            <!-- Footer -->
            <footer id="footer">
              <p class="copyright">Copyright &copy; 2023 UTB, Bc. Holcman Jiří
              <br>Designed by <a rel="nofollow" href="https://www.facebook.com/templatemo">Template Mo</a></p>
            </footer>

          </div>
        </div>

    </div>

  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/transition.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/custom.js"></script>

</body>

</html>
