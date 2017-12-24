<?php
use Symfony\Component\HttpFoundation\Request;
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// Driver BDD
/*
$app->register(new Silex\Provider\DoctrineServiceProvider(),
    array('db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'     => '127.0.0.1',
        'user'     => 'root',
        'password' => '',
        'dbname' => 'polePosition'
    )));
*/
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
    if (!isset($_SERVER['PHP_AUTH_USER']))
    {
        header('WWW-Authenticate: Basic realm=naslyon');
        return $app->json(array('Message' => 'Not Authorised'), 401);
    }
    else
    {
        //once the user has provided some details, check them
        $users = array(
            'google-home' => 'Google_Home&NASLyon'
        );

        if($users[$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW'])
        {
            //If the password for this user is not correct then resond as such
            return $app->json(array('Message' => 'Forbidden'), 403);
        }

        //If everything is fine then the application will carry on as normal
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


// REST tcl prochain tram

//Check how to use parameter part dieu / la soie
$app->post('/tcl-prochain-tram', function (Request $request) use ($app) {
    $parameters=$request->request->get('parameters');

    $tcl_prochain_tram_url='http://www.tcl.fr/Me-deplacer/Itineraires/Mon-trajet?ItinDepart=Reconnaissance+-+Balzac%2C+Lyon+3eme+%28Arr%C3%AAt%29&valueItinDepart=StopArea%7C2035%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&valueItinDepartFavoris=StopArea%7Ctcl5560%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&ItinArrivee=Gare+SNCF+de+la+Part-Dieu%2C+Lyon+3eme&valueItinArrivee=Site%7C1468%7CGare+SNCF+de+la+Part-Dieu%7CLyon+3eme%7C%7C%7C796206%7C2087625%7C4326%2195%211%3B&valueItinArriveeFavoris=Site%7Ctcl20452%7CGare+SNCF+de+la+Part-Dieu%7CLyon+3eme%7C%7C%7C796206%7C2087625%7Ctcl35754%2195%211%3B&radioTiming=DepartImm&DepartMinute=00&radioSens=HorPartir&radioOption=OptionArrivRapid&lancer_recherche=Rechercher';
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

    $phrase1 = "Prochain ".$type." à ".$depart." arrivé à ".$arrivee;
    $phrase2 = " ".$typeSuivant." Suivant à ".$heureDepartSuivant." arrivé à ".$heureArriveeSuivant;

    $phrase = $phrase1.$phrase2;
    $phrase = str_replace("\t","",$phrase);
    $phrase = str_replace("\n","",$phrase);

    // change the retrun for google home
    $result = array("speech" => $parameters.$phrase,
        "displayText"=> $phrase
    );
    return $app->json($result, 200);

});



$app['debug'] = true;
$app->run();
