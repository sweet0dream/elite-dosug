<?php
    function db_connect() {
        global $db;

        $database = new PDODb([
            'type' => $db['type'] ?? 'mysql',
            'host' => $db['host'] ?? 'localhost',
            'username' => $db['user'], 
            'password' => $db['password'],
            'dbname'=> $db['name'],
            'charset' => $db['charset'] ?? 'utf8'
        ]);

        return $database;
    }