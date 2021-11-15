<?php

namespace app\core;

class Request {

    public function getPath() {
        //uzima url vrednost zahteva
        // npr iz...index.php/test?userId=5 $path="/test?userId=5"
        $path = $_SERVER["REQUEST_URI"] ?? "/";
        //izdvajamo string pre ?
        //taj string predstvalja kljuc pod kojim je ruta zapamcena
        //string nakon ? ako postoji predstavlja argumente te metode
        $position = strpos($path, "?");
        //ako nemamo argumente samo vrati path
        if($position === false) {
            //cita od karaktera 25 da bi preskocio SmartphoneMarket/public/
            $path = substr($path, 25);
            return $path;
        }
        //ako imamo vrati samo deo stringa do ?
        $path = substr($path, 25, $position-1);
        return $path;
    }

    //vrati da li je zahtev GET, PUT...
    public function getMethod() {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    public function getOne($key) {
        return $_REQUEST["$key"];
    }


    public function getAll() {
        return $_REQUEST;
    }

}