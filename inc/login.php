<?php
session_regenerate_id(true);
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
                    <h1>Přihlášení do aplikace</h1>
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

                    <form action="index.php" method="post">
                      <table>
                        <tr>
                          <td width="20%"><label for="uname"><b>Uživatelské jméno</b></label></td>
                          <td><input type="text" placeholder="Vložte uživatelské jméno" name="uname" value="<?php echo @$uname; ?>" required size="50"  autofocus></td>
                        </tr>
                        <tr>
                          <td><label for="psw"><b>Heslo</b></label></td>
                          <td><input type="password" placeholder="Vložte heslo" name="psw" value="<?php echo @$psw; ?>" required size="50"></td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <!--   <input type="hidden" name="akce" value="prihlaseni">-->
                            <button type="submit" name="akce" value="prihlaseni" style="cursor: pointer;">Přihlásit</button>
                          </td>
                        </tr>
                       </table>
                      <br>
                      <br>
                      <div>
                        <span>Zapomenuté heslo? (Vložtě registrovanou emailovou adresu a na ní bude zasláno nové heslo pro přístup.)</a></span>
                      </div>
                     </form>

                    <form action="index.php" method="post">
                      <table>
                        <tr>
                          <td width="20%"><label for="forgot"><b>Email:</b></label></td>
                          <td><input type="text" placeholder="Vložte váš email" name="femail" value="<?php echo @$femail; ?>" required size="50"></td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <button type="submit" name="akce" value="zapomenute" style="cursor: pointer;">Zaslat heslo</button>
                          </td>
                        </tr>
                       </table>
                     </form>


                    </div>

                  </div>
                </div>
              </div>
            </section>
            <br><br><br>
            <!-- Page Heading -->
            <div class="page-heading">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <h1>Registrace nového uživatele</h1>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tables -->
            <section class="tables">
              <br>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                  <!--   <div class="section-heading">
                      <h2>Registrace nového uživatele</h2>
                    </div>  -->
                    <div class="default-table">

                    <form  action="index.php" name="registration" onsubmit="return formValidation()"  method="post">
                      <table>
                        <tr>
                          <td><label for="uname"><strong>Uživatelské jméno:</strong></label></td>
                          <td><input type="text" name="uname"  value="<?php echo @$uname; ?>" id="uname" placeholder="Vložte uživatelské jméno" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="pass"><strong>Heslo:</strong></label></td>
                          <td><input type="password" name="pass"  value="<?php echo @$pass; ?>" placeholder="Vložte své heslo" id="pass" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="cpass"><strong>Heslo (znovu): </strong></label></td>
                          <td><input type="password" name="cpass"  value="<?php echo @$cpass; ?>" placeholder="Vložte znovu heslo (pro kontrolu zadaného hesla)" id="cpass" size="50"></td>
                        </tr>
                        <tr>
                          <td width="20%"><label for="name">Jméno a Příjmení:</label></td>
                          <td><input type="text" name="name" id="name"  value="<?php echo @$name; ?>" placeholder="Vložte vaše jméno a příjmení" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="email">Email:</label></td>
                          <td><input type="text" name="email" id="email"  value="<?php echo @$email; ?>" placeholder="Vložte váš email" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="strava">STRAVA ID:</label></td>
                          <td><input type="text" name="strava" id="strava"  value="<?php echo @$strava; ?>" placeholder="Vložte vaše Id na strava.com" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="gender">Pohlaví:</label></td>
                          <td>
                            <select name="gender" id="gender">
                              <option value="">Vyber pohlaví</option>
                              <option value="M">Muž</option>
                              <option value="Z">Žena</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                             <!-- <input type="hidden" name="akce" value="registrace">-->
                            <button type="submit" name="akce" value="registrace" style="cursor: pointer;">Registrace</button>
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
              const name = document.getElementById("name");
              const uname = document.getElementById("uname");
              const email = document.getElementById("email");
              const pass = document.getElementById("pass");
              const cpass = document.getElementById("cpass");
              const strava = document.getElementById("strava");
              //const phoneNumber = document.getElementById("phoneNumber");
              const gender = document.getElementById("gender");
              //const language = document.getElementById("language");
              //const zipcode = document.getElementById("zipcode");
              
              // function for form varification
              function formValidation() {
                
                if (uname.value.length < 4 || uname.value.length > 50) {
                  alert("Jméno musí mít délku od 5 do 50 znaků!");
                  uname.focus();
                  return false;
                }
                // checking password
                if (!pass.value.match(/^.{6,20}$/)) {
                  alert("Délka hesla musí být od 6 do 20 znaků!");
                  pass.focus();
                  return false;
                }
                if (!cpass.value.match(/^.{6,20}$/)) {
                  alert("Délka hesla musí být od 6 do 20 znaků!");
                  cpass.focus();
                  return false;
                }
                if (pass.value != cpass.value) {
                  alert("Heslo a kontrola hesla se neshodují!");
                  cpass.focus();
                  return false;
                }
                if (name.value.length < 4 || name.value.length > 50) {
                  alert("Jméno musí mít délku od 5 do 50 znaků!");
                  name.focus();
                  return false;
                }
                // checking email
                if (email.value === "") {
                  alert("Musíte zadat emailovou adresu!");
                  email.focus();
                  return false;
                }
                if (!email.value.match(/^([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/)) {
                  alert("Toto není platná email adresa!");
                  email.focus();
                  return false;
                }
                // checking strava
                if (!strava.value.match(/^\d{1,20}$/)) {
                  alert("Toto není platné STRAVA ID!");
                  strava.focus();
                  return false;
                }
                // checking gender
                if (gender.value === "") {
                  alert("Zvolte pohlaví!")
                  return false;
                }
                return true;
              }
        </script> 
            
            <?php
?>        