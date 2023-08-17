<?php

echo "Přidávám data <br>";

include "inc/MySQL.inc";
include "inc/simple_html_dom.php";
include "inc/function.php";

//////////////////////
$id_akce = htmlspecialchars($_POST['id'], ENT_QUOTES);
$etapy = [];
$subpole = [];
$ucastnici = [];
$last_id_akce = 0;
$last_id_trate = 0;

// Check connection

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
$conn->set_charset("utf8");

// check for existing record
$sql = "SELECT * FROM akce WHERE id=".$id_akce;
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $url_akce = $row['url_akce'];
}      

$html = file_get_html($url_akce);
if (!$html) {
  echo "Neexistuje";
} else {
  echo $html->find('title',0)->plaintext. "<br><br>\n";
  $pole = $html->find('div.etapa-item');
  $url_mapa = $html->find('div.embed-responsive.embed-responsive-16by9')[0]->find('iframe')[0]->src;
  $last_id_akce = $id_akce;
  
  foreach($pole as $a) {
    /// ----------------------------------etapa start
    $meters = explode(" ", removeSpaces($a->find('p')[0]->plaintext));
    $etapy[0] = (int) explode("#", removeSpaces($a->find('h2')[0]->find('span')[0]->plaintext))[1];                  //poradi
    $etapy[1] = 0;
    $etapy[2] = $a->find('a')[0]->href;                         //url
    $etapy[3] = removeSpaces($a->find('h3')[0]->plaintext);     //etapa
    $etapy[4] = removeSpaces(str_replace($a->find('h2')[0]->find('span')[0]->plaintext, '', $a->find('h2')[0]->plaintext));
    $etapy[5] = (int) $meters[0];
    $etapy[6] = (int) $meters[2];

    $url_strava = file_get_html($a->find('a')[0]->href);
    $etapy[1] = @intval(end(explode("/", $url_strava->find('a.btn.btn-warning.btn-sm')[0]->href)));        //stravaID
    $etapy[7] = $url_strava->find('a.btn-success.btn-sm')[0]->href;        //GPS_soubor
    $etapy[8] = htmlspecialchars($url_strava->find('section.etapa-uvod')[0]->find('div.col-md-8.col-md-offset-2.col-sm-10.col-sm-offset-1.col-xs-12')[0]->find('script')[2]->innertext, ENT_QUOTES);                                                         //mapa_url
    unset($url_strava);
    $sql_test =  "SELECT * FROM etapy WHERE stravaID=".$etapy[1]." AND akce=".$last_id_akce. " LIMIT 1";
    $result_test = mysqli_query($conn, $sql_test);
    if (mysqli_num_rows($result_test) > 0) {
       $row_test = mysqli_fetch_assoc($result_test);
       $last_id_trate = $row_test['id'];
    } else {
        $sql_etapy =  "INSERT INTO etapy (poradi, stravaID, url, etapa, autor, delka, prevyseni, akce, gps, mapa) VALUES "
              ."(".$etapy[0]." ,".$etapy[1].", '".$etapy[2]."', '".$etapy[3]."', '".$etapy[4]."', ".$etapy[5].", ".$etapy[6].", ".$last_id_akce.", '".$etapy[7]."', '".$etapy[8]."')";
        if (mysqli_query($conn, $sql_etapy)) {
          echo "New record created successfully - ".$etapy[3]."<br>";
        } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
        }
        $last_id_trate = mysqli_insert_id($conn);
    }
    unset($etapy);
    /// ----------------------------------etapa end
   
    /// ----------------------------------etapa participants in the run start
    $participian = file_get_html($a->find('a')[0]->href);
    if (!$participian) {
      echo "Neexistují účastníci závodu";
    }else {
      $pole = $participian->find('table.table-striped');
      /// ----------------------------------ucastnik start
        if (count($pole) > 0) { 
          unset($subpole);
          foreach($pole[0]->find('tr.top-10-w, tr.vsichni-w') as $b) {
            $c = $b->find('td'); 
            $subpole[0] = (int) $c[0]->plaintext;        //poradi
            $subpole[1] = utf8_encode(removeSpaces($c[1]->plaintext));              //jmeno
            $subpole[2] = (int) @end(explode("/", $b->find('a')[0]->href));        //stravaID
            $subpole[3] = removeSpaces($c[2]->plaintext);              //klub
            $subpole[4] = removeSpaces($c[3]->plaintext);              //cas
            $subpole[5] = (float) $c[4]->plaintext;              //body
            $subpole[6] = "Z";                           //pohlavi
            $subpole[7] = $last_id_trate;                           //id etapa
            $ucastnici[] = $subpole;
          }
          unset($subpole);
          foreach($pole[1]->find('tr.top-10-m, tr.vsichni-m') as $b) {
            $c = $b->find('td'); 
            $subpole[0] = (int) $c[0]->plaintext;        //poradi
            $subpole[1] = utf8_encode(removeSpaces($c[1]->plaintext));              //jmeno
            $subpole[2] = (int) @end(explode("/", $b->find('a')[0]->href));        //stravaID
            $subpole[3] = removeSpaces($c[2]->plaintext);              //klub
            $subpole[4] = removeSpaces($c[3]->plaintext);              //cas
            $subpole[5] = (float) $c[4]->plaintext;              //body
            $subpole[6] = "M";                           //pohlavi
            $subpole[7] = $last_id_trate;                           //id etapa

            $ucastnici[] = $subpole;
          }
          unset($subpole);
          foreach($pole[2]->find('tr.top-10-c, tr.vsichni-c') as $b) {
            $c = $b->find('td'); 
            $subpole[0] = (int) $c[0]->plaintext;        //poradi
            $subpole[1] = "";                               //jmeno
            $subpole[2] = 0;                               //stravaID
            $subpole[3] = addslashes(removeSpaces($c[1]->plaintext));              //klub
            $subpole[4] = "00:00:00";                               //cas
            $subpole[5] = (float) $c[2]->plaintext;              //body
            $subpole[6] = "K";                           //pohlavi
            $subpole[7] = $last_id_trate;                           //id etapa

            $ucastnici[] = $subpole;  
          }

        }    
    }
    /// ----------------------------------etapa participants in the run endt
  }    

        foreach($ucastnici as $ucastnik) {
          $sql_test =  "SELECT * FROM ucastnici WHERE jmeno='".$ucastnik[1]."' AND stravaID=".$ucastnik[2]." AND klub='".$ucastnik[3]."' AND etapa= ".$ucastnik[7]." LIMIT 1";
          $result_test = mysqli_query($conn, $sql_test);
          if (mysqli_num_rows($result_test) > 0) {
                $row_test = mysqli_fetch_assoc($result_test);
                $sql_ucastnici = "UPDATE ucastnici SET poradi=".$ucastnik[0].", cas='".$ucastnik[4]."', body=".$ucastnik[5]." WHERE id=".$row_test['id'];
                 if (mysqli_query($conn, $sql_ucastnici)) {
                  echo "Record updated ".utf8_decode($ucastnik[1])." - StravaID:".$ucastnik[2]." - ".$ucastnik[7]."<br>";
                } else {
                  echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
                }
          } else {  
                $sql_ucastnici = "INSERT INTO ucastnici (poradi, jmeno, stravaID, etapa, klub, cas, body, pohlavi) VALUES "
                      ."(".$ucastnik[0]." ,'".$ucastnik[1]."', ".$ucastnik[2].", ".$ucastnik[7].", '".$ucastnik[3]."', '".$ucastnik[4]."', ".$ucastnik[5].", '".$ucastnik[6]."')";
              
                if (mysqli_query($conn, $sql_ucastnici)) {
                  echo "New record created successfully ".utf8_decode($ucastnik[1])." - StravaID:".$ucastnik[2]." - ".$ucastnik[7]."<br>";
                } else {
                  echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
                }
          }    
        }         
        unset($pole);
        unset($ucastnici);

}

mysqli_close($conn);

echo "Aktualizace ukončena <br>";
echo "<script language=\"JavaScript\">window.location=\"https://dp.wz.cz/index.php?akce=administrace\";</script>";
?>