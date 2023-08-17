<?php

                $id_trate = htmlspecialchars($_GET["id_trate"]);
                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $dbname);
                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }
                $sql = "SELECT * FROM etapy WHERE id=".$id_trate;
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    $sql_akce = "SELECT * FROM akce WHERE id=".$row["akce"];
                    $result_akce = mysqli_query($conn, $sql_akce);
                    $row_akce = mysqli_fetch_assoc($result_akce);

?>

            <!-- Page Heading -->
            <div class="page-heading">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <h1><?php echo $row_akce["nazev"]."<br>\n"; ?></h1>
                    <h2>Etapa 
                    
<?php 

    echo $row["poradi"].".: ".$row["etapa"];

?></h2><br>
                    <p>
<?php
                      echo "                   Autor tratě: ".$row["autor"]."<br>\n
                                               Délka tratě: ".number_format($row["delka"],0, ',', ' ')." m<br>
                                               Převýšení: ".number_format($row["prevyseni"],0, ',', ' ')." m<br>";
?>

                    </p>


                  </div>
                </div>
              </div>
            </div>
            <!-- Graf start -->

			<script src="https://code.highcharts.com/highcharts.js"></script>
			<script src="https://code.highcharts.com/modules/series-label.js"></script>
			<script src="https://code.highcharts.com/modules/exporting.js"></script>
			<script src="https://code.highcharts.com/modules/export-data.js"></script>
			<script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
			.highcharts-figure {
			    min-width: 310px;
			    max-width: 800px;
			    margin: 1em auto;
			}
    </style>

<figure class="highcharts-figure">
    <div id="container"></div>
</figure>
			<script defer type="text/javascript">
			Highcharts.chart('container', {
				chart: {
					type: 'areaspline',
					scrollablePlotArea: {
						minWidth: 200,
						scrollPositionX: 1
					}
				},
				title: {
					text: 'Profil trati',
					align: 'left'
				},
				subtitle: {
					text: ' ',
					align: 'left'
				},
				xAxis: {
					type: '',
					title: {
						text: 'Délka trati [km]'
					},
					
          labels: {
						enabled: true,
            overflow: 'justify'
					}
				},
        
				yAxis: {
					title: {
						text: 'Nadmořská výška [m]'
					},
					minorGridLineWidth: 0,
					gridLineWidth: 1,
					alternateGridColor: null
				},
        tooltip: {
                shared: true,
                useHTML: true,
                headerFormat: '<table>',
                pointFormat: '<tr><td style="color: {series.color}">{series.name}: </td>' +
                    '<td style="text-align: right"><b>{point.y} m n. m.</b></td></tr>',
                footerFormat: '</table>',
                valueDecimals: 0
            },
				plotOptions: {
					spline: {
						lineWidth: 4,
						states: {
							hover: {
								lineWidth: 5
							}
						},
						marker: {
							enabled: false
						},
						pointInterval: 1, // one hour
						pointStart: 0
					}
				},
				series: [{
					name: 'Nadmořská výška',
					data: [
<?php
					//  https://www.highcharts.com/blog/download/

					$carka = "";
          $path = $row["gps"];
					$ob = simplexml_load_file($path);
					$json  = json_encode($ob);
					$configData = json_decode($json, true);
					$po = count($configData["trk"]["trkseg"]["trkpt"]);
          $pct = 0;
          foreach($configData["trk"]["trkseg"]["trkpt"] as $pole_hodnot){
						if (isset($pole_hodnot["ele"])) {
                if  (($pole_hodnot["ele"] != "") and ($pole_hodnot["ele"] != 0)) {
    						    echo $carka." ";
    						    $carka = ",";
                    $procento = ($pct/$po)*($row["delka"]/1000);
                    $pct = $pct + 1;
                    echo "[".$procento.", ".$pole_hodnot["ele"]."]";
                }
            }
					}
?>            ]

				}],
				navigation: {
					menuItemStyle: {
						fontSize: '10px'
					}
				}
			});

			 </script>
            <!-- Graf end -->
            <!-- map start -->
          <?php
          
          if ($row["mapa"] != "") {
          
              echo "<script src=\"https://api.mapy.cz/loader.js\"></script>";
              echo "<script>Loader.load();</script>";
              echo "<center><div id=\"mapa\" style=\"width:750px; height:300px\"></div></center>\n";
              echo "  <script>\n";
              echo "    ".htmlspecialchars_decode($row["mapa"], ENT_QUOTES)."\n";
              echo "  </script>\n";
          }
          
          ?>
            <!-- map end -->
            
            <!-- Tables -->
            <section class="tables">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <div class="section-heading">
                      <h2>Muži</h2>
                    </div>
                    <div class="default-table">
                      <table>
                        <thead>
                          <tr>
                            <th>Pořadí</th>
                            <th>Jméno</th>
                            <th>Klub</th>
                            <th>Čas</th>
                            <th style="text-align: right;">Body </th>
                          </tr>
                        </thead>
                        <tbody>

