<?php
class NotFoundController{


    public function view($method,$params = []){
         echo("404");
         try {
            call_user_func([$this,$method],$params);
        } catch (Error $e) {
            require_once(__DIR__."/../views/404.php");
        }

        // Affichage de la vue
    }

    
}