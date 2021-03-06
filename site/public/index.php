<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Met en place l'environnement
require __DIR__ . '/../app/env.php';

// Récupère notre container
$container = require __DIR__ . '/../app/dependencies.php';
 // Set le container
AppFactory::setContainer($container());
// Création de l'app
$app = AppFactory::create();

require __DIR__ . '/../app/routes.php';

//Création du middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

session_start() ;

$app->run();
