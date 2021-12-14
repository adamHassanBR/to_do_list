<?php
    $user = "root";
    $pass = "root";
    try 
    {
        $conn = new PDO('mysql:host=localhost;dbname=to_do_list', $user, $pass);
    }
    catch (PDOException $e) 
    {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
?>