<?php
                      $sql1 = "SELECT * FROM ucastnici WHERE (etapa=".$row["id"]." and pohlavi='M')";
                      $result1 = mysqli_query($conn, $sql1);
                      if (mysqli_num_rows($result1) > 0) { 
                            $mojeData = false;
                            if ($prihlasen == "A") {
                                $sql_moje = "SELECT * FROM moje_data WHERE (id_etapa=".$row["id"]." and pohlavi='M')";
                                $result_moje = mysqli_query($conn, $sql_moje);
                                if (mysqli_num_rows($result_moje) > 0) {
                                    $mujCas = mysqli_fetch_assoc($result_moje);
                                    $mojeData = true;
                                }
                            }    
                            while($prvek = mysqli_fetch_assoc($result1)) {
                              if ($mojeData == true) {
                                if ($prvek["cas"] >= $mujCas["cas"]) {
                                    echo "<tr style=\"background-color:#FF0000;color: white;\">\n";
                                    echo "  <td> </td>\n";
                                    echo "  <td>".$_SESSION['userJmeno']."</td>\n";
                                    echo "  <td> </td>\n";
                                    echo "  <td>".$mujCas["cas"]."</td>\n";
                                    echo "  <td style=\"text-align: right;\">".number_format($mujCas["body"],2, ',', ' ')." </td>\n";
                                    echo "  </tr>\n";
                                    $mojeData = false;
                                }
                              }
                              echo "<tr>\n";
                              echo "  <td>".$prvek["poradi"]."</td>\n";
                              //echo "  <td>".utf8_decode($prvek["jmeno"])."</td>\n";
                              echo "  <td>".jmeno($prvek["stravaID"],$prvek["pohlavi"])."</td>\n";
                              echo "  <td>".$prvek["klub"]."</td>\n";
                              echo "  <td>".$prvek["cas"]."</td>\n";
                              echo "  <td style=\"text-align: right;\">".number_format($prvek["body"],2, ',', ' ')." </td>\n";
                              echo "  </tr>\n";  
                            }
                      }   
?>
                        </tbody>
                      </table>
                    </div>

                    <div class="section-heading">
                      <br><br>
                      <h2>Ženy</h2>
                    </div>
                    <div class="default-table">
                      <table>
                        <thead>
                          <tr>
                            <th>Pořadí</th>
                            <th>Jméno</th>
                            <th>Klub</th>
                            <th>Čas</th>
                            <th style="text-align: right;">Body </th>
                          </tr>
                        </thead>
                        <tbody>

<?php
                      $sql1 = "SELECT * FROM ucastnici WHERE (etapa=".$row["id"]." and pohlavi='Z')";
                      $result1 = mysqli_query($conn, $sql1);
                      if (mysqli_num_rows($result1) > 0) { 
                            while($prvek = mysqli_fetch_assoc($result1)) {
                              echo "<tr>\n";
                              echo "  <td>".$prvek["poradi"]."</td>\n";
                              //echo "  <td>".utf8_decode($prvek["jmeno"])."</td>\n";
                              echo "  <td>".jmeno($prvek["stravaID"],$prvek["pohlavi"])."</td>\n";
                              echo "  <td>".$prvek["klub"]."</td>\n";
                              echo "  <td>".$prvek["cas"]."</td>\n";
                              echo "  <td style=\"text-align: right;\">".number_format($prvek["body"],2, ',', ' ')." </td>\n";
                              echo "  </tr>\n";
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
                      $sql1 = "SELECT * FROM ucastnici WHERE (etapa=".$row["id"]." and pohlavi='K')";
                      $result1 = mysqli_query($conn, $sql1);
                      if (mysqli_num_rows($result1) > 0) { 
                            while($prvek = mysqli_fetch_assoc($result1)) {
                              echo "<tr>\n";
                              echo "  <td>".$prvek["poradi"]."</td>\n";
                              echo "  <td>".$prvek["klub"]."</td>\n";
                              echo "  <td style=\"text-align: right;\">".number_format($prvek["body"],2, ',', ' ')." </td>\n";
                              echo "  </tr>\n";
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

}

?>