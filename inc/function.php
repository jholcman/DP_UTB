<?php
function removeSpaces($text) {
  $vysledek = str_replace("&nbsp;"," ",$text);
  return Trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/','',$vysledek)); 
}

function etapyUcastnici($url) {
  $etapy = 0;
  $html = file_get_html($url);
  if ($html) {
    $pole = $html->find('div.etapa-item');
    $etapy = count($pole);
  }
  return $etapy; 
}

function timeToSeconds($time)
{
     $timeExploded = explode(':', $time);
     if (isset($timeExploded[2])) {
         return $timeExploded[0] * 3600 + $timeExploded[1] * 60 + $timeExploded[2];
     }
     return $timeExploded[0] * 60 + $timeExploded[1];
}

function secondsToTime($time)
{
    $hours = floor($time / 3600);
    $minutes = floor(($time / 60) % 60);
    $seconds = $time % 60;
    return gmdate('H:i:s',$time);
}

function mujCasExistuje($id_user, $id_etapa) {
  $existuje = false;

  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT * FROM moje_data WHERE (id_etapa=".$id_etapa." AND id_user=".$id_jmeno.")";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
  		$existuje = true;
  }
  mysql_close($conn);
  return $existuje; 
}

function mujCas($id_user, $id_etapa) {
  $cas = 0;

  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT * FROM moje_data WHERE (id_etapa=".$id_etapa." AND id_user=".$id_jmeno.")";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
  		$row = mysqli_fetch_assoc($result);
  		$cas = $row["cas"];
  }
  mysql_close($conn);
  return $cas; 
}

