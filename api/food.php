<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/types', function (Request $request, Response $response, $args) {
   $conn = $GLOBALS['dbconn'];
   $sql = "SELECT * FROM type";
   $result = $conn->query($sql);
   $data = array();
   while ($row = $result->fetch_assoc()) {
      array_push($data, $row);
   }
   $json = json_encode($data);
   $response->getBody()->write($json);
   return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/typees', function (Request $request, Response $response, $args) {
   $conn = $GLOBALS['dbconn'];
   $body = $request->getBody();
   $bodyArr = json_decode($body, true);
   $sql = "SELECT * FROM food where type = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $bodyArr["type"]);
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

$app->get('/foods', function (Request $request, Response $response, $args) {
   $conn = $GLOBALS['dbconn'];
   $sql = "SELECT * FROM food";
   $result = $conn->query($sql);
   $data = array();
   while ($row = $result->fetch_assoc()) {
      array_push($data, $row);
   }
   $json = json_encode($data);
   $response->getBody()->write($json);
   return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/food/{id}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql = 'select * from food where fid=?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['id']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});


$app->get('/food/name/{name}', function (Request $request, Response $response, array $args) {
   $idx = '%' . $args['name'] . '%';
   $conn = $GLOBALS['dbconn'];
   $sql = 'select * from food where name like ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $idx);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = [];
   while ($row = $result->fetch_assoc()) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});

$app->post('/insertmenu', function (Request $request, Response $response, $args) {
   $json = $request->getBody();
   $jsonData = json_decode($json, true);
   $conn = $GLOBALS['dbconn'];
   $sql = 'INSERT INTO `food`(`fid`, `name`, `price`, `img`, `type`) VALUES (?,?,?,?,?)';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      'ssiss',
      $jsonData['fid'],
      $jsonData['name'],
      $jsonData['price'],
      $jsonData['img'],
      $jsonData['type']
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   if ($affected > 0) {
      $data = ["affected_rows" => $affected, "last_idx" => $conn->insert_id];
      $response->getBody()->write(json_encode($data));
      return $response
         ->withHeader('Content-Type', 'application/json')
         ->withStatus(200);
   }
});

$app->post('/updatestatus', function (Request $request, Response $response, $args) {
   $json = $request->getBody();
   $jsonData = json_decode($json, true);
   $status = 'ส่งแล้ว';
   $conn = $GLOBALS['dbconn'];
   $sql =
   'UPDATE `iorder`
   Set
   `status`=?
   where oid = ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      'ss',
      $status,
      $jsonData['oid']
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   if ($affected > 0) {
      $data = ["affected_rows" => $affected];
      $response->getBody()->write(json_encode($data));
      return $response
         ->withHeader('Content-Type', 'application/json')
         ->withStatus(200);
   }
});

$app->post('/updatemenu', function (Request $request, Response $response, $args) {
   $json = $request->getBody();
   $jsonData = json_decode($json, true);
   $conn = $GLOBALS['dbconn'];
   $sql = 'UPDATE `food` 
   SET
   `name`=?,
   `price`=?,
   `img`=?,
   `type`=? 
   where fid = ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      'sisss',
      $jsonData['name'],
      $jsonData['price'],
      $jsonData['img'],
      $jsonData['type'],
      $jsonData['fid']
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   if ($affected > 0) {
      $data = ["affected_rows" => $affected];
      $response->getBody()->write(json_encode($data));
      return $response
         ->withHeader('Content-Type', 'application/json')
         ->withStatus(200);
   }
});

$app->post('/deletemenu', function (Request $request, Response $response, $args) {
   $conn = $GLOBALS['dbconn'];
   $body = $request->getBody();
   $bodyArr = json_decode($body, true);
   $sql = "delete FROM food where fid = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $bodyArr["fid"]);
   $stmt->execute();
   return $response->withHeader('Content-Type', 'application/json');
});




$app->get('/cart}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql = 'select * from cart';
   $stmt = $conn->prepare($sql);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = [];
   while ($row = $result->fetch_assoc()) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});

$app->post('/insertcart', function (Request $request, Response $response, $args) {
   $json = $request->getBody();
   $jsonData = json_decode($json, true);
   $conn = $GLOBALS['dbconn'];
   $sql = 'INSERT INTO `cart`(`uid`, `food_id`,`amount`) VALUES (?,?,?)';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      'sss',
      $jsonData['uid'],
      $jsonData['food_id'],
      $jsonData['amount']
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   if ($affected > 0) {
      $data = ["affected_rows" => $affected, "last_idx" => $conn->insert_id];
      $response->getBody()->write(json_encode($data));
      return $response
         ->withHeader('Content-Type', 'application/json')
         ->withStatus(200);
   }
});

$app->post('/updatecart', function (Request $request, Response $response, $args) {
   $json = $request->getBody();
   $jsonData = json_decode($json, true);
   $conn = $GLOBALS['dbconn'];
   $sql = 'UPDATE `cart` 
   SET
   `amount`= ?
   where food_id = ?
   and uid = ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      'iss',
      $jsonData['amount'],
      $jsonData['food_id'],
      $jsonData['uid']
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   if ($affected > 0) {
      $data = ["affected_rows" => $affected];
      $response->getBody()->write(json_encode($data));
      return $response
         ->withHeader('Content-Type', 'application/json')
         ->withStatus(200);
   }
});

$app->get('/cart/{uid}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   'select fid,food.name,food.price,food.img,amount 
            from cart,food 
            where food.fid = cart.food_id and uid = ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['uid']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});

