<?php
namespace app\core;

use \PDO;

//Singleton klasa za cuvanje reference ka bazi
//Posto mi imamo bazu i svaki user ce u svakoj prilici imati samo jednu vezu ka njoj ovo je ok ovako
//ali da imamo vise baza sa kojima nam treba veza ili vise veza ka jednoj bazi, ovo ne bi radilo
class DatabaseConnection {
    private static $connection;

    private  function __construct() {}

    public static function &getConnection(): PDO {
        if (self::$connection === null) {
            self::$connection = new PDO("mysql:host=localhost;dbname=smartphone_market;charset=utf8", "root", "");
        }
        return self::$connection;
    }
}