function jmeno($stravaId, $pohlavi) {
  $vysledek = "Novák Jan";
  include "inc/MySQL.inc";
  $conn1 = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn1) {
     die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT * FROM jmena WHERE stravaId=".$stravaId;
  $result = mysqli_query($conn1, $sql);

  if (mysqli_num_rows($result) > 0) {
  		$row = mysqli_fetch_assoc($result);
  		$vysledek = $row["jmeno"];

  } else {

     $muz_jmeno = array ("Jakub", "Jan", "Tomáš", "Matyáš", "Adam", "Filip", "Vojtěch", "Lukáš", "Martin", "Matěj", "Ondřej", "Daniel", "David", "Dominik", "Antonín", "Michal", 
                    "Petr", "Štěpán", "Tobiáš", "Marek", "Samuel", "Jiří", "Václav", "Šimon", "Kryštof", "Jonáš", "Mikuláš", "Oliver", "Tadeáš", "Patrik", "Josef", "František", "Jáchym", "Pavel", 
                    "Viktor", "Sebastian", "Karel", "Eliáš", "Jaroslav", "Vít", "Matouš", "Teodor", "Richard", "Kristián", "Michael", "Eduard", "Sebastián", "Maxmilián", "Jindřich", "Vilém",
                    "Alex", "Vítek", "Maxim", "Robin", "Max", "Nikolas", "Damián", "Albert", "Alexandr", "Miroslav", "Denis", "Milan", "Vincent", "Radek", "Ladislav", "Artur", "Erik",
                    "Stanislav", "Tobias", "Zdeněk", "Roman", "Damian", "Hugo", "Vladimír", "Prokop", "Radim", "Oskar", "Vojta", "Robert", "Nicolas", "Mateo", "Gabriel", "Aleš", "Adrian", 
                    "Kristian", "Bartoloměj", "Hynek", "William", "Alfréd", "Mathias", "Rostislav", "Matteo", "Theodor", "Otakar", "Vítězslav", "Oldřich", "Simon", "Alan", "Boris", "Benjamin", 
                    "Leon", "Patrick", "Matyas", "Tomas", "Rudolf", "Metoděj", "Sebastien", "Mikoláš", "Radovan", "Ota", "Štefan", "Lubomír", "Benjamín", "Kevin", "Christian", "Čeněk", 
                    "Ivan", "Bernard", "Jaromír", "Jonatan", "Felix", "Marcel", "Luboš", "Matias", "Luděk", "Timotej", "Přemysl", "Vavřinec", "Lukas", "Teo", "Brian", "Darek", "Ben", "Hubert",
                    "Fabian", "Eliot", "Ferdinand", "Arnošt", "Čestmír", "Julián", "Bruno", "Frederik", "Miloš", "Viliam", "Valentýn", "Radomír", "Waldemar", "Nathaniel", "Tuan Kiet", 
                    "Marian", "Victor", "Norbert", "Michail", "Ryan", "Zachariáš", "Marcus", "Tom", "Mattias", "Mark", "Tamir", "Lucas", "Tayler", "Marco", "Zdenek", "Michaela", "Maksym",
                    "Vladislav", "Theo", "Mikeš", "Thobias", "Zbyněk", "Timon", "Oliver Vladimír", "Santiago", "Leonard", "Justin", "Ján", "Bryan", "Bohuslav", "Andrej", "Alois", "Jason",
                    "Cyril", "Benedikt", "Kamil", "Andrew", "Dan", "Dalibor", "Alexander", "Gia Bao", "André", "Elliot", "Janek", "Josef Šimon", "Libor", "Tomáš Antonín", "Mohammed", 
                    "Michal Jan", "Mario Gabriel", "Tichon Konstantinovič", "Oliver Eduard", "Neguun", "Oliver Kai", "Miles Sebastian", "Oliver Nikolas", "Mychajlo", "Oliver Petr", 
                    "Maxim Denisovič", "Oliver Vilém", "Tuvshinbayar", "Miloslav", "Michael Itoro", "Milosz", "Wassim", "Marko", "Matyáš Pavel", "Markus", "Teodor Jan", "Lucas Aloysius",
                    "Myron", "Oto", "Timotheus", "Otto", "Tobin", "Paris", "Tristan", "Martin Gabriel", "Mikuláš Lumír", "Martin Samuel", "Marcus Mathias", "Martin Stanislav", "Marco Nello",
                    "Pavel Antonín", "Vojtěch Josef", "Pavol", "Yael Ibrahima", "Pawel", "Mário", "Pessi Arvo", "Tai Duc", "Peter", "Musa", "Matej", "Thanh Dung", "Petr Karel", "Theodore", 
                    "Petr Kevin", "Thomas Jax", "Petr Viktor", "Nam Khanh", "Pham Nhat Minh", "Timothy David", "Phan Anh", "Maxmilian", "Philip Petr", "Manraj", "Phuc Hoang Gia", 
                    "Tonny", "Phuc Nguyen", "Nataniel", "Pietro", "Urv Ankitkumar", "Zef", "Václav Radek", "Mikuláš Florián", "Nhat Nang", "Minh Anh", "Nicholas Michael", "Quang Tien", 
                    "Nikol", "Quoc Viet", "Michal Artur", "Matěj Ignác", "Vojtěch Alexandr", "Matěj Vendelín", "Vratislav", "Radko", "Xuan Hoa", "Minh Duc", "Yoro", "Luis", "Nšan", "Rafael",
                    "Štěpán Leonard", "Ralf", "Tadeáš Šimon", "Rayan", "Misheel", "René", "Mojmír", "Mateo Lam", "Teodor Ignác", "Richard Salvátor", "Thai Quan", "Matheo", "Thanh Tung", 
                    "Minh Khang", "Manas", "Mathyas", "Theon", "Roman Aladár", "Thomas", "Roman Kristián", "Tibor", "Ronald", "Tim", "Minh Quan", "Nárada", "Timothy", "Minh Tiep", 
                    "Timothy Viktor", "Rufin", "Tobias Wayne", "Minh Tuan", "Tobiáš Alex", "Sal Daniel", "Natanael", "Salvatore", "Metin", "Magnus", "Tommy", "Samuel Enrique", "Tony", 
                    "Samuel Lucas", "Truong An", "Samuel Mateo", "Tuan Manh", "Samuel Milan", "Uils Tuguldur", "Samuel Pietro", "Useinu Sebastian", "Minh Vuong", "Václav Christian", 
                    "Mattheo", "Vaibhav", "Matthew", "Vasil Jakub", "Sebastián David", "Vendelín", "Sebastian Eduard", "Viggo Jan", "Marek Vojtěch", "Viktor Jan", "Semi", "Nikita", 
                    "Semir", "Marián", "Simeon", "Michal Antonvyč", "Maik", "Nikolas Daniel", "Slavomil", "Mikel", "Slavomír", "Vojtěch Jan", "Mátyás", "Vojtěch Pavel", "Stepan", 
                    "Noe", "Stěpan", "Noel", "Szymon David", "Xuan Vuong", "Šanel", "Ye Cheng", "Matyas Jan", "Zachar", "Šimon František", "Norman", "Šimon Otakar", "Mikko", "Maksar", 
                    "Matyáš Daniel", "Pranav", "Jakub Jan", "Kvido", "Josef Antonín", "Anand", "Hugo Felix", "Anar", "Jayden Leon", "Adrien", "Kerim", "Andreas", "Hanuš", "Adam Jiří", 
                    "Ivan Jan", "Damián Adam", "Jan Václav", "Damien", "Joakim", "Damir", "Kaden Ralph", "Adam Josef", "Alva", "Dang Khoa", "Leonardo", "Dang Minh Tu", "Hoang Hai Dang",
                    "Andrij", "Bartlomiej Piotr", "Daniel Alexander", "Jáchym Jan", "Daniel Bogdan", "Bedřich", "Daniel Patrick", "Alexander Lucius", "Daniel Raymond", "Benjamin Joseph",
                    "Daniel Waldemar", "Bertram", "Danylo", "Bohuš", "Andy", "Karel Hamada", "Darien", "Kilián", "Dario", "Kryšpín", "Anh Duc", "Cristian Rafael", "David Elias", "Levi", 
                    "David Hoang", "Heliodor", "David Nathanael", "Auguste", "Deniel", "Barnabáš", "Anthony", "Igor", "Deniz", "Izák", "Dennis", "Bartoměj", "Diego", "James", "Dinh Cao",
                    "Jan Kristian", "Dinh Khoi", "Adam Václav", "Diviš", "Jaroslav Teodor", "Dobroslav", "Jeremy", "Dominic", "Jiří Blažej", "Anthony Daniel", "Adam Alexander", 
                    "Dominik Milan", "Jordan Odinakachukwu", "Dorian", "Josef William", "Dorián", "Juraj", "Duc Minh Khoi", "Bořivoj", "Duc Sang", "Kelly Petr", "Duc Tri", 
                    "Khantenger", "Dušan", "Kosma", "Eda", "Kristof", "Edgar", "Kryštof Jáchym", "Anthony George", "Caleb Oliver", "Eduard Jacob", "Cristiano", "Eduard Jan", 
                    "Leopold", "Edvard", "Gualtier Guiragos", "Edvin", "Hektor", "Edward", "Henrik", "Edward Andrew", "Hristijan", "Elias", "Ba Khoi", "Anton", "Huy Minh", 
                    "Eliáš Filip", "Hynek Michal", "Alejandro", "Ian", "Antonio", "Adam Robert", "Elon", "Izak", "Elon Tomáš", "Bartoloměj Kryštof", "Liam", "Jake Simon", 
                    "Lionel", "Jakub Elias", "Loki", "Jakub Michal", "Emilio", "Bayan", "Emín", "Jan Jaroslav", "Emir", "Jan Mark", "Emmett", "Jan Vincenc", "Erbold", 
                    "Janek Maximilian", "Eric", "Adam Viliam", "Arbi", "Alexandre", "Erik Julius", "Jeremiáš", "Ernest Oliver", "Jimmy", "Eron", "Benjamin Sebastian", 
                    "Ethan Noah", "Jiří Emanuel", "Ezel", "John", "Aren Alexander", "Jonáš Radek", "Fabián", "Jonatán", "Fabian Claudiu", "Bohumil", "Fabián Josef", 
                    "Alfréd Antonín", "Faisal", "Jozef", "Ares Murat", "Julius", "Felix Anthony", "Ali", "Archie Tomaš", "Kaito", "Alessio", "Allan Matyáš", "Filip Andrei", 
                    "Karel Jakub", "Filip Viktor", "Kelvin", "Florián", "Bronislav", "Fran", "Kilian", "Áron", "Koloman", "František Emil", "Adrian Jaroslav", "František Jiří", 
                    "Kristian Wassilev", "František Max", "František Osvald", "Břetislav", "Arthur", "Kuba", "Fredy", "Kvído", "Adam Lubomír", "Leo", "Gabriele Petr", 
                    "Leon Philip", "Garid", "Leonard Miltiades", "George Nicholas", "Leonidas", "Atrey", "Leoš", "Gia Hieu", "Amer", "Giovanni", "Eloy", "Logan", "Elyas", "Abraham", "Emil");
 
     $muz_prijmeni = array ("Svoboda", "Novotný", "Černý", "Kučera", "Veselý", "Krejčí", "Němec", "Marek", "Pospíšil", "Pokorný", "Jelínek", "Růžička", "Beneš", "Fiala", 
                    "Doležal", "Zeman", "Urban", "Vaněk", "Blažek", "Kříž", "Kratochvíl", "Bartoš", "Vlček", "Kopecký", "Musil", "Šimek", "Konečný", "Malý", "Čech", "Holub", "Staněk", 
                    "Kadlec", "Šťastný", "Soukup", "Mareš", "Moravec", "Sýkora", "Tichý", "Valenta", "Matoušek", "Říha", "Bureš", "Ševčík", "Hruška", "Mašek", "Dušek", "Pavlík", "Havlíček", 
                    "Hrubý", "Janda", "Mach", "Beran", "Liška", "Vítek", "Toman", "Müller", "Vacek", "Tůma", "Šmíd", "Kašpar", "Švec", "Stejskal", "Jaroš", "Kočí", "Strnad", "Ježek", "Bílek", 
                    "Němeček", "Slavík", "Matějka", "Fišer", "Tesař", "Kraus", "Kubíček", "Brož", "Prokop", "Havel", "Havlík", "Pavlíček", "Klíma", "Suchý", "Richter", "Kohout", "Šulc", 
                    "Janeček", "Pešek", "Čížek", "Petr", "Janoušek", "Souček", "Špaček", "Straka", "Jedlička", "Kovařík", "Havelka", "Zapletal", "Filip", "Vrba", "Červenka", "Stehlík", 
                    "Přibyl", "Daněk", "Janků", "Holý", "Hladík", "Kubík", "Benda", "Hrdlička", "Pavelka", "Vlk", "Linhart", "Adamec", "Nový", "Volf", "Klimeš", "Brabec", "Zelenka", 
                    "Březina", "Turek", "David", "Macek", "Řezníček", "Jílek", "Kořínek", "Trnka", "Martínek", "Burian", "Lukeš", "Čapek", "Šíma", "Dohnal", "Dlouhý", "Hošek", "Holeček",
                    "Sobotka", "Hanuš", "Malík", "Vašíček", "Kroupa", "Švarc", "Gregor", "Nosek", "Vodička", "Fojtík", "Vlach", "Šebesta", "Šedivý", "Kuchař", "Prokeš", "Smetana", "Sikora",
                    "Zajíček", "Chaloupka", "Kalina", "Kopřiva", "Bartoň", "Starý", "Koudelka", "Dufek", "Červený", "Burda", "Přikryl", "Mikeš", "Majer", "Duda", "Hofman", "Horký", 
                    "Adam", "Kocourek", "Štěrba", "Kouba", "Doležel", "Hudec", "Petřík", "Diviš", "Šebek", "Coufal", "Tomek", "Rada", "Bauer", "Jindra", "Zahradník", "Uhlíř", "Chalupa", 
                    "Kunc", "Zajíc", "Minařík", "Zavadil", "Dolejší", "Petrů", "Vašek", "Kvapil", "Pech", "Škoda", "Večeřa", "Bílý", "Langer", "Mareček", "Hovorka", "Peterka", "Uher",
                    "Smejkal", "Vaníček", "Čejka", "Martinek", "Žižka", "Melichar", "Tuček", "Balog", "Berka", "Zatloukal", "Červinka", "Franc", "Trojan", "Hanzlík", "Šimon", "Koutný",
                    "Hampl", "Dobeš", "Kupka", "Křížek", "Veverka", "Neumann", "Kos", "Nečas", "Hradil", "Kozel", "Janík", "Janů", "Smolík", "Popelka", "Hrdina", "Kubín", "Vít", 
                    "Kubeš", "Martinec", "Malina", "Boček", "Lorenc", "Houdek", "Buchta", "Hora", "Ryšavý", "Šubrt", "Lang", "Hloušek", "Seidl", "Skalický", "Kolařík", "Kafka", 
                    "Mucha", "Šimůnek", "Vaculík", "Křenek", "Zelený", "Demeter", "Lacina", "Čonka","Sochor", "Vala", "Pavlů", "Janíček", "Průša", "Zelinka", "Polívka", "Hořejší", 
                    "Nejedlý", "Vyskočil", "Plachý", "Böhm", "Ferenc", "Nedvěd", "Schneider", "Blaha", "Smutný", "Šmejkal", "Mařík", "Pecha", "Dočkal", "Hudeček", "Holík", "Bareš", 
                    "Doubek", "Jirků", "Samek", "Klein", "Kuba", "Homola", "Pilař", "Kostka", "Verner", "Daniel", "Hynek", "Ludvík", "Herman", "Homolka", "Sojka", "Votava",
                    "Prchal", "Menšík", "Stuchlík", "Karas", "Koubek", "Jakubec", "Vondra", "Nguyen", "Hlavatý", "Slabý", "Matuška", "Plaček", "Chovanec", "Matula", "Kotek", "Vaněček", 
                    "Machala", "Antoš", "Klement", "Schwarz", "Sokol", "Kohoutek", "Buček", "Zima", "Janovský", "Kouřil", "Valeš", "Mikula", "Zbořil", "Wolf", "Šimeček", "Vojta", 
                    "Dolejš", "Gabriel", "Dubský", "Císař", "Chytil", "Hron", "Kudrna", "Slavíček", "Šolc", "Vybíral", "Pospíchal", "Fischer", "Krupička", "Frank", "Žiga", "Krejčík",
                    "Varga", "Dudek", "Pařízek", "Javůrek", "Šnajdr", "Bezděk", "Mužík", "Švejda", "Hladký", "Cibulka", "Franěk", "Bajer", "Spurný", "Pelc", "Synek", "Kvasnička", 
                    "Průcha", "Műller", "Wagner", "Šustr", "Bednařík", "Kaplan", "Smékal", "Šafařík", "Gajdoš", "Míka", "Vojtěch", "Černík", "Kantor", "Zach", "Raška", "Studený", 
                    "Příhoda", "Trčka", "Knotek", "Chmelař", "Miko", "Sova", "Svatoš", "Blecha", "Hašek", "Hendrych", "Vančura", "Provazník", "Hrnčíř", "Fridrich", "Brožek", 
                    "Pazdera", "Jančík", "Šindler", "Volný", "Jansa", "Zíka", "Hrbek", "Jahoda", "Švehla", "Anděl", "Balcar", "Dočekal", "Štefan", "Zikmund", "Foltýn", 
                    "Chmelík", "Landa", "Dvorský", "Šiška", "Bobek", "Černoch", "Matouš", "Brychta", "Suk", "Fučík", "Vojtíšek", "Kindl", "Heřman", "Hrůza", "Merta", "Andrle", 
                    "Michal", "Junek", "Lacko", "Karlík", "Kukla", "Kubů", "Vorel", "Kalous", "Berger", "Fencl", "Holec", "Mička", "Kopečný", "Tomeček", "Dobrovolný", "Houška",
                    "Jašek", "Bouška", "Hradecký", "Skala", "Pešta", "Jakeš", "Medek", "Maršík", "Schejbal", "Rezek", "Nagy", "Dokoupil", "Pavel", "Mlčoch",
                    "Hajný", "Ryba", "Albrecht", "Schmidt", "Teplý", "Bouda", "Pecka", "Smola", "Zavřel", "Rudolf", "Najman", "Hanus", "Machač", "Čada", "Tříska", "Kazda", 
                    "Slanina", "Volek", "Fořt", "Strouhal", "Štefek", "Pekař", "Tomšů", "Koukal", "Hejda", "Neubauer", "Motyčka", "Brejcha", "Peřina", "Řehoř", "Smolka",
                    "Hejl", "Kopeček", "Lebeda", "Zouhar", "Hoffmann", "Odehnal", "Louda", "Machů", "Tomeš", "Ambrož", "Janča", "Kysela", "Tóth", "Vrabec", "Chvojka", 
                    "Hrabal", "Fiedler", "Vejvoda", "Skopal", "Šlechta", "Ulrich", "Bittner", "Rak", "Hejduk", "Hartman", "Sadílek", "Pašek", "Polanský", "Široký", 
                    "Suchomel", "Klouda", "Forman", "Sekanina", "Švanda", "Voříšek", "Ferko", "Šanda", "Pavlas", "Michalec", "Kasal", "Mlejnek", "Fanta", "Myška", "Jiroušek", 
                    "Klečka", "Mika", "Pohl", "Doubrava", "Drozd", "Komínek", "Šeda", "Hanzl", "Černohorský", "Weiss", "Mayer", "Stoklasa", "Kurka", "Zoubek", "Korbel", "Berky", 
                    "Paleček", "Papež", "Huml", "Pernica", "Holoubek", "Matějů", "Pícha", "Weber", "Baloun", "Horský", "Sladký", "Horník", "Vojtek", "Ondra", "Bartůněk", "Bernard", 
                    "Kuneš", "Fuchs", "Kalousek", "Mirga", "Matějíček", "Janata", "Peroutka", "Šplíchal");

     $zena_jmeno = array ("Eliška", "Anna", "Adéla", "Tereza", "Sofie", "Viktorie", "Ema", "Karolína", "Natálie", "Amálie", "Julie", "Kristýna", "Barbora", "Nela", 
                    "Laura", "Klára", "Emma", "Anežka", "Rozálie", "Sára", "Marie", "Lucie", "Aneta", "Zuzana", "Mia", "Veronika", "Ella", "Alžběta", "Nikola", "Gabriela", "Kateřina", 
                    "Nikol", "Valerie", "Magdaléna", "Nina", "Liliana", "Ester", "Elena", "Josefína", "Adriana", "Markéta", "Štěpánka", "Stella", "Magdalena", "Stela", "Beáta", "Terezie", 
                    "Linda", "Jasmína", "Emily", "Vanesa", "Michaela", "Elen", "Antonie", "Emílie", "Dominika", "Denisa",  "Agáta", "Johana", "Diana", "Lea", "Bára", "Rozárie", "Klaudie", 
                    "Justýna", "Mariana", "Izabela", "Jana", "Hana", "Monika", "Vendula", "Zoe", "Patricie", "Isabella", "Andrea", "Daniela", "Eva", "Simona", "Šárka", "Sofia", "Meda",
                    "Lilien", "Vivien", "Sabina", "Elizabeth", "Alena", "Martina", "Nella", "Leontýna", "Ellen", "Evelína", "Kamila", "Lenka", "Malvína", "Victoria", "Valérie", 
                    "Karla", "Ela", "Adina", "Jolana", "Šarlota", "Vanessa", "Olivie", "Pavlína", "Sandra", "Johanka", "Františka", "Lily", "Petra", "Tamara", "Miriam", "Anastázie",
                    "Alice", "Amélie", "Edita", "Helena", "Dorota", "Victorie", "Melisa", "Valentýna", "Thea", "Tina", "Lara", "Lada", "Elisabeth", "Marianna", "Žofie",
                    "Zita", "Timea", "Zora", "Noemi", "Melanie", "Ráchel", "Alexandra", "Charlotte", "Berenika", "Eleonora", "Isabela", "Hedvika", "Evelin", "Olivia", "Věra", 
                    "Samanta", "Matylda", "Lota", "Lilly", "Viola", "Milada", "Valentina", "Natali", "Viktoria", "Nicol", "Olívie", "Claudia", "Beata", "Eleanor", "Ivana", "Claudie", 
                    "Leona", "Ludmila", "Tea", "Rosalie", "Melissa", "Natalie", "Magda", "Renata", "Sophia", "Taťána", "Zorka", "Anna Marie", "Iveta", "Anita", "Amálka", "Jindřiška", 
                    "Amy", "Inna", "Abigail", "Iva", "Anastasia", "Jaroslava", "Apolena", "Jiřina", "Gita", "Katka", "Ilona", "Heda", "Tara", "Nathalie", "Natalia", "Naomi", "Zara",
                    "Zlata", "Olga", "Marlen", "Naďa", "Nicole", "Vilma", "Nicoll", "Tala", "Žaneta", "Nora", "Sarah", "Maria", "Sidonie", "Vanda", "Silvie", "Marie Michaela", "Soňa",
                    "Marika", "Melania", "Rebeka", "Sylva", "Nelly", "Lili", "Madlen", "Rozárka", "Marta", "Doubravka", "Beatrice", "Alex", "Evelina", "Kristina", "Evelyn", "Eliana",
                    "Cecílie", "Jessica", "Dana", "Annabell", "Ha My", "Kristyna", "Larisa", "Avantika", "Hela", "Jarmila", "Ida", "Jennifer", "Darja", "Anabela",
                    "Ina", "Bela", "Dorotea", "Erika", "Isabel", "Kristína", "Anika", "Anděla", "Isabell", "Freya", "Tereza Mia", "Wioleta", "Leontýna Thao My", "Mélanie", 
                    "Šárka Karolína", "Ngoc Anh", "Lilian", "Ngoc Han", "Natálie Nikola", "Ngoc Minh", "Zola", "Nguyen", "Tatiana", "Nguyet Cat Tien", "Theodora",
                    "Nhu Thao", "Ujin", "Melanie Daniela", "Natálie Anna", "Nicol Valérie", "Melánie", "Nicola", "Zdislava", "Melis", "Stephanie Anna", "Lilly Anna", 
                    "Naděžda", "Niki", "Teodora", "Lola", "Thai An", "Lora", "Thuy Anh", "Nikoleta", "Truc Diem", "Lorelaj", "Martina Marie", "Ninel", "Vanesa Marie",
                    "Melisa Izabela", "Versaviia Adina Mary", "Noémi Aina", "Viktorie Alena", "Matilda", "Vitoria", "Nour", "Yuan Yi", "Ola", "Lilien Anna", "Leticie", 
                    "Zuzana Erika", "Mia Sidonie", "Mya", "Olívia", "Šarlota Isabela", "Olivia Elizabeth", "Lillien", "Lotka", "Teija Aniela", "Manuela", "Otilia", 
                    "Terezie Gabriela", "Patrícia Milena", "Thao Vy", "Liana", "Thi Yen Vy", "Paulína", "Thuy Tien", "Pauline", "Tran Bao An", "Lily Hope", "Tue Phi",
                    "Penelope Rose", "Ulrike", "Ludvika", "Valeria", "Phuong Anh", "Lívia", "Polina", "Marie Josefína", "Quynh Anh", "Veronika Anna", "Quynh Chi",
                    "Marija", "Radana", "Viktoria Zoe Ronja", "Radka", "Viktorie Hana", "Rafaela", "Violet", "Živa", "Vivienne", "Luisa", "Yasmin", "Lujza", "Livia Jane", 
                    "Michelle", "Zixin", "Regina", "Zoie", "Marta Rose", "Neli", "Mína", "Nella Sofia", "Rosálie", "Sula", "Rosario Vera", "Majdalena", "Rosie Elaine Antoinette",
                    "Malika", "Róza", "Maya", "Lydia", "Manon", "Rozálie Anna", "Melani", "Rozálie Katrin-Adriana", "Lilith", "Rozana", "Temari", "Minh Tue", "Terez", 
                    "Minja", "Tereza Anna", "Rozárka Anna", "Margad", "Ruxandra", "Tess", "Růžena", "Thao Nhi", "Růžena Amálie", "Natália", "Růžena Michaela", "Theresa",
                    "Madonna Mia", "Thu Thao", "Safieh", "Thuy Han", "Letty Dominika Anna", "Mariam", "Samanta Alexandra", "Tram Anh", "Samantha", "Tran Uyen Nhi", "Miroslava",
                    "Tu Anh", "Sara", "Týna", "Libuše", "Uljana", "Sára Anna", "Václava", "Sára Bára", "Leontýna Lena", "Sara Elizabeth", "Maria-Sofia", "Sára Helen",
                    "Valerie Zora", "Misheel", "Marie Eleonor", "Sara Maria", "Vanesa Miluše", "Mishell", "Marie Lussy", "Saruul", "Natalie Ella", "Saša",
                    "Veronika Hana", "Senta", "Mariela", "Shujie", "Vika", "Mišel", "Viktória", "Miya", "Nataša", "Lída", "Viktorie Anna", "Sisi", "Viktorie Sára", "Magdaléna Klára",
                    "Nava", "Mai Anh", "Vitkorie", "Sofie Anna", "Mariko Dobra", "Sofie Marta", "Vizma", "Sofija", "Yalguun", "Solomia", "Yi Chen", "Linda Hedvika", "Yveta",
                    "Monika Magdalena", "Zdeňka", "Sophia Bella", "Marion", "Sophie", "Nela Julie", "Sophie Ella", "Zoé", "Sophie Izabella", "Zoja", "Stanislava", "Markéta Elen",
                    "Stefanie", "Livie", "Maja", "Zuzana Vlastislava", "Maja Jana", "Stella Marie", "Sára Jana", "Žofie Ella", "Razan", "Rea", "Isabella Thea", "Astrid",
                    "Josefine Anna", "Aurélia", "Christine", "Deborah", "Jasmina Lucila", "Ada", "Annamarie", "Amálie Jarmila", "Le Gia Han", "Dita", "Bianca", "Amálie Mary",
                    "Jana Michaela", "Dominika Noemi", "Adina Mona", "Dora", "Annabel", "Amálie Mia", "Kha Han", "Aurora", "Laila", "Dorothea", "Charlotte Marie", "Apollinaria",
                    "Berenika Adéla", "Eda", "Bibiana", "Aylin", "Ivanna", "Amanda Krasimirova", "Jasmin", "Amáta", "Celestýna", "Eleanor Melita", "Anna Maria", "Ameli Alexandra",
                    "Anna Rozálie", "Amelia", "Karla Astra", "Adriana Daniela", "Dagmar", "Eli", "Klaudia", "Babeta", "Kristýna Charlotte", "Elif", "Antonie Amma", "Elin",
                    "Angelina", "Elisa", "Chloe", "Amélie Helen", "Alisa", "Elisabeth Sophia", "Iris", "Elisabetta", "Isabela Leia", "Elishka", "Isabella Leontýna", "Elissa",
                    "Alena Mia", "Amelie Melanie", "Anna Eliška", "Eliška Kateřina", "Brigita Marie", "Eliška Věra", "Jasmina", "Elizabet", "Jasmine Magdy", 
                    "Badamiyankhua", "Jenovéfa Zdena", "Elizabeth Constance", "Jitka", "Elizaveta", "Josefa", "An Vy", "Jozefína", "Ella Dominika", "Julie Vlasta",
                    "Ella May", "Karin", "Bao An", "Annabeth Lada", "Lenka Bronislava", "Kateřina Eva", "Bao Anh", "Ketrin", "Elsa", "Kira", "Anabella", "Kornelia",
                    "Ema Elen", "Amália", "Ema Julie", "Kvetana", "Ema Lily", "Alessandra", "Ema Maria", "Lavinia Jadranka",  "Emilia", "Charlotta Květa", "Emília", 
                    "Charlotte Anastázie", "Emilie", "Chenyue", "Anabelle", "Chloé", "Emílie Anna", "Benedikta", "Emílie Inna", "Ilona Veronika", "Emilly", "Běta", 
                    "Aranka Sophia", "Irma", "Anastasia Vitalijivna", "Bianca Věra", "Emma Charlotee", "Isabela Marlen", "Emma Marie", "Bodi-Goo", "Emma Milena", 
                    "Isabella Maria", "Emma Victoria", "Briana", "Barbara", "Ivanka", "Adéla Darina", "Brigita", "Ester Sofie", "Anna Elizabeth", "Anastázie Gao",
                    "Janika", "Eva Anna", "Arina Bogdanovna", "Eva Lilian", "Jasmin Michaela", "Adél", "Anna Charlotte", "Alexandra Julie", "Jasmine", "Agnes", "Jenifer",
                    "Evelína Anna", "Jenovéfa Marie", "Adina Ester", "Aloisie", "Evženie", "Constance Marie", "Fiorella", "Anna Kateřina", "Florentýna", "Cornélie", "Floriana",
                    "Anna Pavla", "Ágnes", "Jovana", "Antonie Eva", "Judita", "Lena", "Julie Augusta", "Ariana Mia", "Anna Tara", "Gaia", "Kara", "Giorgianna", "Cristina", "Bedřiška",
                    "Karla Josefína", "Goo-Egshig", "Katarina", "Greta", "Kateřina Elizabeth", "Gréta", "Kathrina", "Ha Chi",  "Katrin", "Arina", "Keyadhini", "Hadicha", "Kieu Trang", 
                    "Anette", "Anne Maria", "Hanele", "Annemari", "Hanka", "Krisia", "Hanna", "Daneliya", "Háta", "Annemarie", "Běla", "Květa", "Adelia", "Annie", "Bella", "Lala Gabriela", 
                    "Angela", "Dara", "Hoai An", "Lauren", "Huyen My", "Layla Violett", "Charlota", "Darina", "Charlotta", "Anu", "Aneta Marie", "Gabriela Ellie", "Elli", "Ellie", "Šanel");
   
     $zena_prijmeni = array ("Nováková", "Novák", "Svobodová", "Novotná", "Dvořáková", "Dvořák", "Černá", "Procházková", "Procházka", "Kučerová", "Veselá", "Horáková", "Horák",
                     "Marková", "Němcová", "Pokorná", "Pospíšilová", "Hájková", "Králová", "Jelínková", "Hájek", "Král", "Růžičková", "Benešová", "Fialová", "Sedláčková", "Doležalová", "Zemanová",
                     "Sedláček", "Kolářová", "Navrátilová", "Čermáková", "Kolář", "Navrátil", "Čermák", "Vaňková", "Urbanová", "Kratochvílová", "Blažková", "Šimková", "Křížová", "Kopecká", 
                     "Kovářová", "Vlčková", "Bartošová", "Poláková", "Kovář", "Konečná", "Polák", "Malá", "Musilová", "Čechová", "Staňková", "Štěpánková", "Holubová", "Kadlecová", "Dostálová",
                     "Šťastná", "Štěpánek", "Soukupová", "Dostál", "Marešová", "Sýkorová", "Moravcová", "Valentová", "Vávrová", "Tichá", "Matoušková", "Říhová", "Bláhová",
                     "Machová", "Mašková", "Ševčíková", "Vávra", "Burešová", "Šmídová", "Krejčová", "Bláha", "Dušková", "Pavlíková", "Jandová", "Hrušková", "Hrubá", "Havlíčková",
                     "Beranová", "Vacková", "Lišková", "Bednářová", "Tomanová", "Žáková", "Bártová", "Vítková", "Macháčková", "Tůmová", "Kašparová", "Sedláková", "Jarošová", "Bednář",
                     "Janečková", "Macháček", "Müllerová", "Bárta", "Stejskalová", "Žák", "Pešková", "Fišerová", "Švecová", "Matějková", "Sedlák", "Bílková", "Ježková", "Slavíková",
                     "Strnadová", "Krausová", "Němečková", "Horváth", "Beránková", "Horáčková","Brožová", "Kubíčková", "Beránek", "Havlová", "Horváthová", "Horáček", "Petrová",
                     "Tesařová", "Prokopová", "Klímová", "Pavelková", "Pavlíčková", "Havlíková", "Suchá", "Čížková", "Kohoutová", "Součková", "Šulcová", "Janoušková", "Tomášková",
                     "Straková", "Špačková", "Kovaříková", "Havelková", "Pechová", "Adámková", "Kozáková", "Zapletalová", "Daňková", "Filipová", "Richterová", "Urbánková", 
                     "Jedličková", "Hlaváčková", "Jeřábková", "Hanáková", "Urbánek", "Mrázková", "Kozák", "Přibylová", "Hanák", "Červenková", "Štěpánová", "Macková", "Vrbová",
                     "Adámek", "Tomášek", "Čapková", "Jeřábek", "Mrázek", "Bendová", "Hlaváček", "Stehlíková", "Málek", "Kubíková", "Málková", "Holá", "Štěpán", "Michálková",
                     "Nová", "Hladíková", "Vlková", "Klimešová", "Adamcová", "Hrdličková", "Linhartová", "Šrámková", "Volfová", "Dvořáčková", "Dvořáček", "Váňová", "Šindelářová",
                     "Řeháková", "Kováč", "Michálek", "Hlaváčová", "Turková", "Zelenková", "Vránová", "Šindelář", "Kováčová", "Brabcová", "Březinová", "Krátká", "Kořínková",
                     "Jílková", "Válková", "Davidová", "Šrámek", "Hlaváč", "Řehák", "Polášková", "Martínková", "Krátký", "Vrána", "Slámová", "Stránská", "Suchánková", 
                     "Váňa", "Nováčková", "Trnková", "Řezníčková", "Dlouhá", "Polášek", "Charvátová", "Hošková", "Sláma", "Suchánek", "Ptáčková", "Charvát", "Šímová", 
                     "Malíková", "Sládková", "Holečková", "Nosková", "Nováček", "Vašíčková", "Lukešová", "Ptáček", "Stránský", "Dohnalová", "Burianová", "Šebestová",
                     "Švarcová", "Hanušová", "Chaloupková", "Kroupová", "Gregorová", "Vašková", "Tomková", "Vodičková", "Poláček",  "Sládek", "Dufková", "Komárková", 
                     "Poláčková", "Sobotková", "Kubátová", "Vlachová", "Komárek", "Kubát", "Jirásková", "Mrázová", "Válek", "Prokešová", "Kalinová", "Fojtíková", "Smetanová",
                     "Šedivá", "Slezáková", "Žáčková", "Zajíčková", "Mráz", "Peterková", "Kuchařová", "Stará", "Slezák", "Karásková", "Kopřivová", "Koudelková", "Přikrylová",
                     "Burdová", "Červená", "Pánková", "Sikorová", "Večeřová", "Žáček", "Majerová", "Vondráčková", "Karásek", "Mikešová", "Bartoňová", "Štěrbová", "Boháčová",
                     "Hofmanová", "Martinková", "Horká", "Vlasák", "Miková", "Ondráčková", "Petříková", "Vlasáková", "Šebková", "Adamová", "Vondráček", "Doleželová", "Ondráček", 
                     "Václavíková", "Dudová", "Kocourková", "Jindrová", "Bauerová", "Václavík", "Chalupová", "Pánek", "Radová", "Zajícová", "Koubová", "Kuncová", "Boháč", "Baláž", "Divišová",
                     "Trávníčková", "Kvapilová", "Uhlířová", "Berková", "Coufalová", "Čejková", "Minaříková", "Zahradníková", "Havránková", "Balážová", "Kulhánková", "Sivák", "Zavadilová",
                     "Hovorková", "Skálová", "Trávníček", "Jirásek", "Křížková", "Kulhánek", "Bartáková", "Havránek", "Vaníčková", "Franková", "Skála", "Žižková", "Smejkalová", "Bílá",
                     "Kubešová", "Marečková", "Křivánková", "Tučková",  "Francová", "Formánková", "Lukášová", "Hamplová", "Řezáčová", "Škodová", "Zatloukalová", "Drábková", "Vítová",
                     "Kočová", "Červinková", "Melicharová", "Šimonová", "Sklenářová", "Křivánek", "Kociánová", "Trojanová", "Uhrová", "Váchová", "Drábek", "Siváková", "Formánek", 
                     "Lukáš", "Koutná", "Pelikánová", "Hubáčková", "Dobešová", "Kocián", "Blahová", "Řezáč", "Hanzlíková", "Neumannová", "Hradilová", "Hubáček", "Barták", "Nečasová", 
                     "Morávková", "Kosová", "Sklenář", "Smolíková", "Kupková", "Šubrtová", "Gábor", "Martincová", "Matušková",  "Valová", "Pelikán", "Janíková", "Horová", "Vácha", 
                     "Kubová", "Kubínová", "Bočková", "Červeňák", "Kozlová", "Popelková", "Košťálová", "Kolaříková", "Samková", "Skalická", "Morávek", "Seidlová", "Veverková",
                     "Eliášová", "Vágnerová", "Pražáková", "Malinová", "Balogová", "Hudcová", "Vágner", "Červeňáková", "Houdková", "Jonášová", "Sochorová", "Košťál", "Čápová", 
                     "Janíčková", "Jonáš", "Šafránková", "Langová", "Lorencová", "Langerová", "Ryšavá", "Hrdinová", "Pekárková", "Eliáš", "Čáp", "Štefková", "Buchtová", "Šimůnková",
                     "Nejedlá", "Muchová", "Hloušková", "Kafková", "Bučková", "Gáborová", "Křenková", "Zelinková", "Pražák", "Pekárek", "Šafránek", "Chládková", "Vaculíková",
                     "Valášková", "Lacinová", "Průšová", "Plachá", "Valášek", "Hudečková", "Zemánková", "Maříková", "Králíková", "Zelená", "Šmejkalová", "Böhmová", "Nedvědová",
                     "Zemánek", "Vyskočilová", "Dočkalová", "Hynková", "Smutná", "Králík", "Barešová", "Polívková", "Vaněčková", "Kostková", "Šestáková", "Mikulová", "Holíková",
                     "Sojková", "Karasová", "Sokolová", "Pilařová", "Chládek", "Husáková", "Tománková", "Hlavatá", "Brázdová", "Doubková", "Kleinová", "Šesták",
                     "Menšíková", "Prchalová", "Demeterová", "Schneiderová", "Votavová", "Vernerová", "Plačková", "Homolová", "Hašková", "Tománek", "Koubková", "Koláčková",
                     "Hermanová", "Homolková", "Stuchlíková", "Matulová", "Ferencová", "Husák", "Klementová", "Vojtová", "Jakubcová", "Antošová", "Danielová", "Vondrová",
                     "Brázda", "Kotková", "Kohoutková", "Machalová", "Janovská", "Císařová", "Horvát", "Zbořilová", "Zimová", "Rašková", "Chovancová", "Bezděková", "Kudrnová",
                     "Slavíčková", "Čonková", "Koláček", "Šimečková", "Kašpárková", "Spáčilová", "Chytilová", "Kašpárek", "Dudková", "Vojáčková", "Hálová", "Ludvíková", "Slabá", "Čiháková",
                     "Maršálková", "Dolejšová", "Kouřilová", "Wolfová", "Schwarzová", "Zárubová", "Fraňková", "Poláchová", "Vojáček", "Smrčková", "Šnajdrová", "Fischerová", "Polách",
                     "Valešová", "Šafářová", "Vybíralová", "Záruba", "Hála", "Zámečník", "Gabrielová", "Hronová", "Víchová", "Slováková", "Spáčil", "Krejčíková", "Šolcová", "Krupičková",
                     "Míková", "Pospíchalová", "Zachová", "Mužíková", "Synková", "Chmelařová", "Javůrková", "Tomáš", "Zámečníková", "Pařízková", "Brožková", "Šafář",
                     "Švejdová", "Průchová", "Cibulková", "Tomášová", "Dubská", "Čihák", "Spurná", "Smékalová", "Bajerová", "Horvátová", "Landová", "Slovák", "Tomečková", "Maršálek",
                     "Műllerová", "Vargová", "Wagnerová", "Zikmundová", "Srbová", "Petrášová", "Černíková", "Kvasničková", "Chmelíková", "Šustrová", "Hladká", "Hromádková", "Jansová",
                     "Šafaříková", "Blechová", "Kantorová", "Bednaříková", "Pelcová", "Vančurová", "Knotková", "Sedlářová", "Studená", "Pazderová", "Žigová", "Táborská", "Svatošová",
                     "Vojtěchová", "Jašková", "Sovová", "Zíková", "Chmelová", "Švábová",  "Vondráková", "Štefanová", "Boušková", "Dobiášová", "Fridrichová", "Hrnčířová", "Kaplanová",
                     "Jančová", "Sedlář", "Šindlerová", "Trčková", "Švehlová", "Andrlová", "Heřmanová", "Jahodová", "Janková", "Šváb", "Andělová", "Pašková", "Gajdošová", "Kuželová",
                     "Příhodová", "Petráš", "Balcarová", "Dočekalová", "Jančíková", "Zlámalová", "Kyselová", "Mičková", "Mertová", "Dobiáš", "Langrová", "Provazníková", "Dvorská",
                     "Šišková", "Smolková", "Foltýnová", "Janáková", "Oláh", "Matoušová", "Vondrák", "Zítková", "Bobková", "Hrbková", "Suková", "Volková", "Volná", 
                     "Fučíková", "Drozdová", "Hendrychová", "Pátek", "Bradáčová", "Houšková", "Hradecká", "Bradáč", "Medková", "Janák", "Brychtová", "Kuklová", "Zlámal", "Kotlár",
                     "Dubová", "Junková", "Třísková", "Kloudová", "Michalová", "Táborský", "Kalousová", "Pavlová", "Karlíková", "Skalová", "Matyášová", "Lacková", "Pátková",
                     "Janáčková", "Pecková", "Hrůzová", "Dobrovolná", "Skřivánková", "Schejbalová", "Odehnalová", "Jurečková", "Kurková", "Černochová", "Fenclová", "Kindlová",
                     "Matyáš", "Jiráková", "Kopečná", "Boudová", "Hejlová", "Dokoupilová", "Holcová", "Rezková", "Petrák", "Vojtíšková", "Skácelová", "Skřivánek", "Petráková",
                     "Kudláček", "Hoffmannová", "Rybová", "Hajná", "Hrabalová", "Kudláčková", "Pecháčková", "Maršíková", "Hanzlová", "Chvojková", "Stoklasová", "Koukalová",
                     "Hanusová", "Oláhová", "Smolová", "Kováříková", "Loudová", "Hartmanová", "Schmidtová", "Čadová", "Vorlová", "Jakešová", "Kovářík", "Rudolfová", "Formanová",
                     "Kasalová", "Albrechtová", "Jurková", "Ambrožová", "Mlčochová", "Peřinová", "Brejchová", "Motyčková", "Zouharová", "Kotlárová", "Pecháček", "Hejdová",
                     "Strouhalová", "Orságová", "Machačová", "Cihlářová", "Orság", "Slaninová", "Zavřelová", "Polanská", "Stárková", "Kazdová", "Bartošková", "Krčmářová",
                     "Teplá", "Ulrichová", "Peštová", "Skácel", "Skopalová", "Fořtová", "Klečková", "Řehořová", "Janáček", "Hušková", "Šlechtová", "Fiedlerová",
                     "Bergerová", "Kopečková", "Horská", "Pekařová", "Peterová", "Šedová", "Tóthová", "Korbelová", "Neubauerová", "Vrabcová", "Nagyová", "Vejvodová",
                     "Machálková", "Cihlář", "Hejduková", "Jirák", "Doubravová", "Pavlasová", "Sadílková", "Václavková", "Krčmář", "Suchomelová", "Švandová",
                     "Najmanová", "Rybářová", "Horňák", "Raková", "Machálek", "Prášková", "Máchová", "Michalcová", "Sekaninová", "Komínková", "Ondrová", "Černohorská",
                     "Široká", "Zdražilová", "Myšková", "Hrbáčková", "Palečková", "Prošková", "Stárek", "Fantová", "Chvátalová", "Ottová", "Kadeřábek", "Chvátal", 
                     "Pohlová", "Matějíčková", "Voříšková", "Kadeřábková", "Prášek", "Voráčková", "Weissová", "Bečvářová", "Zichová", "Šandová", "Zoubková",
                     "Filová", "Picková", "Hurtová", "Píšová", "Slováčková", "Lebedová", "Maňáková", "Rybář", "Koudelová", "Karlová", "Nguyenová", "Humlová",
                     "Bittnerová", "Mácha", "Jánská", "Večerková", "Maňák", "Horníková", "Kaňková", "Zahrádková", "Mládková", "Horňáková", "Žídková", "Mlejnková",
                     "Jiroušková", "Šípková", "Bernardová", "Buriánková", "Hrbáček", "Fuksová", "Slováček", "Ferková", "Skořepová", "Šimáčková", "Jírová",
                     "Vorlíčková", "Kalousková", "Šplíchalová", "Richtrová", "Fuchsová", "Michalíková", "Bartůňková", "Bečvář", "Hlávková", "Bínová",
                     "Vysloužilová", "Papežová", "Sladká", "Václavek", "Roubalová", "Buriánek", "Janatová", "Mládek", "Hanková", "Kunešová", "Vrzalová",
                     "Dunková", "Floriánová", "Rozsypalová", "Florián", "Holoubková", "Boháčková", "Zábranská", "Boučková", "Hrubešová", "Zezulová",
                     "Boháček", "Fabiánová", "Peroutková", "Zábranský", "Řeháčková", "Jechová", "Matysová", "Hůlková", "Šteflová", "Hanousková", 
                     "Mirgová", "Berkyová", "Pivoňková", "Šenková", "Truhlářová", "Klimentová", "Weberová", "Zahrádka", "Zálešáková");
      if ($pohlavi == "M") {
         $vysledek = $muz_prijmeni[random_int(0, count($muz_prijmeni)-1)]." ".$muz_jmeno[random_int(0, count($muz_jmeno)-1)];
      } else {
         $vysledek = $zena_prijmeni[random_int(0, count($zena_prijmeni)-1)]." ".$zena_jmeno[random_int(0, count($zena_jmeno)-1)];
      }
      $sql1 = "INSERT INTO jmena (stravaId, jmeno) VALUES (".$stravaId.", '".$vysledek."')";
      $result1 = mysqli_query($conn1, $sql1);
  }
  //mysql_close($conn1);

  return $vysledek; 
}

