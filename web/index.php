<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
// Driver BDD
$app->register(new Silex\Provider\DoctrineServiceProvider(),
    array('db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'     => '127.0.0.1',
        'user'     => 'root',
        'password' => '',
        'dbname' => 'train'
    )));

// Config twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Config REST JSON
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

//authentification basic
$app->before(function() use ($app)
{
    $variables = parse_ini_file('.env');
    if (!isset($_SERVER['PHP_AUTH_USER']))
    {
        header('WWW-Authenticate: Basic realm='.$variables['BASIC_USER']);
        return $app->json(array('Message' => 'Not Authorised'), 401);
    }
    else
    {
        //once the user has provided some details, check them
        $users = array(
            'google-home' => $variables['BASIC_PASSWORD']
        );

        if($users[$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW'])
        {
            //If the password for this user is not correct then resond as such
            return $app->json(array('Message' => 'Forbidden'), 403);
        }

        //If everything is fine then the application will carry on as normal
    }
});

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

/////////////////////
//TODO

//"speech": "Barack Hussein Obama II was the 44th and current President of the United States.",
//"displayText": "Barack Hussein Obama II was the 44th and current President of th"
//"data": {...},
//"contextOut": [...],
//"source": "DuckDuckGo"


// REST hello
$app->post('/hello', function () use ($app) {

    $result = array("speech" => 'hello micka from speech',
                    "displayText"=> 'Hello mika from displayText'
                    );

    return $app->json($result, 200);

});


// REST tcl prochain tram PART DIEU / LA SOIE
$app->post('/tcl-prochain-tram', function (Request $request) use ($app) {
    $parameters=$request->request->get("result");
    $parameters = $parameters["parameters"];
    $direction = $parameters["direction"];

    if($direction == "part dieu"){
        $tcl_prochain_tram_url='http://www.tcl.fr/Me-deplacer/Itineraires/Mon-trajet?ItinDepart=Reconnaissance+-+Balzac%2C+Lyon+3eme+%28Arr%C3%AAt%29&valueItinDepart=StopArea%7C2035%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&valueItinDepartFavoris=StopArea%7Ctcl5560%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&ItinArrivee=Gare+SNCF+de+la+Part-Dieu%2C+Lyon+3eme&valueItinArrivee=Site%7C1468%7CGare+SNCF+de+la+Part-Dieu%7CLyon+3eme%7C%7C%7C796206%7C2087625%7C4326%2195%211%3B&valueItinArriveeFavoris=Site%7Ctcl20452%7CGare+SNCF+de+la+Part-Dieu%7CLyon+3eme%7C%7C%7C796206%7C2087625%7Ctcl35754%2195%211%3B&radioTiming=DepartImm&DepartMinute=00&radioSens=HorPartir&radioOption=OptionArrivRapid&lancer_recherche=Rechercher';
    }elseif($direction == "la soie"){
        $tcl_prochain_tram_url='http://www.tcl.fr/Me-deplacer/Itineraires/Mon-trajet?ItinDepart=Reconnaissance+-+Balzac%2C+Lyon+3eme+%28Arr%C3%AAt%29&valueItinDepart=StopArea%7C2035%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&valueItinDepartFavoris=StopArea%7Ctcl5560%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&ItinArrivee=Parc+Relais+TCL+Vaulx-en-Velin+La+Soie%2C+Vaulx-en-Velin&valueItinArrivee=Site%7C555%7CParc+Relais+TCL+Vaulx-en-Velin+La+Soie%7CVaulx-en-Velin%7C%7C%7C801132%7C2087766%7C2339%2177%211%3B&valueItinArriveeFavoris=Site%7Ctcl21549%7CParc+Relais+TCL+Vaulx-en-Velin+La+Soie%7CVaulx-en-Velin%7C%7C%7C801132%7C2087766%7Ctcl37170%2177%211%3B&radioTiming=DepartImm&DepartMinute=00&radioSens=HorPartir&radioOption=OptionArrivRapid&lancer_recherche=Rechercher';
    }else{
        $tcl_prochain_tram_url='http://www.tcl.fr/Me-deplacer/Itineraires/Mon-trajet?ItinDepart=Reconnaissance+-+Balzac%2C+Lyon+3eme+%28Arr%C3%AAt%29&valueItinDepart=StopArea%7C2035%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&valueItinDepartFavoris=StopArea%7Ctcl5560%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&ItinArrivee=Gare+SNCF+de+la+Part-Dieu%2C+Lyon+3eme&valueItinArrivee=Site%7C1468%7CGare+SNCF+de+la+Part-Dieu%7CLyon+3eme%7C%7C%7C796206%7C2087625%7C4326%2195%211%3B&valueItinArriveeFavoris=Site%7Ctcl20452%7CGare+SNCF+de+la+Part-Dieu%7CLyon+3eme%7C%7C%7C796206%7C2087625%7Ctcl35754%2195%211%3B&radioTiming=DepartImm&DepartMinute=00&radioSens=HorPartir&radioOption=OptionArrivRapid&lancer_recherche=Rechercher';
        $direction = "part dieu";
    }

    $homepage = file_get_contents($tcl_prochain_tram_url);

    $pos = strpos($homepage, 'class="depart"');
    $depart = substr($homepage,$pos);
    $pos = strpos($depart, "</span>");
    $depart = substr($depart,15,$pos-15);

    $pos = strpos($homepage, 'class="arrivee"');
    $arrivee = substr($homepage,$pos);
    $pos = strpos($arrivee, "</span>");
    $arrivee = substr($arrivee,0+16,$pos-16);


    $pos = strpos($homepage, 'TABLE-result-trajet');
    $images= substr($homepage,$pos);
    $pos = strpos($images, "</table>");
    $images = substr($images,0,$pos);

    $pos = strpos($images, '.png');
    $png= substr($images,$pos+4);
    $pos = strpos($png, ".png");
    $png= substr($png,0,$pos);

    $pos = strpos($png, 'src="');
    $png= substr($png,$pos+5);

    $splite = explode('/',$png);

    $type=$splite[sizeof($splite)-1];
    $depart = str_replace("h","heure",$depart);
    $arrivee = str_replace("h","heure",$arrivee);

    //depart suivant
    $pos = strpos($homepage, 'class="INFOS-depart-suivant"');
    $departSuivant = substr($homepage,$pos);
    $pos = strpos($departSuivant, "</div>");
    $departSuivant = substr($departSuivant,0,$pos);

    $pos = strpos($departSuivant, 'class="depart"');
    $heureDepartSuivant = substr($departSuivant,$pos);
    $pos = strpos($heureDepartSuivant, "</span>");
    $heureDepartSuivant = substr($heureDepartSuivant,15,$pos-15);

    $pos = strpos($departSuivant, 'class="arrivee"');
    $heureArriveeSuivant = substr($departSuivant,$pos);
    $pos = strpos($heureArriveeSuivant, "</span>");
    $heureArriveeSuivant = substr($heureArriveeSuivant,0+16,$pos-16);

    $heureDepartSuivant = str_replace("h","heure",$heureDepartSuivant);
    $heureArriveeSuivant = str_replace("h","heure",$heureArriveeSuivant);

    $pos = strpos($departSuivant, 'type-de-ligne');
    $typeSuivant = substr($departSuivant,$pos);
    $pos = strpos($typeSuivant, ".png");
    $typeSuivant = substr($typeSuivant,0,$pos);

    if(strpos($typeSuivant, 'TRA') != false){
        $typeSuivant = "TRAM";
    }elseif(strpos($typeSuivant, 'BUS') != false){
        $typeSuivant = "BUS";
    }else{
        $typeSuivant = "inconnu";
    }

    $phrase1 = "Prochain ".$type." direction ".$direction." ".$depart." arrivé à ".$arrivee;
    $phrase2 = " ".$typeSuivant." Suivant direction ".$direction. " ".$heureDepartSuivant." arrivé à ".$heureArriveeSuivant;

    $phrase = $phrase1.$phrase2;
    $phrase = str_replace("\t","",$phrase);
    $phrase = str_replace("\n","",$phrase);

    // change the retrun for google home
    $result = array("speech" => $phrase,
        "displayText"=> $phrase
    );
    return $app->json($result, 200);

});

// Set week-end AND ADD CLEAN --------------------------------------------------------------------
$app->post('/set_week_end', function () use ($app) {
    $now = strtotime("now");
    $end_date = strtotime("+40 days");

    while (date("Y-m-d", $now) != date("Y-m-d", $end_date)) {
        $day_index = date("w", $now);
        if ($day_index == 0 || $day_index == 5) {
            $heure = ' 18:00:00';
            if($day_index == 5){
                $gare_depart = 'lyon';
                $gare_arrivee = 'paris';
            }
            if($day_index == 0){
                $gare_depart = 'paris';
                $gare_arrivee = 'lyon';
            }
            $reqActif = "SELECT * FROM need_train WHERE datetime > :before AND datetime < :after";
            $isActif = $app['db']->fetchAll($reqActif, array('before' => date("Y-m-d", $now) . " 01:00:00", 'after' => date("Y-m-d", $now) . " 23:00:00"));

            if(sizeof($isActif) == 0) {
                $app['db']->insert('need_train', array(
                    'datetime' => date("Y-m-d", $now) . $heure,
                    'gare_depart' => $gare_depart,
                    'gare_arrivee' => $gare_arrivee,
                    'actif' => true
                ));
                var_dump(date("Y-m-d", $now));
            }
        }
        $now = strtotime(date("Y-m-d", $now) . "+1 day");
    }
    exit;
});

// Create an alert on date
$app->post('/create_date', function () use ($app) {

});

// Create an alert on date -> send link in email
$app->get('/change_status/{datetime}/{actif}', function (Request $request, $datetime, $actif) use ($app) {
    $reqActif = "SELECT * FROM need_train WHERE datetime =:now";
    $isActif = $app['db']->fetchAll($reqActif, array('now' => $datetime ));
    if(sizeof($isActif) > 0){
        $app['db']->update('need_train',
            array('actif' => $actif),
            array('datetime' => $datetime));
        return $app->json('', 200);
    }
    return $app->json('datetime not found', 500);

});


// Get train
$app->post('/train', function () use ($app) {

    $variables = parse_ini_file('.env');

    $smtp_host_ip = gethostbyname($variables['SMTP']);
    $transport = Swift_SmtpTransport::newInstance($smtp_host_ip, $variables['PORT'], $variables['ENCRYPTION'])
        ->setUsername($variables['USER'])
        ->setPassword($variables['PASSWORD']);
    $mailer = Swift_Mailer::newInstance($transport);

    $passenger_ids = 55107035;
    $cards_ids = 2586954;
    $token = "LG_XjKLCoLiZsztvnfhQ";
    $array[] = '';

    $now = date("Y-m-d H:i:s");
    $day = date("Y-m-d");
    $reqActif = "SELECT * FROM need_train WHERE datetime > :now AND notification != :day AND actif = TRUE ORDER BY datetime ASC";
    $actif = $app['db']->fetchAll($reqActif, array('now' => $now, 'day' => $day));

    if ($actif != null){
        foreach ($actif as $key => $value){
            $depart = str_replace(' ','T',$value['datetime']).'UTC';
            switch ($value['gare_depart']){
                case 'paris':
                    $gare_depart = 4924;
                    $nom_depart="paris-gare-de-lyon";
                    break;
                case 'lyon':
                    $gare_depart = 4676;
                    $nom_depart="lyon-part-dieu";
                    break;
                case 'massy':
                    $gare_depart= 1818;
                    $nom_depart="massy-tgv";
                    break;
            }
            switch ($value['gare_arrivee']){
                case 'paris':
                    $gare_arrivee = 4924;
                    $nom_arrivee = "paris-gare-de-lyon";
                    break;
                case 'lyon':
                    $gare_arrivee = 4676;
                    $nom_arrivee = "lyon-part-dieu";
                    break;
                case 'massy':
                    $gare_arrivee = 1818;
                    $nom_arrivee = "massy-tgv";
                    break;
            }
            $url = "https://www.trainline.fr/search/".$nom_depart."/".$nom_arrivee."/".substr(str_replace(' ','-',$value['datetime']),0,-3);
            $ch = curl_init("https://www.trainline.fr/api/v5_1/search");
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://www.trainline.fr/api/v5_1/search",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"search\":{\"departure_date\":\"".$depart."\",\"return_date\":null,\"cuis\":{},\"systems\":[\"sncf\",\"db\",\"idtgv\",\"ouigo\",\"trenitalia\",\"ntv\",\"hkx\",\"renfe\",\"benerail\",\"ocebo\",\"westbahn\",\"leoexpress\",\"locomore\",\"busbud\",\"flixbus\",\"distribusion\",\"city_airport_train\",\"timetable\"],\"exchangeable_part\":null,\"source\":null,\"is_previous_available\":false,\"is_next_available\":false,\"departure_station_id\":\"".$gare_depart."\",\"via_station_id\":null,\"arrival_station_id\":\"".$gare_arrivee."\",\"exchangeable_pnr_id\":null,\"passenger_ids\":[\"".$passenger_ids."\"],\"card_ids\":[\"".$cards_ids."\"]}}",
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json, text/javascript, */*; q=0.01",
                    "Accept-Encoding: gzip, deflate, br",
                    "Accept-Language: fr-FR,fr;q=0.8",
                    "Authorization: Token token=\"".$token."\"",
                    "Cache-Control: no-cache",
                    "Connection: keep-alive",
                    "Content-Length: 531",
                    "Content-Type: application/json; charset=utf-8",
                    "Cookie: _ga=GA1.2.1666471707.1518030342; _gid=GA1.2.375413982.1518030342; mobile=no; _uetsid=_uetd509d2ba",
                    "Host: www.trainline.fr",
                    "Postman-Token: 19ed6425-b400-58e2-dc02-869f37f7fd47",
                    "Referer: ".$url,
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0",
                    "X-CT-Client-Id: 3a27a0b0-b233-48f5-961b-e2c563566e34",
                    "X-CT-Locale: fr",
                    "X-CT-Timestamp: 1517993347",
                    "X-CT-Version: b678a32740bf5985de99ffc8f52579d1c7b84f46",
                    "X-Requested-With: XMLHttpRequest",
                    "X-User-Agent: CaptainTrain/1517993347(web) (Ember 2.18.0)"
                ),
            ));

            // Send the request
            $response = curl_exec($ch);

            // Check for errors
            if($response === FALSE){
                die(curl_error($ch));
            }
            $res = json_decode($response);
            $res1 = $res->folders;
            foreach($res1 as $key => $r){
                if($r->is_sellable == true) {
                    if (substr($r->departure_date, 0, -6) >= substr($depart, 0, -3) && $r->local_amount->subunit == 0 ) {
                        array_push($array, str_replace('T',' ',substr($r->departure_date, 0, -6)));
                    }
                }
            }
            if (sizeof($array) > 1) {
                $liste = '';
                foreach ($array as $a) {
                    $liste = $liste . $a . '<br />';
                }
                $sujet = 'Train disponible le: ' . $value['datetime'] . ' de ' . $nom_depart . ' a ' . $nom_arrivee;
                $body = $sujet . '\n' . 'Lien: ' . $url;
                $body = '
                                 <html>
                                  <head>
                                   <title>Train disponible le ' . $value['datetime'] . ' de ' . $nom_depart . ' a ' . $nom_arrivee . ' </title>
                                  </head>
                                  <body>
                                   <p>Train disponible le ' . $value['datetime'] . ' de ' . $nom_depart . ' a ' . $nom_arrivee . '</p>
                                   <a href=' . $url . '> Lien reservation</a>
                                   <p>
                                     ' . $liste . '
                                   </p>
                                  </body>
                                 </html>
                                 ';
                $message = Swift_Message::newInstance($sujet)
                    ->setFrom(array($variables['FROM'] => 'TRAIN'))
                    ->setTo(array($variables['TO'] => 'Michael'));
                $message->setBody($body, 'text/html');
                try {
                    $mailer->send($message);
                    $app['db']->update('need_train',
                        array('notification' => date("Y-m-d")),
                        array('id' => $value['id']));
                } catch (\Swift_TransportException $e) {
                    echo $e->getMessage();
                }
            }
            $array = '';
            $array[]= '';
        }
    }

    // Decode the response
    //$responseData = json_decode($array, TRUE);
    return $app->json($array, 200);

});

$app['debug'] = true;
$app->run();
