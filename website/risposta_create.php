<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location: risposta.html");
    exit;
}

session_start();

if (!isset($_SESSION["uid"])){
    echo "Utente non autenticato";
    header("Location: login.html");
    exit;
}

require "config.php";

try{
    $uid = $_SESSION["uid"];
    $today = date("Y-m-d");
    $testo = trim($_POST["testo"] ?? "");

    if ($testo === ""){
        throw new ErrorException("Dati mancanti");
    }

    $pdo->beginTransaction();
    $ins = $pdo->prepare("INSERT INTO interazione (UID, data) VALUES (:uid, :data)");
    $ins->execute([":uid" => $uid, ":data" => $today]);
    $id = $pdo->lastInsertId();

    $stm = $pdo->prepare("INSERT INTO risposta (id_interazione, testo) VALUES (:id, :testo)");
    $stm->execute([":id" => $id, ":testo" => $testo]);

    $pdo->commit();
    echo "Risposta creata con successo";
    header("Location: risposta.html");
    exit;
}
catch(PDOException $pdo_e){
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Errore nel database";
    header("Location: risposta.html");
    exit;
}
catch(ErrorException $err){
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo $err->getMessage();
    header("Location: risposta.html");
    exit;
}

?>