function predikujVysledek($id_user, $id_etapa, $id_akce, $k, $typ) {
  // typ:   "A" - průměr k nejbližších, "P" - procentuální průměr k nejbližších
  include "inc/MySQL.inc";

  $cas = "00:00:00";

  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
  
  // načtění mých ujetých etap mimo tu hledanou
  $sql = "SELECT * FROM moje_data WHERE (id_akce=".$id_akce." AND id_jmeno=".$id_user." AND imputace='N')";
  $result = mysqli_query($conn, $sql);
  $pocetStrava = mysqli_num_rows($result);
  if ($pocetStrava > 0) {
    $pole_moje = array();
    while($a = mysqli_fetch_assoc($result)) {
      $pole_moje[$a["id_etapa"]] = timeToSeconds($a["cas"]);    // pole mých časů za etapu
    }
  }

  // načtení všech závodníků co jeli společné závody s hledanou etapou 
  $sql2 = "SELECT * FROM ucastnici WHERE ((ucastnici.stravaID IN (SELECT stravaID FROM `ucastnici` WHERE ((ucastnici.etapa IN (SELECT id_etapa FROM moje_data WHERE (id_akce=".$id_akce." AND id_jmeno=".$id_user." AND imputace='N'))) OR (ucastnici.etapa=".$id_etapa.")) AND (pohlavi='M' OR pohlavi='Z') GROUP BY stravaID HAVING COUNT(*) = (SELECT COUNT(*) FROM moje_data WHERE (id_akce=".$id_akce." AND id_jmeno=".$id_user." AND imputace='N'))+1 ORDER BY stravaID)) AND ((ucastnici.etapa IN (SELECT id_etapa FROM moje_data WHERE (id_akce=".$id_akce." AND id_jmeno=".$id_user." AND imputace='N'))) OR (ucastnici.etapa=".$id_etapa."))) ORDER BY stravaID,etapa";
  $result2 = mysqli_query($conn, $sql2);
  if (mysqli_num_rows($result2) > ($k-1)) {
    $pole_dat = array();
    while($b = mysqli_fetch_assoc($result2)) {
      $pole_dat[$b["stravaID"]][$b["etapa"]] = timeToSeconds($b["cas"]);  // pole časů za závodníků za etapu
    }
    $vysledek = 0;
    $vzdalenosti = array();
    $vzdalenostiSkutecne = array();

    foreach ($pole_dat as $strava => $etapa) {
      $vzdalenost = 0;
      foreach ($etapa as $cc => $casa) {
        if ($cc != $id_etapa) {
          if ($typ == "A") {
            $vzdalenost += pow($casa - $pole_moje[$cc], 2);
          } else {
            //$vzdalenost += (($casa - $pole_moje[$cc])/$pole_moje[$cc])*100;
            $vzdalenost += (($pole_moje[$cc] - $casa)/$casa)*100;
          }
        }  
      }
      if ($typ == "A") {
        $vzdalenost = sqrt($vzdalenost);
        $vzdalenosti[$strava] = $vzdalenost;
        $vzdalenostiSkutecne[$strava] = $vzdalenost;
      } else {
        $vzdalenost = $vzdalenost/$pocetStrava;
        $vzdalenosti[$strava] = sqrt(pow($vzdalenost,2));
        $vzdalenostiSkutecne[$strava] = $vzdalenost;
        
      }
   }                              
    asort($vzdalenosti);
  
            // Calculate the average of the K nearest neighbors
    $sum = 0;
    $count = 0;
    $sumWeight = 0;
    $keys = array_keys($vzdalenosti);
    $smycka = ($k < count($keys)) ? $k : count($keys);
    for ($m = 0; $m < $smycka; $m++) {
      $key = $keys[$m];
      if ($typ == "A") {
        //$sum += $pole_dat[$key][$id_etapa];
        $sum += $pole_dat[$key][$id_etapa] * ($vzdalenosti[$key] != 0 ? 1/$vzdalenosti[$key] : 0);
        $sumWeight += ($vzdalenosti[$key] != 0 ? 1/$vzdalenosti[$key] : 0);  
      } else {
        $sum += ($pole_dat[$key][$id_etapa] + (($pole_dat[$key][$id_etapa] / 100) * ($vzdalenostiSkutecne[$key]/100)));
      }
      $count++;
    }
    if ($typ == "A") {
      $vysledek = $sum / $sumWeight;
    } else {
      $vysledek = $sum / $count;
    }
    $cas = secondsToTime($vysledek);  		
  }
  mysqli_close($conn);

  ///// KNN výběr konec
  return $cas;
}

