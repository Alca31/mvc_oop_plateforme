<?php
//class logger pour récupérer les bugs et éventuellement les enregistrer
 class Logger
{
    private $filepath;
    function __construct($filepath = "php://stdout")
    {
        $this->$filepath = $filepath;
    }
    function log(mixed $data): void
    {
        ob_start(); # démarre la capture du flux de sortie
        var_dump($data);
        $debug_str = ob_get_clean(); # capture le flux de sortie et l'efface
        file_put_contents($this->filepath, $debug_str);
    }
}

// $console = new Logger();
// $logger_ser = new Logger(__DIR__."/serv.log");

// $console->log("Hello console pendant l'execution");
// $logger_ser->log("logger dans le fichier serv.log");