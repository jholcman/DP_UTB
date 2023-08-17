<?php
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
?>

        			<script src="https://code.highcharts.com/highcharts.js"></script>
        			<script src="https://code.highcharts.com/modules/series-label.js"></script>
        			<script src="https://code.highcharts.com/modules/exporting.js"></script>
        			<script src="https://code.highcharts.com/modules/export-data.js"></script>
        			<script src="https://code.highcharts.com/modules/accessibility.js"></script>
        
            <!-- Page Heading -->
            <div class="page-heading">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <h1>Statistiky závodů</h1>
<?php                if (!empty($_SESSION['prihlasen']) && $_SESSION['prihlasen']=="A" && $_SESSION['isadmin']=="0") {   ?>
                        <p>Přehled výsledků jednotlivých akcí, rozdělených dle etap. Na ose X jsou časy dosažené jednotlivými závodníky (vlevo nejlepší, vpravo nejhorší). Osa Y označuje jednotlivé etapy závodů. 
                        <ul>
                          <li><strong style="color: rgb(83, 255, 83);">Zeleně</strong> - výsledky oficiálních účastníků závodů.</li> 
                          <li><strong style="color: red;">Červeně</strong> - vaše ručně zadané výsledky.</li>
                          <li><strong style="color: orange;">Oranžově</strong> - predikované výsledky na základě vašich dosavadních výsledků.</li>
                          <li><strong style="color: blue;">Modře</strong> - výsledky importované dle STRAVA ID, udané v registraci uživatele.</li>
                        </ul>
                        </p>
<?php               } else {     ?>
                    <p>Přehled výsledků jednotlivých akcí, rozdělených dle etap. Po registraci a zadání svých výsledků, jsou grafy zobrazeny jednobarevně a barevně jsou odlišeny Vaše výsledky. Tím je možno porovnat vlastní umístění vzhledem ke všem závodníkům, kteří již trať absolvovali.</p>
<?php               }        ?>
                    <br>
<?php
      $sql = "SELECT * FROM `akce` ORDER BY poradi DESC";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {            //start akce
          echo "                      <h2>".$row["nazev"]."</h2>\n";
          grafPoradiZavodniku($row["id"],$conn);
 
        }  // konec akce
      } else {
        echo "                      <h2>Nejsou zadány žádné akce<h2>\n";
      }
      mysqli_close($conn);   
?>


                  </div>
                </div>
              </div>
            </div>