function predikujVysledekStrava($stravaID, $id_etapa, $id_akce, $k, $typ) {
  // typ:   "A" - průměr k nejbližších, "P" - procentuální průměr k nejbližších
  
  include "inc/MySQL.inc";
  $cas = "00:00:00";

  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
  
  // načtění mých ujetých etap mimo tu hledanou
  $sql = "SELECT * FROM ucastnici WHERE ((etapa IN (SELECT id FROM etapy WHERE (akce=".$id_akce." AND id<>".$id_etapa."))) AND stravaID=".$stravaID.")";
  $result = mysqli_query($conn, $sql);
  $pocetStrava = mysqli_num_rows($result);
  if ($pocetStrava > 0) {
    $pole_moje = array();
    while($a = mysqli_fetch_assoc($result)) {
      $pole_moje[$a["etapa"]] = timeToSeconds($a["cas"]);    // pole mých časů za etapu
    }
  }

  // načtení všech závodníků co jeli společné závody s hledanou etapou 
  $sql2 = "SELECT * FROM ucastnici WHERE (stravaID IN (SELECT stravaID FROM ucastnici WHERE ((etapa IN (SELECT etapa FROM ucastnici WHERE ((etapa IN (SELECT id FROM etapy WHERE akce=".$id_akce.")) AND stravaID=".$stravaID.") OR etapa=".$id_etapa.")) AND stravaID<>".$stravaID." AND imputace='N' AND (pohlavi IN ('M','Z'))) GROUP BY stravaID HAVING COUNT(stravaID)=".($pocetStrava+1)."))  AND (etapa IN ( SELECT etapa FROM ucastnici WHERE ((etapa IN (SELECT id FROM etapy WHERE akce=".$id_akce.")) AND stravaID=".$stravaID.") OR etapa=".$id_etapa.") )";
  $result2 = mysqli_query($conn, $sql2);
  if (mysqli_num_rows($result2) > ($k-1)) {
    $pole_dat = array();
    while($b = mysqli_fetch_assoc($result2)) {
      $pole_dat[$b["stravaID"]][$b["etapa"]] = timeToSeconds($b["cas"]);  // pole časů za závodníků za etapu
    }
    $vysledek = 0;
    $vzdalenosti = array();
    $vzdalenostiSkutecne = array();

    foreach ($pole_dat as $strava => $etapa) {
      $vzdalenost = 0;
      foreach ($etapa as $cc => $casa) {
        if ($cc != $id_etapa) {
          if ($typ == "A") {
            $vzdalenost += pow($casa - $pole_moje[$cc], 2);
          } else {
            //$vzdalenost += (($casa - $pole_moje[$cc])/$pole_moje[$cc])*100;
            $vzdalenost += (($pole_moje[$cc] - $casa)/$casa)*100;
          }
        }  
      }
      if ($typ == "A") {
        $vzdalenost = sqrt($vzdalenost);
        $vzdalenosti[$strava] = $vzdalenost;
        $vzdalenostiSkutecne[$strava] = $vzdalenost;
      } else {
        $vzdalenost = $vzdalenost/$pocetStrava;
        $vzdalenosti[$strava] = sqrt(pow($vzdalenost,2));
        $vzdalenostiSkutecne[$strava] = $vzdalenost;
        
      }
      //$vzdalenosti[$strava] = $vzdalenost;
   }                              
    asort($vzdalenosti);
            // Calculate the average of the K nearest neighbors
    $sum = 0;
    $count = 0;
    $sumWeight = 0;
    $keys = array_keys($vzdalenosti);
    $smycka = ($k < count($keys)) ? $k : count($keys);
    for ($m = 0; $m < $smycka; $m++) {
      $key = $keys[$m];
      if ($typ == "A") {
        //$sum += $pole_dat[$key][$id_etapa];
        $sum += $pole_dat[$key][$id_etapa] * ($vzdalenosti[$key] != 0 ? 1/$vzdalenosti[$key] : 0);
        $sumWeight += ($vzdalenosti[$key] != 0 ? 1/$vzdalenosti[$key] : 0);  
      } else {
        //$sum += ($pole_dat[$key][$id_etapa]/(100 + $vzdalenostiSkutecne[$key]))*100;
        $sum += ($pole_dat[$key][$id_etapa] + (($pole_dat[$key][$id_etapa] / 100) * ($vzdalenostiSkutecne[$key]/100)));
      }
      $count++;
    }
    //$vysledek = $sum / $count;
    if ($typ == "A") {
      $vysledek = $sum / $sumWeight;
    } else {
      $vysledek = $sum / $count;
    }
    $cas = secondsToTime($vysledek);  		
  }
  mysqli_close($conn);
  ///// KNN výběr konec
  return $cas;
}

