<?php

                $id_akce = (int) htmlspecialchars($_GET["id_akce"]);
                //$rok = htmlspecialchars($_GET["rok"]);
                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM akce WHERE id=".$id_akce;
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                echo " <h1>Etapy závodu ". $row["nazev"]."</h1>\n";       // hlavní akce

                if ($row["url_mapa"] != "") {
                    echo "<iframe src=\"".$row["url_mapa"]."\" style=\"width:100%; height:400px; border:0px solid white;\"></iframe>";
                }
                echo " <section class=\"simple-post\">\n";
                echo " <div class=\"container-fluid\">\n";
                echo "   <div class=\"row\">\n";

                $sql = "SELECT * FROM etapy WHERE akce=".$id_akce." ORDER BY poradi";
                $result = mysqli_query($conn, $sql);

                $muzu = 0;
                $zeny = 0;
                if (mysqli_num_rows($result) > 0) {
                  //echo "               <ul>\n";
                  while($row = mysqli_fetch_assoc($result)) {
                      $muzu = 0;
                      $zeny = 0;
                      $sql1 = "SELECT pohlavi,COUNT(jmeno) AS pocet FROM ucastnici WHERE (etapa=".$row["id"]." and (pohlavi='M' or pohlavi='Z')) group by pohlavi";
                      $result1 = mysqli_query($conn, $sql1);
                      $zavodnici = "Žádní závodníci neabsoluvovali tuto etapu";
                      if (mysqli_num_rows($result1) > 0) {
                          while($row1 = mysqli_fetch_assoc($result1)) {
                                $zavodnici = "ANO";
                                if ($row1["pohlavi"] == "M") {
                                    $muzu = $row1["pocet"];
                                } else {
                                    $zeny = $row1["pocet"];
                                }
                          }
                      }    
                      
                      
                      echo "                <div class=\"col-lg-4\">\n";
						          echo "                  <h3><a href=\"?id_trate=".$row["id"]."&akce=trat\">".$row["poradi"].". ".$row["etapa"]."</a></h3>\n";
                      echo "                   <p>Autor tratě: ".$row["autor"]."<br>\n
                                                  Délka tratě: ".number_format($row["delka"],0, ',', ' ')." m<br>
                                                  Převýšení: ".number_format($row["prevyseni"],0, ',', ' ')." m<br>
                                                  Počet účastníků: ".$muzu." mužů a ".$zeny." žen</p>\n";
                      echo "                   <br>\n";
                      echo "                </div>\n";
                  }
                }
                mysqli_close($conn);
              echo "   </div>";
              echo " </div>";
              echo " </section>";
?>