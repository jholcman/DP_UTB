<?php

                if (!empty($_SESSION["userID"])) $id_user = $_SESSION['userID'];
                if (!empty($_SESSION["pohlavi"])) $pohlavi = $_SESSION['pohlavi'];
                if (!empty($_SESSION["stravaID"])) $stravaID = $_SESSION['stravaID']; 
                $k = 5;                                                                 // k-NN parametr k
                $id_akce = htmlspecialchars($_GET["id_akce"]);
                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }
                $sql = "SELECT * FROM akce WHERE id=".$id_akce;
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                	
        					if ($_POST["akce"] == "Uložit") {
           					$sql2 = "DELETE FROM moje_data WHERE (id_jmeno=".$_SESSION['userID']." AND id_etapa=".$_POST["id_etapa"].")";
                    $result2 = mysqli_query($conn, $sql2);
              			if (!empty($_POST["cas"])) {
                			//echo "<script>alert(\"xx:".$_POST["cas"]."\");</script>";
                			$casMuj = $_POST["cas"];
                			if (strlen($casMuj) == 5) {
                				$casMuj = $casMuj .":00";
                			} 
                			if (timeToSeconds($casMuj) > 0) {
      						        $tsCislo = floatval($_POST["ts"]);
                          $bodyMoje = 0;
                          $bodyMoje = (100*(2.5-(timeToSeconds($casMuj)/$tsCislo)));
                          //echo "<script>alert(\"".$bodyMoje." - ".$tsCislo."\");</script>";
                          $sql2 = "INSERT INTO moje_data (id_jmeno, id_strava, id_etapa, id_akce, cas, body, pohlavi, imputace) VALUES (".$_SESSION['userID'].", ".$_SESSION['stravaID'].", ".$_POST["id_etapa"].", ".$_POST["id_akce"].", '".$casMuj."', ".$bodyMoje.", '".$pohlavi."', 'N')";
                          $result2 = mysqli_query($conn, $sql2);
                			}
                		}
        					} elseif ($_POST["akce"] == "Smazat") {
          					$sql2 = "DELETE FROM moje_data WHERE (id_jmeno=".$_POST["id_user"]." AND id_etapa=".$_POST["id_etapa"].")";
                    $result2 = mysqli_query($conn, $sql2);
        					} elseif ($_POST["akce"] == "Predikce") {
                      $vysledek = predikujVysledek($_POST["id_user"], $_POST["id_etapa"], $_POST["id_akce"], $k, "A"); // k - počet sousedů
      
                			$casMuj = $vysledek;
                			if (strlen($casMuj) == 5) {
                				$casMuj = $casMuj .":00";
                			} 
                			if (timeToSeconds($casMuj) > 0) {
        					        $tsCislo = floatval($_POST["ts"]);
                          $bodyMoje = 0;
                          $bodyMoje = (100*(2.5-(timeToSeconds($casMuj)/$tsCislo)));
                          //echo "<script>alert(\"".$bodyMoje." - ".$tsCislo."\");</script>";
                          $sql2 = "INSERT INTO moje_data (id_jmeno, id_strava, id_etapa, id_akce, cas, body, pohlavi, imputace) VALUES (".$_POST["id_user"].", ".$_SESSION['stravaID'].", ".$_POST["id_etapa"].", ".$_POST["id_akce"].", '".$casMuj."', ".$bodyMoje.", '".$pohlavi."', 'P')";
                          $result2 = mysqli_query($conn, $sql2);
                			}
      
      
        					}
				        }

                  $pocetTrailtour = 0;
                  if ($_SESSION['stravaID'] > 0) {
                    $sqltest = "SELECT * FROM ucastnici WHERE ((etapa IN (SELECT id FROM etapy WHERE akce=".$id_akce.")) AND stravaID=".$_SESSION['stravaID'].")";
                    $resulttest = mysqli_query($conn, $sqltest);
                    $pocetTrailtour = mysqli_num_rows($resulttest);
                  }
?>

            <!-- Page Heading -->
            <div class="page-heading">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <h1><?php echo $row["nazev"]."<br>\n"; ?></h1>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tables -->
            <section class="tables">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <div class="section-heading">
                      <h2>Etapy</h2>
                      <p>Zde je možno zadávat svoje časy ručně a nebo na základě ručně zadaných časů predikovat časy na dalších etapách. Pro predikci je nutné mít zadaný alespon jeden čas.
                        <ul>
                          <li><strong style="color: red;">Červeně</strong> - ručně zadaný čas.</li>
                          <li><strong style="color: orange;">Oranžově</strong> - predikovaný čas.</li>
                        </ul>

                      </p>
                    </div>
                    <div class="default-table">
                      <table>
                        <thead>
                          <tr>
                            <th>Poř.</th>
                            <th>Etapa</th>
                            <th>Nejlepší čas</th>
                            <th>Můj čas</th>
                            <!--<th>Trai AP</th>   Trailtour + weighted total predicted  -->
                            <th>Trailour</th>  <!-- Trailtour + weighted  -->
                           <!-- <th>Trai TP</th>   Trailtour  + procentatotal predicted  -->
                           <!--  <th>Trail %</th>   Trailtour + procenta  -->
                           <th>---</th>
                          </tr>
                        </thead>
                        <tbody>