function predikujVysledekStravaTP($stravaID, $id_etapa, $id_akce, $k, $typ) {
  // typ:   "A" - průměr k nejbližších, "P" - procentuální průměr k nejbližších
  
  include "inc/MySQL.inc";
  $cas = "00:00:00";

  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
  
  // načtění mých ujetých etap mimo tu hledanou
  $sql = "SELECT * FROM ucastnici WHERE ((etapa IN (SELECT id FROM etapy WHERE (akce=".$id_akce." AND id<>".$id_etapa."))) AND stravaID=".$stravaID.")";
  $result = mysqli_query($conn, $sql);
  $pocetStrava = mysqli_num_rows($result);
  if ($pocetStrava > 0) {
    $pole_moje = array();
    while($a = mysqli_fetch_assoc($result)) {
      $pole_moje[$a["etapa"]] = timeToSeconds($a["cas"]);    // pole mých časů za etapu
    }
  }

  // načtení všech závodníků co jeli společné závody s hledanou etapou 
  $sql2 = "SELECT * FROM ucastnici WHERE (stravaID IN (SELECT stravaID FROM ucastnici WHERE ((etapa IN (SELECT etapa FROM ucastnici WHERE ((etapa IN (SELECT id FROM etapy WHERE akce=".$id_akce.")) AND stravaID=".$stravaID.") OR etapa=".$id_etapa.")) AND stravaID<>".$stravaID." AND imputace='N' AND (pohlavi IN ('M','Z'))) GROUP BY stravaID HAVING COUNT(stravaID)=".($pocetStrava+1)."))  AND (etapa IN ( SELECT etapa FROM ucastnici WHERE ((etapa IN (SELECT id FROM etapy WHERE akce=".$id_akce.")) AND stravaID=".$stravaID.") OR etapa=".$id_etapa.") )";
  $result2 = mysqli_query($conn, $sql2);
  if (mysqli_num_rows($result2) > ($k-1)) {
    $pole_dat = array();
    while($b = mysqli_fetch_assoc($result2)) {
      $pole_dat[$b["stravaID"]][$b["etapa"]] = timeToSeconds($b["cas"]);  // pole časů za závodníků za etapu
    }
    $vysledek = 0;
    $vzdalenosti = array();
    $vzdalenostiSkutecne = array();

    foreach ($pole_dat as $strava => $etapa) {
      $vzdalenost = 0;
      foreach ($etapa as $cc => $casa) {
        if ($cc != $id_etapa) {
          if ($typ == "A") {
            $vzdalenost += pow($casa - $pole_moje[$cc], 2);
          } else {
            //$vzdalenost += (($casa - $pole_moje[$cc])/$pole_moje[$cc])*100;
            $vzdalenost += (($pole_moje[$cc] - $casa)/$casa)*100;
          }
        }  
      }
      if ($typ == "A") {
        $vzdalenost = sqrt($vzdalenost);
        $vzdalenosti[$strava] = $vzdalenost;
        $vzdalenostiSkutecne[$strava] = $vzdalenost;
      } else {
        $vzdalenost = $vzdalenost/$pocetStrava;
        $vzdalenosti[$strava] = sqrt(pow($vzdalenost,2));
        $vzdalenostiSkutecne[$strava] = $vzdalenost;
        
      }
   }                              
    asort($vzdalenosti);
            // Calculate the average of the K nearest neighbors
    $sum = 0;
    $count = 0;
    $sumWeight = 0;
    $keys = array_keys($vzdalenosti);
    $smycka = ($k < count($keys)) ? $k : count($keys);
    for ($m = 0; $m < $smycka; $m++) {
      $key = $keys[$m];
      if ($typ == "A") {
        //$sum += $pole_dat[$key][$id_etapa];
        $sum += $pole_dat[$key][$id_etapa] * ($vzdalenosti[$key] != 0 ? 1/$vzdalenosti[$key] : 0);
        $sumWeight += ($vzdalenosti[$key] != 0 ? 1/$vzdalenosti[$key] : 0);  
      } else {
        //$sum += ($pole_dat[$key][$id_etapa]/(100 + $vzdalenostiSkutecne[$key]))*100;
        $sum += ($pole_dat[$key][$id_etapa] + (($pole_dat[$key][$id_etapa] / 100) * ($vzdalenostiSkutecne[$key]/100)));
      }
      $count++;
    }
    if ($typ == "A") {
      $vysledek = $sum / $sumWeight;
    } else {
      $vysledek = $sum / $count;
    }
    $cas = secondsToTime($vysledek);  		
  }
  mysqli_close($conn);
  ///// KNN výběr konec
  return $cas;
}

