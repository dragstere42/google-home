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

//change to post and check how to use parameter part dieu / la soie
$app->get('/tcl-prochain-tram', function () use ($app) {
    $tcl_prochain_tram_url='http://www.tcl.fr/Me-deplacer/Itineraires/Mon-trajet?ItinDepart=Reconnaissance+-+Balzac%2C+Lyon+3eme+%28Arr%C3%AAt%29&valueItinDepart=StopArea%7C2035%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&valueItinDepartFavoris=StopArea%7Ctcl5560%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&ItinArrivee=Relais+Info+Service+TCL+Part+Dieu+Villette%2C+Lyon+3eme&valueItinArrivee=Site%7C1537%7CRelais+Info+Service+TCL+Part+Dieu+Villette%7CLyon+3eme%7C%7C%7C796432%7C2087617%7C4309%2150%211%3B&valueItinArriveeFavoris=Site%7Ctcl21850%7CRelais+Info+Service+TCL+Part+Dieu+Villette%7CLyon+3eme%7C%7C%7C796432%7C2087617%7Ctcl35659%2150%211%3B&radioTiming=DepartImm&DepartMinute=00&radioSens=HorPartir&radioOption=OptionArrivRapid&lancer_recherche=Rechercher';

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

    echo $depart;
    echo $arrivee;
    echo $type;
    exit;

    // change the retrun for google home

    $result = array("hello" => 'hello micka');
    return $app->json($result, 200);

});



$app['debug'] = true;
$app->run();
