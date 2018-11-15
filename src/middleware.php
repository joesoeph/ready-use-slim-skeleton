<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
// middleware untuk validasi api key
$app->add(function ($request, $response, $next) {
    
    $key = $request->getQueryParam("key");

    if(!isset($key)){
        return $response->withJson(["status" => "API Key required"], 401);
    }
    
    $sql = "SELECT * FROM mstemployee WHERE DeviceToken=:DeviceToken";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":DeviceToken" => $key]);
        
    if($stmt->rowCount() > 0){
        $result = $stmt->fetch();
        if($key == $result["DeviceToken"]){
        
            // update hit
            $sql = "UPDATE mstemployee SET Hit=Hit+1 WHERE DeviceToken=:DeviceToken";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":DeviceToken" => $key]);
            
            return $response = $next($request, $response);
        }
    }

    return $response->withJson(["status" => "Unauthorized"], 401);

});