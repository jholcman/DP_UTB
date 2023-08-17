<?php

echo "<!-- administrace -->\n";
echo <<<HTML

            <!-- Page Heading -->
            <div class="page-heading">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <h1>Administrace závodů Trailtour</h1>
                    <p>
                    Zde je možno aktualizovat obsah databáze závodů. Data se načítají přímo ze aplikace TRAILTOUR z jejich adresy www.trailtour.cz a nebo ze souboru ve formátu XML.
                    </p>
                  </div>
                </div>
              </div>
            </div>

                        <!-- Tables -->
            <section class="tables">
HTML;
//////////////////////////////////////////////
                 //pole existujících adres Trailtour - start
                  $pole_adres = array(
                      "TRAILTOUR 2023 CZ" => "http://www.trailtour.cz/2023/etapy/etapy-CZ/", 
                      "TRAILTOUR 2023 SK" => "http://www.trailtour.cz/2023/etapy/etapy-SK/",
                      "TRAILTOUR 2022 CZ" => "http://www.trailtour.cz/2022/etapy/etapy-CZ/", 
                      "TRAILTOUR 2022 SK" => "http://www.trailtour.cz/2022/etapy/etapy-SK/",
                      "TRAILTOUR 2021 CZ" => "http://www.trailtour.cz/2021/etapy/etapy-CZ/",
                      "TRAILTOUR 2021 SK" => "http://www.trailtour.cz/2021/etapy/etapy-SK/",
                      "TRAILTOUR 2020 CZ" => "http://www.trailtour.cz/2020/etapy/etapy-CZ/",
                      "TRAILTOUR 2020 SK" => "http://www.trailtour.cz/2020/etapy/etapy-SK/"
                  );
                 //pole existujících adres Trailtour - stop


                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }
                foreach ($pole_adres as $key => $value) {

                    $sql = "SELECT * FROM `akce` WHERE nazev='".$key."'";
                    $result = mysqli_query($conn, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        echo " <h2>".$row["nazev"]."</h2><br>\n";       // hlavní akce

                        echo " <form id=\"form".$row["id"]."\" action=\"ubratData.php\" method=\"post\">";
                        echo " <input type='hidden' name='id' value='".$row["id"]."'>";
                        echo " <button type=\"submit\" name=\"pole\" value=\"smazat\">Smazat akci č. ".$row["id"]."</button><br><br>\n";       // tlacitko
                        echo "</form>";
                        echo " <form id=\"form".$row["id"]."\" action=\"aktualizovatData.php\" method=\"post\">";
                        echo " <input type='hidden' name='id' value='".$row["id"]."'>";
                        echo " <button type=\"submit\" name=\"pole\" value=\"update\">Aktualizovat záznamy</button><br><br>\n";       // tlacitko
                        echo "</form>";
                        
                        $sql1 = "SELECT * FROM etapy WHERE akce=".$row["id"]." ORDER BY poradi";
                        $result1 = mysqli_query($conn, $sql1);
                        
                        echo "  <section class=\"simple-post\">\n";
                        echo "    <div class=\"container-fluid\">\n";
                        echo "     <div class=\"row\">\n";
                        if (mysqli_num_rows($result1) > 0) {
    
                          while($row1 = mysqli_fetch_assoc($result1)) {
                            /////////////
                                $sqlu = "SELECT pohlavi,COUNT(jmeno) AS pocet FROM ucastnici WHERE (etapa=".$row1["id"]." and (pohlavi='M' or pohlavi='Z')) group by pohlavi";
                                $resultu = mysqli_query($conn, $sqlu);
                                $zavodnici = "Žádní závodníci neabsoluvovali tuto etapu";
                                $muzu = 0;
                                $zeny = 0;

                                if (mysqli_num_rows($resultu) > 0) {
                                    $muzu = 0;
                                    $zeny = 0;
                                    while($rowu = mysqli_fetch_assoc($resultu)) {
                                          $zavodnici = "ANO";
                                          if ($rowu["pohlavi"] == "M") {
                                              $muzu = $rowu["pocet"];
                                          } else {
                                              $zeny = $rowu["pocet"];
                                          }
                                    }
                                }    
                            
                            /////////////
                            echo "      <div class=\"col-lg-4\">\n";
                            echo " ".$row1["poradi"].". ".$row1["etapa"]."<br>&nbsp;&nbsp;&nbsp;&nbsp;(".$muzu." mužů a ".$zeny." žen)<br>\n";   //tratě
                            echo "      </div>\n";
                          }
                        } else {
                            echo "      <div class=\"col-lg-4\">\n";
                            echo " Žádné etapy pro tuto akci nejsou vloženy<br>\n";   //tratě
                            echo "      </div>\n";
                        }
                        echo "      </div>\n";
                        echo "    </div>\n";
                        echo "    </section>\n";
                        echo "<br>\n";
                    } else {
                        echo " <h2>".$key."</h2><br>\n";       // hlavní akce
                        if (@file_get_contents($value)) {
                            echo " <form id=\"form\" action=\"pridatData.php\" method=\"post\">";
                            echo " <input type='hidden' name='nazev' value='".$key."'>";
                            echo " <input type='hidden' name='url' value='".$value."'>";
                            echo " <button type=\"submit\" name=\"pole\" value=\"pridat\">Přidat akci do databáze</button><br><br>\n";       // tlacitko
                            echo "</form>";
                            echo " ".$value."\n";       // url akce
                            echo "  <section class=\"simple-post\">\n";
                            echo "    <div class=\"container-fluid\">\n";
                            echo "     <div class=\"row\">\n";
                            echo "      <div class=\"col-lg-4\">\n";
                            echo "Počet etap: ". etapyUcastnici($value)."<br>\n";   //tratě
                            echo "      </div>\n";
                            echo "      </div>\n";
                            echo "    </div>\n";
                            echo "    </section>\n";
                        } else {
                            echo " ".$value."<br>\n";       // url akce
                            echo " Akci nelze přidat, protože na zadané adrese data nejsou k dispozici.<br><br>\n";       // tlacitko
                            echo "  <section class=\"simple-post\">\n";
                            echo "    <div class=\"container-fluid\">\n";
                            echo "     <div class=\"row\">\n";
                            echo "      <div class=\"col-lg-4\">\n";
                            echo "      </div>\n";
                            echo "      </div>\n";
                            echo "    </div>\n";
                            echo "    </section>\n";
                        }
                        echo "<br>\n";
                    }
              }
              mysqli_close($conn);

//////////////////////////////////////////////
echo <<<HTML
            </section>
HTML;            
?>