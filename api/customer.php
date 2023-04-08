<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/customer/{id}', function (Request $request, Response $response, array $args) {
   $id  = $args['id'];
   $conn = $GLOBALS['dbconn'];
   $sql = "select * from user where uid = ? ";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $id);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   while ($row = $result->fetch_assoc()) {
      array_push($data, $row);
   }
   $json = json_encode($data);
   $response->getBody()->write($json);
   return $response->withHeader('Content-Type', 'application/json');
});


// $app->get('/customer', function (Request $request, Response $response, array $args) {
//    $response->getBody()->write("get Customer");
//    return $response;
//    // $id  = $args['id'];
//    // $conn = $GLOBALS['dbconn'];
//    // $sql = "select * from user where uid = ? ";
//    // $stmt = $conn->prepare($sql);
//    // $stmt->bind_param("s", $id);
//    // $stmt->execute();
//    // $result = $stmt->get_result();
//    // $data = array();
//    // while ($row = $result->fetch_assoc()) {
//    //    array_push($data, $row);
//    // }
//    // $json = json_encode($data);
//    // $response->getBody()->write($json);
//    // return $response->withHeader('Content-Type', 'application/json');
// });