// $app->get('/cartamount/{uid}/{food}', function (Request $request, Response $response, array $args) {
//    $conn = $GLOBALS['dbconn'];
//    $sql = 'SELECT food.fid, food.name, food.price, food.img, cart.amount 
//             FROM cart 
//             INNER JOIN food ON food.fid = cart.food_id 
//             WHERE cart.uid = ?
//             AND cart.food_id = ?';
//    $stmt = $conn->prepare($sql);
//    $stmt->bind_param('ss', $args['uid'], $args['food']);
//    $stmt->execute();
//    $result = $stmt->get_result();
//    $data = array();
//    foreach ($result as $row) {
//       array_push($data, $row);
//    }
//    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//    return $response
//       ->withHeader('Content-Type', 'application/json; charset=utf-8')
//       ->withStatus(200);
// });



$app->post('/deletecart', function (Request $request, Response $response, $args) {
   $conn = $GLOBALS['dbconn'];
   $body = $request->getBody();
   $bodyArr = json_decode($body, true);
   $sql = "DELETE FROM `cart` WHERE uid = ? AND food_id = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      "ss",
      $bodyArr["uid"],
      $bodyArr["food_id"]
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   $data = ["affected_rows" => $affected];
   $response->getBody()->write(json_encode($data));
   return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
});

$app->post('/deletecartAll', function (Request $request, Response $response, $args) {
   $conn = $GLOBALS['dbconn'];
   $body = $request->getBody();
   $bodyArr = json_decode($body, true);
   $sql = "DELETE FROM `cart` WHERE uid = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      "s",
      $bodyArr["uid"],
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   $data = ["affected_rows" => $affected];
   $response->getBody()->write(json_encode($data));
   return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
});



$app->get('/cartsumprice/{uid}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   'SELECT SUM(sumtotal) as total FROM `sumprice` WHERE uid = ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['uid']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});



$app->get('/cartcount/{uid}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   'SELECT COUNT(*) as count FROM `cart` WHERE uid = ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['uid']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});




$app->get('/payment/{user}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   '  SELECT   cart.*,
                        food.name,
                        food.price,
                        (food.price*cart.amount) as sumamount
                        -- smu(food.price*cart.amount) as sumer

               FROM `cart`,food 
               WHERE cart.food_id = food.fid
               and   uid = ?
               -- group by cart.uid
               
               
               ';
            
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['user']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});


// $app->post('/insertIorder', function (Request $request, Response $response, $args) {
//    $conn = $GLOBALS['dbconn'];
//    $body = $request->getBody();
//    $bodyArr = json_decode($body, true);
//    $sql =
//    "INSERT INTO `iorder`( `cname`, `cphone`, `address`, `detail`, `totalPrice`, `time`, `status`, `cusid`,`cartSTR`) 
//    VALUES (?,?,?,?,?,?,?,?,?)";
//    $stmt = $conn->prepare($sql);
//    $stmt->bind_param(
//       "sssssssss",
//       $bodyArr["cname"],
//       $bodyArr["cphone"],
//       $bodyArr["address"],
//       $bodyArr["detail"],
//       $bodyArr["totalPrice"],
//       $bodyArr["time"],
//       $bodyArr["status"],
//       $bodyArr["cusid"],
//       $bodyArr["cartSTR"]
//    );
//    $stmt->execute();
//    $affected = $stmt->affected_rows;
//    // $data = ["affected_rows" => $affected];
//    if($affected > 0){
//       $data = ["oid" => $affected['cname']];
//    }
//    $response->getBody()->write(json_encode($data));
//    return $response
//       ->withHeader('Content-Type', 'application/json')
//       ->withStatus(200);
// });

