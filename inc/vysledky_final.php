                                        <?php

                if (!empty($_SESSION["userID"])) $id_user = $_SESSION['userID'];
                if (!empty($_SESSION["pohlavi"])) $pohlavi = $_SESSION['pohlavi'];
                if (!empty($_SESSION["stravaID"])) $stravaID = $_SESSION['stravaID']; 
                $id_akce = (int) htmlspecialchars($_GET["id_akce"]);
                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM akce WHERE id=".$id_akce;
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                
                $f_jmeno="";
        				$f_klub="";
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                	$f_jmeno=$_POST["f_jmeno"];
        					$f_klub=$_POST["f_klub"];
                }

?>

            <!-- Page Heading -->
            <div class="page-heading">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <h1><?php echo $row["nazev"]."<br>\n"; ?></h1>
                    <h2>Celkové pořadí</h2><br>
<?php                if (!empty($_SESSION['prihlasen']) && $_SESSION['prihlasen']=="A" && $_SESSION['isadmin']=="0") {   ?>
                        <p>Celkové pořadí závodníků za všechny etapy tohoto ročníku. Barevně jsou odlišeny vaše výsledky a to ručně zadané nebo predikované. 
                        <ul>
                          <li><strong style="color: #00963c;">Zeleně</strong> - váš výsledek z oficiálních dat Trailtour.</li> 
                          <li><strong style="color: orange;">Oranžově</strong> - váš výsledek na základě vašich dosavadních ručně zadaných a predikovaných výsledků.</li>
                        </ul>
                        </p>
<?php               } else {     ?>
                    <p>Celkové pořadí závodníků za všechny etapy tohoto ročníku (muži a ženy dohromady).</p>
<?php               }        ?>
                  </div>
                </div>
              </div>
            </div>

            
            <!-- Tables -->
            <section class="tables">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">

                      <form method="post">                      
                      <table >
                        <tr>
                            <td>
                              <label><b>Vyhledávání</b></label>
                            </td>
                            <td>
                              <label for="f_jmeno">Jméno:</label>
                              <input type="text" placeholder="Jméno" name="f_jmeno" value="<?php echo @$f_jmeno; ?>" size="40">
                            </td>
                            <td>
                              <label for="f_klub">Klub:</label>
                              <input type="text" placeholder="Klub" name="f_klub" value="<?php echo @$f_klub; ?>" size="40">
                            </td>
                            <td>
                              <input type="submit" name="Hledat" value="Hledat">
                            </td>
                        </tr>
                      </table>
                      </form>
                      <br>

                    <div class="default-table">
                      <table>
                        <thead>
                          <tr>
                            <th>Pořadí</th>
                            <th>Jméno</th>
                            <th>Klub</th>
                            <th style="text-align: right;">Body </th>
                          </tr>
                        </thead>
                        <tbody>

<?php
					  $mamBody = false;
            if ($prihlasen == "A" AND $_SESSION['isadmin'] == "0") {
              $sql2 = "SELECT SUM(body) as bodyCelkem FROM moje_data WHERE (id_jmeno=".$id_user." AND id_etapa IN (select id from etapy where akce=".$id_akce."))";
						  $result2 = mysqli_query($conn, $sql2);
  						if (mysqli_num_rows($result2) > 0) {
  							$mojeBody = mysqli_fetch_assoc($result2);
  							$mojeBodyCelkem = $mojeBody["bodyCelkem"];
  							$mamBody = true;
  						}
            }
            $sql1 = "SELECT jmeno,stravaID, sum(body) as bodyCelkem,klub,pohlavi FROM ucastnici WHERE (pohlavi IN ('M','Z') AND etapa IN (select id from etapy where akce=".$id_akce.")) GROUP BY stravaID,jmeno,klub,pohlavi ORDER BY bodyCelkem DESC";
            $result1 = mysqli_query($conn, $sql1);
            $poradi = 1;
            if (mysqli_num_rows($result1) > 0) { 
                while($prvek = mysqli_fetch_assoc($result1)) {
  
                  if ($mamBody == true AND $pohlavi == "M") {
                    if ($prvek["bodyCelkem"] < $mojeBodyCelkem) {
                      echo "<tr style=\"background-color:orange;color:#FFFFFF;font-weight: bold;\">\n";
                      echo "  <td> </td>\n";
                      //echo "  <td>".utf8_decode($prvek["jmeno"])."</td>\n";
  	               	  echo "  <td>".$_SESSION['userJmeno']."</td>\n";
            		      echo "  <td> </td>\n";
                  		echo "  <td style=\"text-align:right;\">".number_format($mojeBodyCelkem,2, ',', ' ')." </td>\n";
                      echo "  </tr>\n";
                      $mamBody = false;
                    }
                  }
  
                  if ($prihlasen == "A" AND $_SESSION['isadmin'] == "0" AND $_SESSION['stravaID'] == $prvek["stravaID"]) {
                    echo "<tr style=\"background-color:#00963c;color:#FFFFFF;font-weight: bold;\">\n";
                    echo "  <td>".$poradi."</td>\n";
                    echo "  <td>".$_SESSION['userJmeno']."</td>\n";
                    echo "  <td>".$prvek["klub"]."</td>\n";
                    echo "  <td style=\"text-align: right;\">".number_format($prvek["bodyCelkem"],2, ',', ' ')." </td>\n";
                    echo "  </tr>\n";
                  } else {
                    if (str_contains(jmeno($prvek["stravaID"],$prvek["pohlavi"]), $f_jmeno) AND str_contains($prvek["klub"], $f_klub)) {
                        echo "  <tr>\n";
                        echo "  <td>".$poradi."</td>\n";
                        echo "  <td>".jmeno($prvek["stravaID"],$prvek["pohlavi"])."</td>\n";
                        echo "  <td>".$prvek["klub"]."</td>\n";
                        echo "  <td style=\"text-align: right;\">".number_format($prvek["bodyCelkem"],2, ',', ' ')." </td>\n";
                        echo "  </tr>\n";
                     }   
                  }
                  $poradi = $poradi + 1;
                }
              }   
?>
                        </tbody>
                      </table>
                    </div>

                    <div class="section-heading">
                      <br><br>
                      <h2>Kluby</h2>
                    </div>
                    <div class="default-table">
                      <table>
                        <thead>
                          <tr>
                            <th>Pořadí</th>
                            <th>Klub</th>
                            <th style="text-align: right;">Body </th>
                          </tr>
                        </thead>
                        <tbody>

<?php
                      $sql1 = "SELECT SUM(body) as bodyCelkem,klub,stravaID FROM ucastnici WHERE (pohlavi='K' AND etapa IN (select id from etapy where akce=".$id_akce.")) GROUP BY klub,stravaID ORDER BY bodyCelkem DESC";
                      $result1 = mysqli_query($conn, $sql1);
                      $poradi = 1;
                      if (mysqli_num_rows($result1) > 0) { 
                            while($prvek = mysqli_fetch_assoc($result1)) {
                              if (str_contains($prvek["klub"], $f_klub)) {
                                  echo "<tr>\n";
                                  echo "  <td>".$poradi."</td>\n";
                                  echo "  <td>".$prvek["klub"]."</td>\n";
                                  echo "  <td style=\"text-align: right;\">".number_format($prvek["bodyCelkem"],2, ',', ' ')." </td>\n";
                                  echo "  </tr>\n";
                              }
                              $poradi = $poradi + 1;
                            }
                      }   
?>
                        </tbody>
                      </table>
                    </div>


                  </div>
                </div>
              </div>
            </section>
        