<?php
// Je vais créer les routes /product/... j'ai donc besoin
// de controleur ProductController
require_once(__DIR__ . "/../controllers/ProductController.php");
require_once(__DIR__ . "/../controllers/NotFoundController.php");

class Router
{
    public static function getController(string $controllerName)
    {

        $controllerSite = [
            ""=> new NotFoundController(),
            "product" => new ProductController()
        ];

        return $controllerSite["$controllerName"] ?? $controllerSite["default"];
    }
}
