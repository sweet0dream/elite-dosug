<?php
    function db_connect() {
        global $db;
        return new PDODb([
            'type' => 'mysql',
            'username' => $db['user'], 
            'password' => $db['password'],
            'dbname'=> $db['name'],
            'charset' => 'utf8'
        ]);
    }