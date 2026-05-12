<?php
require "config.php";

function checkemail(string $email, int $threshold) : bool{
    global $pdo;

    $query = "SELECT * FROM credenziali WHERE email = :em";
    $stm = $pdo->prepare($query);
    $stm -> bindParam(":em", $email);
    
    $stm -> execute();
    $ris = $stm -> fetchAll();

    if (count($ris) == $threshold) return true;
    else return false;     
}
?>