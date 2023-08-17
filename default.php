<?php
echo "<!-- default -->\n";
echo <<<HTML
            <!-- Banner -->
            <section class="main-banner">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <div class="banner-content">
                      <div class="row">
                        <div class="col-md-12">
                        
                          <div class="banner-caption">
                          
                            <h4>Vítejte v aplikaci <em>TOURNAMENT</em>.</h4>
                            <span>Virtuální běžecké závody</span>
                            <p>Aplikace pro sledování a analýzu virtuálních běžeckých závodu <strong>TRAILTOUR</strong>, s možností predikce výsledků, založených na metodách strojového učení.</p>
                            <div class="primary-button">
                              <a href="#vice">Více informací...</a>
                            </div>
                            
                          </div>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <!-- Services -->
            <section class="services">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-4">
                    <div class="service-item first-item">
                      <div class="icon"></div>
                      <h4>Grafická úprava</h4>
                      <p>Grafickou šablonu aplikaci poskytl <a rel="nofollow" href="https://www.facebook.com/templatemo">TemplateMo</a>, doplněných obrázky zdarma z webové databanky <a rel="nofollow" href="https://www.rawpixel.com/">rawpixel</a>.</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="service-item fourth-item">
                      <div class="icon"></div>
                      <h4>Použití zdarma</h4>
                      <p>Vytvořeno v rámci závěrečné diplomové práce Univerzity Tomáše Bati ve Zlíne, fakulty aplikované informatiky.</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="service-item third-item">
                      <div class="icon"></div>
                      <h4>PHP HTML CSS MySQL</h4>
                      <p>Aplikace postavena na výkonných programovacých jazycích a relačním databázovém systému.</p>
                    </div>
                  </div>
             </div>
              </div>
            </section>

            <!-- Top Image -->
            <section class="top-image">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <img src="assets/images/top-image.jpg" alt="">
                    <div class="down-content">
                      <h4 id="vice">O aplikaci TOURNAMENT</h4>
                      <p>Aplikace umožňuje sledování a analýzu výsledků virtuálních závodů Trailtour. Obsahuje kompletní výsledky od roku 2018. V databázi jsou uloženy informace o pořádaných akcích, 
                      etapách a výsledky jednotlivých závodníků. 
                      Můžeme zobrazit informace o akci, jejích etapách, počtu závodníku, jejich výsledcích a pořadí na jakých se umístily včetně bodového ohodnocení dle pravidel závodů.
                      <br>
                      Aplikace nabízí registraci uživatele. Tento uživatel nemusí být oficiálním účastníkem závodů. Pokud se závodů oficiílně účastníte, není tato účast nijak zohledněna. 
                      Po registraci si uživatel může libovolně zadávat výsledky jednotlivých etap. Díky temto datům je možno sledovat, jakých výsledků by bylo dosaženo, pokud by se závodů účastnil. 
                      Navíc aplikace umožňuje za pomocí algoritmů stojového učení predikovat (odhadnout) budoucí výsledky u etap, kterých se uživatel ještě nezúčastnil. 
                      Tato predikce je však založena na již dosažených výsledků uživatele a výsledcích oficiálních účastníků závodů a proto je výsledný odhad velmi přesný
                      (vše závisí na množství výsledků zadaných uživatelem).
                      </p>
                      <!-- <div class="primary-button"> -->
                      <!--   <a href="#">Read More</a> -->
                      <!-- </div> -->
                    </div>
                  </div>
                </div>
              </div>
            </section>
HTML;            
?>