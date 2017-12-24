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
    $result='{
  "speech": "test",
  "displayText": "tt",
  "data": {
    "google": {
      "expect_user_response": true,
      "is_ssml": true,
    }
  },
  "contextOut": [],
}';

    $google = array("expect_user_response"=> false,
                    "is_ssml"=>true);
    $data = array("google"=>$google);
    $result = array("speech" => 'hello micka from speech',
                    "displayText"=> 'Hello mika from displayText',
                    "data"=> $data,
                    "contextOut" => []
    );

    return $app->json($result, 200);

});


// REST hello
$app->get('/tcl-prochain-tram', function () use ($app) {
    $tcl_prochain_tram_url='http://www.tcl.fr/Me-deplacer/Itineraires/Mon-trajet?ItinDepart=Reconnaissance+-+Balzac%2C+Lyon+3eme+%28Arr%C3%AAt%29&valueItinDepart=StopArea%7C2035%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&valueItinDepartFavoris=StopArea%7Ctcl5560%7CReconnaissance+-+Balzac%7CLyon+3eme%7C%7C%7C798248%7C2087000&ItinArrivee=Relais+Info+Service+TCL+Part+Dieu+Villette%2C+Lyon+3eme&valueItinArrivee=Site%7C1537%7CRelais+Info+Service+TCL+Part+Dieu+Villette%7CLyon+3eme%7C%7C%7C796432%7C2087617%7C4309%2150%211%3B&valueItinArriveeFavoris=Site%7Ctcl21850%7CRelais+Info+Service+TCL+Part+Dieu+Villette%7CLyon+3eme%7C%7C%7C796432%7C2087617%7Ctcl35659%2150%211%3B&radioTiming=DepartImm&DepartMinute=00&radioSens=HorPartir&radioOption=OptionArrivRapid&lancer_recherche=Rechercher';

    $homepage = file_get_contents($tcl_prochain_tram_url);
    $dom = new DOMDocument;
    $dom->loadHTML($homepage);
    $trajet=$dom->getElementById('RESULT-trajet');

    $result = array("hello" => 'hello micka');
    return $app->json($result, 200);

});



$app['debug'] = true;
$app->run();
