<?php
    function db_connect() {
        global $db;
        return new PDODb([
            'type' => 'mysql',
            'host' => '81.31.244.196',
            'username' => $db['user'], 
            'password' => $db['password'],
            'dbname'=> $db['name'],
            'charset' => 'utf8'
        ]);
    }