<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

// Get all data label
// Example: localhost:8080/mstlabel/?key=123
$app->get("/mstlabel/", function (Request $request, Response $response){
    $sql = "SELECT * FROM mstlabel";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

// Get data label by id
// Example: localhost:8080/mstlabel/8213f4d9-b8f8-4ac4-948a-882e0cda0143?key=123
$app->get("/mstlabel/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "SELECT * FROM mstlabel WHERE id_label=:id_label";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id_label" => $id]);
    $result = $stmt->fetch();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

// Get data label by keyword search
// Example: localhost:8080/mstlabel/search/?key=123&keyword=Temp Steam
$app->get("/mstlabel/search/", function (Request $request, Response $response){
    $keyword = $request->getQueryParam("keyword");
    $sql = "SELECT * FROM mstlabel WHERE name LIKE '%$keyword%'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

// Post new data label
// Example: localhost:8080/mstlabel/?key=123
$app->post("/mstlabel/", function (Request $request, Response $response){
    $param = $request->getParsedBody();
    $sql = "INSERT INTO mstlabel 
            (
                id_label, 
                name, 
                unit, 
                create_by, 
                create_date
            ) VALUE 
            (
                :id_label, 
                :name, 
                :unit, 
                :create_by, 
                :create_date
            )";
    $stmt = $this->db->prepare($sql);
    $data = [
        ":id_label" => uniqid().uniqid(),
        ":name" => $param["name"],
        ":unit" => $param["unit"],
        ":create_by" => 1,
        ":create_date" => date("Y-m-d H:i:s")
    ];

    if($stmt->execute($data))
       return $response->withJson(["status" => "success", "data" => "1"], 200);
    
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

// Update data label by id
// Example: localhost:8080/mstlabel/5bed15b42dc255bed15b42dc2a?key=123
$app->put("/mstlabel/{id_label}", function (Request $request, Response $response, $args){
    $id_label = $args["id_label"];
    $param = $request->getParsedBody();
    $sql = "UPDATE mstlabel SET name=:name, unit=:unit, update_by=:update_by, update_date=:update_date WHERE id_label=:id_label";
    $stmt = $this->db->prepare($sql);
    var_dump($sql);
    
    $data = [
        ":id_label" => "$id_label",
        ":name" => $param["name"],
        ":unit" => $param["unit"],
        ":update_by" => 2,
        ":update_date" => date("Y-m-d H:i:s")
    ];

    if($stmt->execute($data))
       return $response->withJson(["status" => "success", "data" => "1"], 200);
    
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

// Delete data label by id
// Example: localhost:8080/mstlabel/search/?key=123&keyword=Temp Steam
$app->delete("/mstlabel/{id_label}", function (Request $request, Response $response, $args){
    $id_label = $args["id_label"];
    $sql      = "DELETE FROM mstlabel WHERE id_label =:id_label";
    $stmt     = $this->db->prepare($sql);
    
    $data = [
        ":id_label" => $id_label
    ];

    if($stmt->execute($data))
       return $response->withJson(["status" => "success", "data" => "1"], 200);
    
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});