function nactiMojeCasy($conn) {  //rozděláno
  
  $sql = "SELECT * FROM ucastnici WHERE (stravaID=".$_SESSION['stravaID']." AND stravaID>0 AND pohlavi='".$_SESSION['pohlavi']."')";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
         $sql2 = "SELECT * FROM mojedata WHERE (id_strava=".$row['stravaID']." AND id_etapa=".$row['etapa'].")";
         $result2 = mysqli_query($conn, $sql2);
         if (mysqli_num_rows($result) == 0) {
           $row2 = mysqli_fetch_assoc($result2);
  ////         $sql3 = "INSERT INTO moje_data (id_jmeno, id_etapa, id_strava, id_akce, cas, body, pohlavi, imputace) VALUES (".$_SESSION['userID'].", ".$row['stravaID'].", ".$row2["etapa"].", ".$_POST["id_akce"].", '".$casMuj."', ".$bodyMoje.", '".$pohlavi."', 'N')";
           $result3 = mysqli_query($conn, $sql3);
         }
    }
  }
}

function zapisLog($id_user, $id_sess, $conn) {
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }
  $sql = "INSERT INTO logapp (sessid, usrid, datin) VALUES ('".$id_sess."', '".$id_user."', NOW())";
  $result = mysqli_query($conn, $sql);
}

