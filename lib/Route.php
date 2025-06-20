<?php

namespace Lib;

class Route
{
    private static $routes = [];


    # Registrar rutas para diferentes metodos HTTP
    public static function get($uri, $callback)
    {
        $uri = trim($uri, "/");
        self::$routes["GET"][$uri] = $callback;
    }
    public static function post($uri, $callback)
    {
        $uri = trim($uri, "/");
        self::$routes["POST"][$uri] = $callback;
    }
    public static function put($uri, $callback)
    {
        $uri = trim($uri, "/");
        self::$routes["PUT"][$uri] = $callback;
    }
    public static function delete($uri, $callback)
    {
        $uri = trim($uri, "/");
        self::$routes["DELETE"][$uri] = $callback;
    }

    # ejecutar rutas
    # se ejecuta cuando se llama a la ruta
    public static function dispatch()
    {   
        # Obtener la URI y el metodo HTTP
        $uri = $_SERVER["REQUEST_URI"];
        $uri = trim($uri, "/");

        $method = $_SERVER["REQUEST_METHOD"];

        foreach (self::$routes[$method] as $route => $callback) {

            if (strpos($route, ":") !== false) {
                $route = preg_replace('#:[a-zA-Z0-9]+#', '([a-zA-Z0-9]+)', $route);
            }
            # /tareas/([a-zA-Z0-9]+)

            # ^ tiene que empezar con la palabra
            # $ de fin a inicio tiene que ser igual que la palabra
            
            if (preg_match("#^$route$#", $uri, $matches)) {
                # $matches es un array "[0] => /usuarios/42 , [1] => 42]
                $params = array_slice($matches, 1);
                # Si es un metodo POST o PUT, obtener el cuerpo de la peticion
                if (in_array($method, ['POST', 'PUT'])) {
                    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
                    $body = strpos($contentType, 'application/json') !== false
                        ? json_decode(file_get_contents('php://input'), true)
                        : $_POST;

                    array_splice($params, 1, 0, [$body]);
                }



                if (is_callable($callback)) {
                    $response = $callback(...$params);
                }
                if (is_array($callback)) {
                    $controller = new $callback[0];
                    $response = $controller->{$callback[1]}(...$params);
                }

                if (is_array($response) || is_object($response)) {
                    header("Content-Type: application/json");
                    echo json_encode($response);
                } else {
                    echo $response;
                }
                return;
            }
        }

        echo "404 not found";
    }
}
