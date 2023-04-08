<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/login/{id}', function (Request $request, Response $response, array $args) {
   $id  = $args['id'];
   $conn = $GLOBALS['dbconn'];
   $sql = "select * from `user` where uid = ? ";
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

$app->post('/login', function (Request $request, Response $response, array $args) {
   $body = $request->getBody();
   $bodyArr = json_decode($body, true);

   $conn = $GLOBALS["dbconn"];
   $sql = "select * from `user` where username = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $bodyArr["username"]);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows === 1) {
      $row = $result->fetch_assoc();
      $pwdInDB = $row["password"];
      if (password_verify($bodyArr["password"], $pwdInDB)) {
         $value = array(
            "status" => $row["status"],
         "uid" => $row["uid"], 
         "username" => $row["username"]);
         $json = json_encode($value);
         $response->getBody()->write($json);
      } else {
         $value = array("status" => 'fail');
         $json = json_encode($value);
         $response->getBody()->write($json);
      }
   } else {
      $value = array("status" => 'notFound');
      $json = json_encode($value);
      $response->getBody()->write($json);
   }
   return $response->withHeader('content-type', 'application/json');
});
