<?php

require "bootstrap.php";

use Slim\Http\Response;
use App\Controllers\WordController;

$configuration = ['settings' => ['displayErrorDetails' => true]];
$contenedor = new \Slim\Container($configuration);
$app = new \Slim\App($contenedor);

// http://localhost/pr1-encoding/back-end/word/1
$app->get(
    '/word/{id}',
    function ($request, $response) {
        /** @var Response $response */
        $userController = new WordController();
        return $response->withJson($userController->get($request));
    }
);

// http://localhost/pr1-encoding/back-end/word
$app->get(
    '/words',
    function ($request, $response) {
        /** @var Response $response */
        $userController = new WordController();
        return $response->withJson($userController->wordList());
    }
);

// http://localhost/pr1-encoding/back-end/word
$app->post(
    '/word',
    function ($request, $response) {
        /** @var Response $response */
        $userController = new WordController();
        return $response->withJson($userController->add($request));
    }
);

$app->run();