function aktualniHeslo($id_user,  $conn) {
  $apass = "";
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT pass FROM users WHERE id=".$id_user."";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
  		$row = mysqli_fetch_assoc($result);
  		$apass = $row["pass"];
  }
  return $apass; 
}

function zmenitHeslo($id_user,  $new_hash, $conn) {
  $apass = "";
  // Check connection
  if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "UPDATE users SET pass='".$new_hash."' WHERE id=".$id_user."";
  $result = mysqli_query($conn, $sql);

}

//////////////////////


function testExistenceDat() {
  include "inc/MySQL.inc";
  $conn = mysqli_connect($servername, $username, $password, $dbname); 
  $result = $conn->query("SHOW TABLES LIKE 'akce'");
  if($result->num_rows == 1) {
          //echo "Tables exists";
      }
  else {
     // echo "Create tables. Please wait....";
      $query = '';
      $sqlScript = file('dpwzcz7772.sql');
      foreach ($sqlScript as $line)	{
      	
      	$startWith = substr(trim($line), 0 ,2);
      	$endWith = substr(trim($line), -1 ,1);
      	
      	if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
      		continue;
      	}
      		
      	$query = $query . $line;
      	$pocet = 0;
        if ($endWith == ';') {
          mysqli_query($conn,$query) or die('<div>Problem v přístupu do databáze!!!!!</div>');
      		$query= '';		
      		$pocet++;
      	}
      }
  }
}
?>