$app->post('/insertIorder', function (Request $request, Response $response, $args) {
   $conn = $GLOBALS['dbconn'];
   $body = $request->getBody();
   $bodyArr = json_decode($body, true);
   $sql =
      "INSERT INTO `iorder`( `cname`, `cphone`, `address`, `detail`, `totalPrice`, `time`, `status`, `cusid`,`cartSTR`) 
   VALUES (?,?,?,?,?,?,?,?,?)";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      "sssssssss",
      $bodyArr["cname"],
      $bodyArr["cphone"],
      $bodyArr["address"],
      $bodyArr["detail"],
      $bodyArr["totalPrice"],
      $bodyArr["time"],
      $bodyArr["status"],
      $bodyArr["cusid"],
      $bodyArr["cartSTR"]
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;

   if ($affected > 0) {
      // Get the ID of the newly inserted row
      $last_id = $stmt->insert_id;
      $data = ["oid" => $last_id];
   } else {
      $data = ["error" => "Failed to insert row"];
   }
   $response->getBody()->write(json_encode($data));
   return $response
      ->withHeader('Content-Type', 'application/json')
      ->withStatus(200);
});


$app->get('/ioders/{user}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   ' SELECT * FROM `iorder` WHERE cusid = ? 
               ';

   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['user']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});

$app->get('/getcart_id/{user}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   ' SELECT cart_id FROM `cart` WHERE uid = ? 
               ';

   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['user']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});

$app->get('/getlistorder', function (Request $request, Response $response, $args) {
   $conn = $GLOBALS['dbconn'];
   $sql = "SELECT * FROM `iorder`";
   $result = $conn->query($sql);
   $data = array();
   while ($row = $result->fetch_assoc()) {
      array_push($data, $row);
   }
   $json = json_encode($data);
   $response->getBody()->write($json);
   return $response->withHeader('Content-Type', 'application/json');
});



$app->get('/getlistFoodorders/{user}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   "SELECT food.* FROM `food`,cart 
   WHERE food.fid = cart.food_id and cart.uid = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['user']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});

$app->get('/getlistFoodorders', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   "SELECT food.*,cartAmount.oid,cartAmount.amount FROM `food`,cartAmount 
   WHERE food.fid = cartAmount.food_id";
   $stmt = $conn->prepare($sql);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});



$app->get('/getlistcart/{user}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   ' SELECT * FROM `cart` WHERE uid = ? ';

   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['user']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});

$app->get('/removecartAll/{user}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   'DELETE FROM `cart` WHERE uid = ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s', $args['user']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});



// $app->post('/updatecart_oid', function (Request $request, Response $response, $args) {
//    $json = $request->getBody();
//    $jsonData = json_decode($json, true);
//    $conn = $GLOBALS['dbconn'];
//    $sql = 'UPDATE `cart` 
//    SET
//    `oid`=? 
//    where uid = ?';
//    $stmt = $conn->prepare($sql);
//    $stmt->bind_param(
//       'ss',
//       $jsonData['oid'],
//       $jsonData['uid']
//    );

//    $stmt->execute();
//    $affected = $stmt->affected_rows;
//    if ($affected > 0) {
//       $data = ["affected_rows" => $affected];
//       $response->getBody()->write(json_encode($data));
//       return $response
//          ->withHeader('Content-Type', 'application/json')
//          ->withStatus(200);
//    }
// });

// $app->post('/updatecart_oid', function (Request $request, Response $response, array $args) {
//    $body = $request->getBody();
//    $jsonData = json_decode($body, true);

//    $conn = $GLOBALS["dbconn"];
//    $sql = 'UPDATE `cart` 
//    SET `oid`= ? where uid = ?';
//    $stmt = $conn->prepare($sql);
//    $stmt->bind_param(
//       'ss',
//       $jsonData['oid'],
//       $jsonData['uid']
//    );
//    $stmt->execute();
//    $result = $stmt->get_result();


//    if ($result->num_rows > 0) {
//       $row = $result->fetch_assoc();
//       $value = array(
//          "cart_id" => $row["cart_id"],
//          "uid" => $row["uid"],
//          "oid" => $row["oid"]
//       );
//       $json = json_encode($value);
//       $response->getBody()->write($json);
//    } else {
//       $value = array("status" => 'notFound');
//       $json = json_encode($value);
//       $response->getBody()->write($json);
//    }
//    return $response->withHeader('content-type', 'application/json');
// });

// $app->post('/updatecart_oid', function (Request $request, Response $response, array $args) {
//    $body = $request->getBody();
//    $jsonData = json_decode($body, true);

//    $conn = $GLOBALS["dbconn"];
//    $sql = 'UPDATE `cart` 
//    SET `oid`= ? WHERE uid = ? AND oid IS NULL';
//    $stmt = $conn->prepare($sql);

//    $affected_rows = 0;
//    foreach ($jsonData['cart_ids'] as $cart_id) {
//       $stmt->bind_param(
//          'ss',
//          $jsonData['oid'],
//          $cart_id
//       );
//       $stmt->execute();
//       $affected_rows += $stmt->affected_rows;
//    }

//    if ($affected_rows > 0) {
//       $value = array("status" => 'success');
//       $json = json_encode($value);
//       $response->getBody()->write($json);
//    } else {
//       $value = array("status" => 'failed');
//       $json = json_encode($value);
//       $response->getBody()->write($json);
//    }
//    return $response->withHeader('content-type', 'application/json');
// });




$app->get('/getFoodname/{id}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   ' SELECT name FROM `food` WHERE fid = ? ';

   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s',  $args['id']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   foreach ($result as $row) {
      array_push($data, $row);
   }
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});

