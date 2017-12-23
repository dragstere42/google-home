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

// REST hello
$app->get('/hello', function () use ($app) {

    $result = array("hello" => 'hello micka');
    return $app->json($result, 201);

});



$app['debug'] = true;
$app->run();
