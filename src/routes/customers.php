<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//create a route to get all customer from the database

$app->get('/api/customers', function(Request $request, Response $response){
  echo 'working';
});
