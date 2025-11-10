<?php

require_once(__DIR__."/Router.php");

class App{
    public static function start(){
        /**
         * Récupère l'uri.
         */

        echo("App");
        $uri = $_SERVER["REQUEST_URI"];

        /**
         * Récupère un tableau des élements de l'uri en séparant
         * la string via le caractère '/'
         */
        $uri_elements = explode("/",$uri);
        var_dump($uri_elements);
        // Pour l'uri /product/show/3
        //$uri_elements  => ["","product","show","3"]
        
        $controllerName = $uri_elements[1] ?? "";
        
        $methodName = $uri_elements[2] ?? "index";
        
        //supprime les 3 premiers éléments pour ne conserver que les paramètres
        $params = array_splice($uri_elements,3); 
        // Pour l'uri /product/show/3
        //$params => ["3"];
        // Pour l'uri /product/show/3/4/5
        //$params => ["3","4","5"]
        echo("controller");
        var_dump($controllerName);
        echo("methode");
        var_dump($methodName);
        echo("param");
        var_dump($params);

       Router::getController($controllerName)->view($methodName,$params);
    }
}