$app->get('/getcartOid/{oid}', function (Request $request, Response $response, array $args) {
   $conn = $GLOBALS['dbconn'];
   $sql =   ' SELECT * FROM `cart` WHERE oid = ? ';

   $stmt = $conn->prepare($sql);
   $stmt->bind_param('s',  $args['oid']);
   $stmt->execute();
   $result = $stmt->get_result();
   $data = array();
   // $totalAmount = 0; // initialize total amount to 0
   foreach ($result as $row) {
      array_push($data, $row);
   }
   // $data = array(
   //    'cart_id' => $row['cart_id'],
   //    'uid' => $row['uid'],
   //    'food_id' => $row['food_id'],
   //    'amount' => $$row['amount'], // return the total amount instead of the individual amounts
   //    'oid' => $row['oid']
   // );
   $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
   return $response
      ->withHeader('Content-Type', 'application/json; charset=utf-8')
      ->withStatus(200);
});



// $app->post('/updatecartOID', function (Request $request, Response $response, $args) {
//    $json = $request->getBody();
//    $jsonData = json_decode($json, true);
//    $conn = $GLOBALS['dbconn'];
//    $sql = 'UPDATE `cart`
//    Set `oid`=? where uid = ?';
//    $stmt = $conn->prepare($sql);
//    $stmt->bind_param(
//       'ss',
//       $jsonData['oid'],
//       $jsonData['uid']
//    );
//    $stmt->execute();
//    $affected = $stmt->affected_rows;
//    if ($affected > 0) {
//       $data = ["affected_rows" => $affected];
//       $response->getBody()->write(json_encode($data));
//       return $response
//          ->withHeader('Content-Type', 'application/json')
//          ->withStatus(200);
//    }
// });

$app->post('/updatecartOID', function (Request $request, Response $response, $args) {
   $json = $request->getBody();
   $jsonData = json_decode($json, true);
   $conn = $GLOBALS['dbconn'];
   $sql =
   'UPDATE `cart`
   SET `oid` = ?
   WHERE `uid` = ?';
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      'ss',
      $jsonData['oid'],
      $jsonData['uid']
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   if ($affected > 0) {
      $sql = 'SELECT `cart_id`, `uid`, `food_id`,`amount`, `oid`
              FROM `cart`
              WHERE `uid` = ?';
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('s', $jsonData['uid']);
      $stmt->execute();
      $result = $stmt->get_result();
      $data = $result->fetch_assoc();
      $response->getBody()->write(json_encode($data));
      return $response
         ->withHeader('Content-Type', 'application/json')
         ->withStatus(200);
   }
});


$app->post('/pushcartAmount', function (Request $request, Response $response, $args) {
   $json = $request->getBody();
   $jsonData = json_decode($json, true);
   $conn = $GLOBALS['dbconn'];
   $sql = "INSERT INTO `cartAmount`( `cart_id`, `uid`, `food_id`, `amount`,`oid`) VALUES (?,?,?,?,?)";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param(
      'issis',
      $jsonData['cart_id'],
      $jsonData['uid'],
      $jsonData['food_id'],
      $jsonData['amount'],
      $jsonData['oid'],
   );
   $stmt->execute();
   $affected = $stmt->affected_rows;
   if ($affected > 0) {
      $data = ["affected_rows" => $affected];
      $response->getBody()->write(json_encode($data));
      return $response
         ->withHeader('Content-Type', 'application/json')
         ->withStatus(200);
   }
});