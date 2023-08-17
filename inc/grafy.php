<?php
function grafPoradiZavodniku($id_akce,$conn) {



          $sqletapa = "SELECT * FROM etapy WHERE akce=".$id_akce." ORDER BY poradi";
          $resultetapa = mysqli_query($conn, $sqletapa);
          if (mysqli_num_rows($resultetapa) > 0) {
            $kategorie = "";
            $carka="";
            $kategorii=mysqli_num_rows($resultetapa)-1;
            while($rowetapa = mysqli_fetch_assoc($resultetapa)) {
                $sqletapatest = "SELECT id FROM ucastnici WHERE etapa=".$rowetapa['id'];
                $resultetapatest = mysqli_query($conn, $sqletapatest);
                //if (mysqli_num_rows($resultetapatest) > 0) {
                   $kategorie = $kategorie.$carka."'".$rowetapa['etapa']."'";
                   $carka=", ";
               //}
            }
          }


?>
                      <br>
                      <!-- Graf start -->
                
                    <figure class="highcharts-figure-<?php echo $id_akce;?>" style="min-width: 310px;max-width: 1200px;margin: 1em auto;">
                        <div id="a-<?php echo $id_akce;?>"></div>
                    </figure>
                    
                    <script defer type="text/javascript">
                    const colors<?php echo $id_akce;?> = Highcharts.getOptions().colors.map(color =>
                      Highcharts.color(color).setOpacity(0.5).get()
                    );
                    
                    Highcharts.chart('a-<?php echo $id_akce;?>', {
                      chart: {
                        type: 'scatter',
                        height: <?php echo ($kategorii * 25);?>
                      },
                    
                      colors<?php echo $id_akce;?>,
                    
                      title: { text: '' },
                    
                      yAxis: [{
                        title: { text: 'Etapa' },
                        categories: [<?php echo $kategorie;?>],
                        labels: { step: 1 }
                      }],
                    
                      xAxis: {
                        title: { text: 'Čas závodníka' },
                        type: 'datetime',
                        labels: { format: '{value:%H:%M:%S}' }
                      },
                    
                      plotOptions: {
                        scatter: { showInLegend: false, jitter: { x: 0.0, y: 0.2 } },
                        series: {  states: { inactive: { enabled: true, opacity: 0.4 } } }
                      }, 
                       series: [

<?php
          $sqletapa = "SELECT * FROM etapy WHERE akce=".$id_akce." ORDER BY poradi";
          $resultetapa = mysqli_query($conn, $sqletapa);
          if (mysqli_num_rows($resultetapa) > 0) {
            $poradi=0;
            $carka="";
            while($rowetapa = mysqli_fetch_assoc($resultetapa)) {
               echo $carka."{name: '".$rowetapa['etapa']."', ";
               if (!empty($_SESSION['prihlasen']) && $_SESSION['prihlasen']=="A"  && $_SESSION['isadmin']=="0") {
                 echo "color: 'rgba(83, 255, 83, .9)',";
               }
   			       echo "	 marker: { enabled: true, symbol: 'circle', radius: 2 },";
               echo "	 tooltip: { pointFormat: 'Čas závodníka: {point.x:%k:%M:%S}'},";

               echo "data: [";
               $sqlcas = "SELECT * FROM ucastnici WHERE (etapa=".$rowetapa["id"]." AND pohlavi<>'K')";
               $resultcas = mysqli_query($conn, $sqlcas);
               if (mysqli_num_rows($resultcas) > 0) {
                 $cascarka="";
                   while($rowcas = mysqli_fetch_assoc($resultcas)) {
                     if (timeToSeconds($rowcas["cas"]) != 0) {
                       echo $cascarka."{x: ".timeToSeconds($rowcas["cas"])."000, y: ".$poradi."}";
                       $cascarka=", ";
                     }
                  }
               } else {
               
               }   
               // zadané nebo imputované hodnoty
               if (!empty($_SESSION['prihlasen']) && $_SESSION['prihlasen']=="A"  && $_SESSION['isadmin']=="0" ) {
                   $sqlmojeetapa = "SELECT * FROM moje_data WHERE (id_etapa=".$rowetapa["id"]." AND id_jmeno=".$_SESSION['userID'].")";
                   $resultmojeetapa = mysqli_query($conn, $sqlmojeetapa);
                   if (mysqli_num_rows($resultmojeetapa) > 0) {
                     $rowmujcas = mysqli_fetch_assoc($resultmojeetapa);
                     if ($rowmujcas["imputace"] == "N") {
                        $barva = "red";
                     } else {
                        $barva = "orange";
                     }   
                     echo ", {x: ".timeToSeconds($rowmujcas["cas"])."000, y: ".$poradi.", color: '".$barva."', marker:{radius: 4}}";
                   }
               }    
               // importované hodnoty dle STRAVA ID
               if (!empty($_SESSION['prihlasen']) && $_SESSION['prihlasen']=="A"  && $_SESSION['isadmin']=="0" ) {
                   $sqlsetapa = "SELECT * FROM ucastnici WHERE (etapa=".$rowetapa["id"]." AND stravaID=".$_SESSION['stravaID']." AND pohlavi='".$_SESSION['pohlavi']."')";
                   $resultsetapa = mysqli_query($conn, $sqlsetapa);
                   if (mysqli_num_rows($resultsetapa) > 0) {
                     $rowscas = mysqli_fetch_assoc($resultsetapa);
                     $barva = "blue";
                     echo ", {x: ".timeToSeconds($rowscas["cas"])."000, y: ".$poradi.", color: '".$barva."', marker:{radius: 4}}";
                   }
               }    


               //////////////////////////////////////
               echo "]}\n";
               $kategorie = $kategorie.$carka."'".$rowetapa['etapa']."'";
               $carka=", ";
               $poradi=$poradi + 1;
            }
          }
?>
                   ]
                   });
                    </script>
                    <!-- Graf end   --> 

<?php
}

?>