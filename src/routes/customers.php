<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//create a route to get all customer from the database

$app->get('/api/customers', function(Request $request, Response $response){
  $query= "SELECT * FROM customers";
  try {
    //get the db object
    $db = new db();
    //connect to db
    $db = $db->connect();

    $stmt = $db->query($query);
    $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($customers);

  } catch(PDOException $e){
    echo '{ "error" : { "text": ' . $e->getMessage() . '}';
  }
});

//get single customer from database

$app->get('/api/customer/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $query= "SELECT * FROM customers where id = $id";
  try {
    //get the db object
    $db = new db();
    //connect to db
    $db = $db->connect();

    $stmt = $db->query($query);
    $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    echo json_encode($customer);

  } catch(PDOException $e){
    echo '{ "error" : { "text": ' . $e->getMessage() . '}';
  }
});

//add new customer

$app->post('/api/customer/add', function(Request $request, Response $response){
  $first_name     = $request->getParam('first_name');
  $last_name     = $request->getParam('last_name');
  $phone           = $request->getParam('phone');
  $email            = $request->getParam('email');
  $address        = $request->getParam('address');
  $city              = $request->getParam('city');
  $state            = $request->getParam('state');
  $query = "INSERT INTO customers ( ";
  $query .= "first_name, last_name, phone, email, address, city, state )  ";
  $query .= " VALUES ( ";
  $query .= " :first_name, :last_name, :phone, :email, :address, :city, :state ";
  $query .= " ) ";
  try {
    //get the db object
    $db = new db();
    //connect to db
    $db = $db->connect();
    $stmt = $db->prepare($query);
    $stmt->bindParam(':first_name' , $first_name);
    $stmt->bindParam(':last_name' , $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state' , $state);

    $stmt->execute();
    echo '{ "notice": { "text": "Customer Added" }';

  } catch(PDOException $e){
    echo '{ "error" : { "text": ' . $e->getMessage() . '}';
  }
});


//update customer

  $app->put('/api/customer/update{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $first_name     = $request->getParam('first_name');
  $last_name     = $request->getParam('last_name');
  $phone           = $request->getParam('phone');
  $email            = $request->getParam('email');
  $address        = $request->getParam('address');
  $city              = $request->getParam('city');
  $state            = $request->getParam('state');

  $query = "UPDATE customers SET
    first_name = :first_name,
    last_name = :last_name,
    phone = :phone,
    email = :email,
    address = :address,
    city = :city,
    state = :state
    WHERE id = $id";

  try {
    //get the db object
    $db = new db();
    //connect to db
    $db = $db->connect();
    $stmt = $db->prepare($query);
    $stmt->bindParam(':first_name' , $first_name);
    $stmt->bindParam(':last_name' , $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state' , $state);

    $stmt->execute();
    echo '{ "notice": { "text": "Customer Updated" }';

  } catch(PDOException $e){
    echo '{ "error" : { "text": ' . $e->getMessage() . '}';
  }
}); //need to rethink this method as the only way it works is if all fields are updated
//would be better if only the ones that we want to change will be updated
//RETURN TO THIS

$app->delete('/api/customer/delete/{id}', function(Request $request, Response $response){
  $id = $request->getAttribute('id');
  $query= "DELETE FROM customers where id = $id";
  try {
    //get the db object
    $db = new db();
    //connect to db
    $db = $db->connect();

    $stmt = $db->prepare($query);
    $stmt->execute();
    $db = null;
    echo '{ "notice": { "text": "Customer Deleted" }';
  } catch(PDOException $e){
    echo '{ "error" : { "text": ' . $e->getMessage() . '}';
  }
});
