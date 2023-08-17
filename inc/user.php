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
                    <h1>Informace o uživateli</h1>
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
                          <td><label for="name"><strong>Uživatelské jméno:</strong></label></td>
                          <td><input type="text" name="name" id="username" placeholder="Vložte uživatelské jméno" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="password"><strong>Heslo:</strong></label></td>
                          <td><input type="password" name="password" placeholder="Vložte své heslo" id="password" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="cpassword"><strong>Heslo (znovu): </strong></label></td>
                          <td><input type="password" name="cpassword" placeholder="Vložte znovu heslo (pro kontrolu zadaného hesla)" id="cpassword" size="50"></td>
                        </tr>
                        <tr>
                          <td width="20%"><label for="name">Jméno a Příjmení:</label></td>
                          <td><input type="text" name="name" id="name" placeholder="Vložte vaše jméno a příjmení" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="email">Email:</label></td>
                          <td><input type="text" name="email" id="email" placeholder="Vložte váš email" size="50"></td>
                        </tr>
                        <tr>
                          <td><label for="email">STRAVA ID:</label></td>
                          <td><input type="text" name="strava" id="strava" placeholder="Vložte vaše Id na strava.com" size="50"></td>
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
              const username = document.getElementById("username");
              const email = document.getElementById("email");
              const password = document.getElementById("password");
              const cpassword = document.getElementById("cpassword");
              const strava = document.getElementById("strava");
              //const phoneNumber = document.getElementById("phoneNumber");
              const gender = document.getElementById("gender");
              //const language = document.getElementById("language");
              //const zipcode = document.getElementById("zipcode");
              
              // function for form varification
              function formValidation() {
                
                // checking name length
                if (name.value.length < 4 || name.value.length > 50) {
                  alert("Jméno musí mít délku od 5 do 50 znaků!");
                  name.focus();
                  return false;
                }
                if (username.value.length < 4 || username.value.length > 50) {
                  alert("Jméno musí mít délku od 5 do 50 znaků!");
                  username.focus();
                  return false;
                }
                // checking email
                if (email.value.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
                  alert("Toto není platná email adresa!");
                  email.focus();
                  return false;
                }
                // checking strava
                if (strava.value.match(/^[0-9]{15}*$/)) {
                  alert("Toto není platné STRAVA ID!");
                  strava.focus();
                  return false;
                }
                // checking password
                if (!password.value.match(/^.{6,20}$/)) {
                  alert("Délka hesla musí být od 6 do 20 znaků!");
                  password.focus();
                  return false;
                }
                if (!cpassword.value.match(/^.{6,20}$/)) {
                  alert("Délka hesla musí být od 6 do 20 znaků!");
                  cpassword.focus();
                  return false;
                }
                if (password.value != cpassword.value) {
                  alert("Heslo a kontrola hesla se neshodují!");
                  cpassword.focus();
                  return false;
                }
                // checking phone number
                if (!phoneNumber.value.match(/^[1-9][0-9]{9}$/)) {
                  alert("Phone number must be 10 characters long number and first digit can't be 0!");
                  phoneNumber.focus();
                  return false;
                }
                // checking gender
                if (gender.value === "") {
                  alert("Zvolte pohlaví!")
                  return false;
                }
                // checking language
                if (language.value === "") {
                  alert("Please select your language!")
                  return false;
                }
                // checking zip code
                if (!zipcode.value.match(/^[0-9]{6}$/)) {
                  alert("Zip code must be 6 characters long number!");
                  zipcode.focus();
                  return false;
                }
                return true;
              }
        </script> 
            
            <?php
?>        