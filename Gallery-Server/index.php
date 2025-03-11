<?php 

// Define your base directory 
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base_dir_lower = strtolower($base_dir);
$request_lower = strtolower($request);

// Remove the base directory from the request if present (case-insensitive)
if (strpos($request_lower, $base_dir_lower) === 0) {
    $request = substr($request, strlen($base_dir));
}


if ($request == '') {
    $request = '/';
}
$apis = [
    '/login'         => ['controller' => 'UserController', 'method' => 'login'],
    '/register'    => ['controller' => 'UserController', 'method' => 'register']
];

if (isset($apis[$request])) {
    $controllerName = $apis[$request]['controller'];
    $method = $apis[$request]['method'];
    require_once "apis/v1/{$controllerName}.php";
    
    $controller = new $controllerName();
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo "Error: Method {$method} not found in {$controllerName}.";
    }
} else {
    echo "404 Not Found \r\n";
    echo "basedir " . $base_dir . "\r\n";
    echo "request " . $request . "\r\n";
}