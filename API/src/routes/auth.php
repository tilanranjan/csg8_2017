<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

include_once dirname(__FILE__) . '/../config.php';
use \Firebase\JWT\JWT;


$app->add(function($request, $response, $next) {
    $route = $request->getAttribute('route');
    $methods = [];
    //$decoded = JWT::decode($token, $secret, ['HS256']);
    if (!empty($route)) {
        $pattern = $route->getPattern();
        foreach ($this->router->getRoutes() as $route) {
            if ($pattern === $route->getPattern()) {
                $methods = array_merge_recursive($methods, $route->getMethods());
            }
        }
        //Methods holds all of the HTTP Verbs that a particular route handles.
    } else {
        $methods[] = $request->getMethod();
    }
    
    $response = $next($request, $response);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader("Access-Control-Allow-Headers", 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->add(new \Slim\Middleware\JwtAuthentication([
    "path" => "/",
    "passthrough" => ["/token","/admin/login","/hello","/gettoken","/other/login","/other/user","/image","/admin/scheduler","/order/test"],
    "secret" => "proleadsoftdev",
    "relaxed" => ["localhost", API_IP],
    "algorithm" => ["HS256"],
    "attribute" => "jwt",
    "error" => function ($request, $response, $arguments) {
        $data["response"] = 401;
        $data["status"] = "Unauthorized";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader("Access-Control-Allow-Headers", 'X-Requested-With, Content-Type, Accept, Origin')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

// $app->post('/token', function (Request $request, Response $response) {
//         $request_params =$request->getParams();
//         $response=verifyRequiredParams(array('username','password'),$request_params);
//             if($response['success']) {
//                 $db = new UserHandler();
//                 $userName = $request->getParam('username');
//                 $password = $request->getParam('password');
//                 $isValid = $db->isValidApiUser($userName, $password);
//                 if($isValid){  
//                 try {
//                     $auth = new AuthonticationService();
//                     $accessToken =$auth ->getToken($userName,$password);
//                     //$refreshToken =$auth ->getRefreshToken($userName,$password);
//                     echo json_encode([
//                         "token"      => $accessToken,
//                         //"refresh_token"      => $refreshToken,
//                         "user" => 'paigenine'
//                     ]);
//                 } catch (Exception $e) {
//                     echo json_encode($e);
//                 }
//             }else{
//                 $response['status'] = 0;
//                 $response['message'] = "Invalid user";
//                 echo json_encode($response);
//             }  
//         }else{
//             $response['status'] = 0;
//             echo json_encode($response['status']);
//         }  
// });

$app->get('/hello', function (Request $request, Response $response) {
    echo '{"msg": "hello"}';
});

function verifyRequiredParams($required_fields, $request_params) {
    $error = false;
    $error_fields = array();
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields[] = $field;
        }
    }
    if ($error) {
        // Required field(s) are missing or empty
        return array(
            'success' => false,
            'message' => 'Required field(s) ' . implode(', ', $error_fields) . ' is missing or empty'
        );
    }else{
        return array(
            'success' => true
        );
    }
}

$container = $app->getContainer();
$app->post("/gettoken", function ($request, $response, $args) use ($container){
    $request_params =$request->getParams();
    $response=verifyRequiredParams(array('apikey'),$request_params);
    // var_dump($response);exit;
        // if($response['success']) { 
        //     if($request->getParam('apikey') == APIKEY){
        /* Here generate and return JWT to the client. */
            $now = new DateTime();
            $expire = new DateTime("+100 minutes");
            $server = $request->getServerParams();
            $jti = '';
                $payload = [
                "iat" => $now->getTimeStamp(),
                "exp" => $expire->getTimeStamp(),
                "jti" => $jti,
                "sub" => "proleadsoft"
                ];
            $secret = "proleadsoftdev"; 
            //$jwt = JWT::encode($payload, $secret);  
            $token = JWT::encode($payload, $secret, "HS256");
            
            $responseData["token"] = $token;
            $responseData["expires"] = $expire->getTimeStamp();
            echo json_encode($responseData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    //     }else{
    //         $response['status'] = 0;
    //         $response['message'] = "Invalid Key";
    //         echo json_encode($response);
    //     }  
    // }else{
    //     $response['status'] = 0;
    //     echo json_encode($response['status']);
    // }  
});