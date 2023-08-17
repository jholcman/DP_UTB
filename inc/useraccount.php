<?php

if ($message != "") {
    echo "            <div class=\"container-fluid\" style=\"background:".$colour.";color:white;margin:0px 0px 10px 0px;padding:10px 0px 10px 20px;\">\n";
    echo "               <strong>".$message."</strong>\n";
    echo "            </div>\n";
}

?>
            <!-- Page Heading -->
            <div class="page-heading">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <h1>Můj účet</h1>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tables -->
            <section class="tables">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                  <!--   <div class="section-heading">  
                       <h2>Přihlášení uživatele</h2>          
                    </div>  -->                         
                    <div class="default-table">

                      <div>
                        <span>Zrušení účtu:</a></span>
                      </div>
                    <form action="index.php" onsubmit="return potvrzeni()" method="post">
                      <table>
                        <tr>
                          <td><button type="submit" name="akce" value="smazatucet" style="cursor: pointer;">Smazat účet</button></td>
                        </tr>
                        <tr>
                          <td width="20%">Zároveň se zrušenímí vašeho účtu dojde i ke smazání všech vašich dat bez možnosti obnovy.</label></td>
                        </tr>
                       </table>
                      <br>
                      <br>
                     </form>

                      <div>
                        <span>Změna hesla:</a></span>
                      </div>
                    <form action="index.php" onsubmit="return formValidation()" method="post">
                      <table>
                        <tr>
                          <td><label for="ppass"><strong>Heslo (současné):</strong></label></td>
                          <td><input type="password" name="ppass"  placeholder="Vložte současné heslo" id="ppass" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="cpass"><strong>Heslo (nové): </strong></label></td>
                          <td><input type="password" name="npass" placeholder="Vložte nové heslo" id="npass" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="cpass"><strong>Heslo (nové - kontrola): </strong></label></td>
                          <td><input type="password" name="cpass" placeholder="Vložte nové heslo (pro kontrolu zadaného hesla)" id="cpass" size="50"></td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <button type="submit" name="akce" value="zmenahesla" style="cursor: pointer;">Změnit heslo</button>
                          </td>
                        </tr>
                       </table>
                     </form>


                    </div>

                  </div>
                </div>
              </div>
            </section>



            
           <script type = "text/javascript">  
        
              // Select all input elements for varification
              const ppass = document.getElementById("ppass");
              const npass = document.getElementById("npass");
              const cpass = document.getElementById("cpass");
              
              // function for form varification
              function formValidation() {
                
                // checking password
                if (!ppass.value.match(/^.{6,20}$/)) {
                  alert("Délka hesla musí být od 6 do 20 znaků!");
                  ppass.focus();
                  return false;
                }
                if (!npass.value.match(/^.{6,20}$/)) {
                  alert("Délka hesla musí být od 6 do 20 znaků!");
                  npass.focus();
                  return false;
                }
                if (!cpass.value.match(/^.{6,20}$/)) {
                  alert("Délka hesla musí být od 6 do 20 znaků!");
                  cpass.focus();
                  return false;
                }
                if (npass.value != cpass.value) {
                  alert("Heslo a kontrola hesla se neshodují!");
                  cpass.focus();
                  return false;
                }
                return true;
              }
              
              function potvrzeni() {
                
                // checking password
                return confirm("Opravdu chcete váš účes smazat?\n(S účtem budou smazána i všechny vaše data)");
              }
        </script> 
          