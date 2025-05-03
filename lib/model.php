<?php

function get_db(){
    $db = null;

    try{
        $db = new PDO('mysql:host=localhost;dbname=blogs_db', 'root','root');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        // notice how we THROW the exception. You can catch this in your controller code in the usual way
        throw new Exception("Something wrong with the database: ".$e->getMessage());
    }
    return $db;

}
