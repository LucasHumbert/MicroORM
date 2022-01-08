<?php
namespace hellokant\factory;

use PDO;

class ConnectionFactory
{
    static $pdo;

    static function makeConnection(array $conf){
        $server = $conf['host'];
        $dbname = $conf['database'];
        $user = $conf['username'];
        $password = $conf['password'];
        self::$pdo = new PDO("mysql:host=$server;dbname=$dbname", $user, $password, [PDO::ATTR_PERSISTENT=>true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES=> false, PDO::ATTR_STRINGIFY_FETCHES => false]);

        return self::$pdo;
    }

    static function getConnection(){
        return self::$pdo;
    }
}