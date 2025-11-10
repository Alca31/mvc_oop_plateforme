<?php
require_once(__DIR__."/../models/ProductModel.php");

class ProductController{

    public function view(string $method,array $params = []){
        // Je place la fonction call_user_func dans un try catch 
        // au cas une méthode inconnu est tapée dans l'URL
        try {
            call_user_func([$this,$method],$params);
        } catch (Error $e) {
        }
    }
   

    public function showAll(){

        // Récupération de tous les produits

        $productList[] = (new ProductModel)->getAllProducts();
        var_dump($productList);

        // Affichage de la vue
        require_once(__DIR__."/../views/allProducts.php");
    }

    public function showOne(array $params=[]){
        
        (new ProductModel())->createProduct("pc",500,"un ordinateur");
        $product[] = (new ProductModel)->getAProduct(["id"=>$params]);
        var_dump($product);
        // Affichage de la vue
        require_once(__DIR__."/../views/aProduct.php");
    }
}