<?php
                    $sql1 = "SELECT * FROM etapy WHERE akce=".$id_akce." ORDER BY poradi";
                    $result1 = mysqli_query($conn, $sql1);
                      if (mysqli_num_rows($result1) > 0) { 
                            while($prvek = mysqli_fetch_assoc($result1)) {
                        		  $sql3 = "SELECT * FROM ucastnici WHERE (etapa=".$prvek["id"]." AND poradi=1 AND pohlavi = '".$pohlavi."') ORDER BY cas ASC";
                        		  $result3 = mysqli_query($conn, $sql3);
                              $nejCas = "--:--:--";
                              $ts = 0;
                              if (mysqli_num_rows($result3) > 0) {
                                  $nejlepsi = mysqli_fetch_assoc($result3);
                                  $nejCas = $nejlepsi["cas"];
                                  $ts = (timeToSeconds($nejCas)/(2.5-($nejlepsi["body"]/100)));
                              }
                        		  // můj STRAVA čas
                              $stravacas = "--:--:--";
                       		    if ($_SESSION['stravaID'] > 0) {
                                  $sql4 = "SELECT * FROM ucastnici WHERE (etapa=".$prvek["id"]." AND stravaID=".$_SESSION['stravaID'].")";
                            		  $result4 = mysqli_query($conn, $sql4);
                                  if (mysqli_num_rows($result4) > 0) {
                                      $scas = mysqli_fetch_assoc($result4);
                                      $stravacas = $scas["cas"];
                                  } else {
                                     if ($pocetTrailtour > 3) {
                                        $vysledekTrailtour = predikujVysledekStrava($stravaID, $prvek["id"],$id_akce, $k, "A"); // k - počet sousedů
                                  			$casTrailtour = $vysledekTrailtour;
                                  			if (strlen($casTrailtour) == 5) {
                                  				$casTrailtour = $casTrailtour .":00";
                                  			}
                                        $stravacas = "<span style=\"color: orange;\">".$casTrailtour."</span>"; 
                                     }
                                  }
                              }    
                        		  // můj STRAVA čas - total prediction
/*                              $stravacasAP = "--:--:--";
                       		    if ($_SESSION['stravaID'] > 0) {
                           		  if ($pocetTrailtour > 3) {
                                        $vysledekTrailtourAP = predikujVysledekStrava($stravaID, $prvek["id"],$id_akce, $k, "A"); // k - počet sousedů
                                  			$casTrailtourAP = $vysledekTrailtourAP;
                                  			if (strlen($casTrailtourAP) == 5) {
                                  				$casTrailtourAP = $casTrailtourAP .":00";
                                  			}
                                        $stravacasAP = "<span style=\"color: orange;\">".$casTrailtourAP."</span>"; 
                                     }
                              }    
                        		  // můj STRAVA čas Procenta
                              $stravacasP = "--:--:--";
                       		    if ($_SESSION['stravaID'] > 0) {
                                  $sql4 = "SELECT * FROM ucastnici WHERE (etapa=".$prvek["id"]." AND stravaID=".$_SESSION['stravaID'].")";
                            		  $result4 = mysqli_query($conn, $sql4);
                                  if (mysqli_num_rows($result4) > 0) {
                                      $scas = mysqli_fetch_assoc($result4);
                                      $stravacasP = $scas["cas"];
                                  } else {
                                     if ($pocetTrailtour > 3) {
                                        $vysledekTrailtourP = predikujVysledekStrava($stravaID, $prvek["id"],$id_akce, $k, "P"); // k - počet sousedů
                                  			$casTrailtourP = $vysledekTrailtourP;
                                  			if (strlen($casTrailtourP) == 5) {
                                  				$casTrailtourP = $casTrailtourP .":00";
                                  			}
                                        $stravacasP = "<span style=\"color: orange;\">".$casTrailtourP."</span>"; 
                                     }
                                  }
                              }    
                        		  // můj STRAVA čas Procenta - total prediction
                              $stravacasTP = "--:--:--";
                       		    if ($_SESSION['stravaID'] > 0) {
                           		  if ($pocetTrailtour > 3) {
                                        $vysledekTrailtourTP = predikujVysledekStrava($stravaID, $prvek["id"],$id_akce, $k, "P"); // k - počet sousedů
                                  			$casTrailtourTP = $vysledekTrailtourTP;
                                  			if (strlen($casTrailtourTP) == 5) {
                                  				$casTrailtourTP = $casTrailtourTP .":00";
                                  			}
                                        $stravacasTP = "<span style=\"color: orange;\">".$casTrailtourTP."</span>"; 
                                     }
                              }    
*/                              // test zda existuji nějaké mé výsledky
                              $existujiVysledky = false;
                              $sqlTest = "SELECT * FROM moje_data WHERE (id_jmeno=".$id_user." AND id_akce=".$id_akce." AND imputace='N')";
                        		  $resultTest = mysqli_query($conn, $sqlTest);
              							  if (mysqli_num_rows($resultTest) > 0) {
                                  $existujiVysledky = true;
                              }
                              // konec testu
                              $sql2 = "SELECT * FROM moje_data WHERE (id_jmeno=".$id_user." AND id_etapa=".$prvek["id"].")";
                        		  $result2 = mysqli_query($conn, $sql2);
              							  if (mysqli_num_rows($result2) > 0) {
                              		$mojeData = mysqli_fetch_assoc($result2);
                              		echo "<tr>\n";
                              		echo "<form method=\"post\">";
                              		echo "  <td>".$prvek["poradi"]."</td>\n";
                              		echo "  <td>".$prvek["etapa"]."</td>\n";
                              		echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nejCas."</td>\n";
                                  if ($mojeData["imputace"] == "N") {
                                  		echo "  <td><input type=\"time\" style=\"color: red;\" name=\"cas\" step=\"1\" value=\"".$mojeData["cas"]."\"></td>\n";
                                  } else {
                                  		echo "  <td><input type=\"time\" style=\"color: orange;\" name=\"cas\" step=\"1\" value=\"".$mojeData["cas"]."\"></td>\n";
                                  }
                              		//echo "  <td><button name=\"xxxx\" value=\"".$stravacas."\">".$stravacas."</button></td>\n";
                              		//echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$stravacasAP."</td>\n";  //total
                              		echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$stravacas."</td>\n";
                              		//echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$stravacasTP."</td>\n";  //procenta total
                               		//echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$stravacasP."</td>\n";   //procenta
                                  echo "  <td>";
                              		echo "    <input type=\"hidden\" name=\"id_zaznam\" value=".$mojeData["id"].">\n";
                              		echo "    <input type=\"hidden\" name=\"id_akce\" value=".$id_akce.">\n";
                              		echo "    <input type=\"hidden\" name=\"id_etapa\" value=".$prvek["id"].">\n";
                              		echo "    <input type=\"hidden\" name=\"id_user\" value=".$id_user.">\n";
                              		echo "    <input type=\"hidden\" name=\"ts\" value=\"".$ts."\">\n";
                              		echo "    <input type=\"submit\" name=\"akce\" value=\"Uložit\">\n";
                              		echo "    <input type=\"submit\" name=\"akce\" value=\"Smazat\">\n";
                              		echo "  </td>\n";
                              		echo "  </form>\n";
                              		echo "  </tr>\n";
            						      } else {
                                  echo "<tr>\n";
                              		echo "<form method=\"post\">";
                              		echo "  <td>".$prvek["poradi"]."</td>\n";
                              		echo "  <td>".$prvek["etapa"]."</td>\n";
                              		echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$nejCas."</td>\n";
                              		echo "  <td><input type=\"time\" name=\"cas\" step=\"1\" value=\"00:00:00\"></td>\n";
                              		//echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$stravacasAP."</td>\n";  //total
                              		echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$stravacas."</td>\n";
                              		//echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$stravacasTP."</td>\n";  //procenta total
                              		//echo "  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$stravacasP."</td>\n";  //procenta
                              		echo "  <td>";
                              		echo "    <input type=\"hidden\" name=\"id_zaznam\" value=0>\n";
                              		echo "    <input type=\"hidden\" name=\"id_akce\" value=".$id_akce.">\n";
                              		echo "    <input type=\"hidden\" name=\"id_etapa\" value=".$prvek["id"].">\n";
                              		echo "    <input type=\"hidden\" name=\"id_user\" value=".$id_user.">\n";
                              		echo "    <input type=\"hidden\" name=\"ts\" value=\"".$ts."\">\n";
                              		echo "    <input type=\"submit\" name=\"akce\" value=\"Uložit\">\n";
                                  if ($existujiVysledky == true) {
                              		    echo "    <input type=\"submit\" name=\"akce\" value=\"Predikce\">\n";
                                  } 
                              		echo "  </td>\n";
                              		echo "  </form>\n";
                              		echo "  </tr>\n";
                              }
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
<?php
?>        