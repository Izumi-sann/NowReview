<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location: ../frontend/forum.php");
    exit;
}

session_start();

if (!isset($_SESSION["uid"])){
    echo "Utente non autenticato";
    header("Location: ../frontend/login.html");
    exit;
}

require "config.php";

try{
    $uid = $_SESSION["uid"];
    $today = date("Y-m-d");
    $titolo = trim($_POST["titolo"] ?? "");
    $testo = trim($_POST["testo"] ?? "");
    $id_prodotto = $_POST["id_prodotto"] ?? null;

    if ($titolo === "" || $testo === "" || $id_prodotto === null){
        throw new ErrorException("Dati mancanti");
    }

    $pdo->beginTransaction();
    $ins = $pdo->prepare("INSERT INTO interazione (UID, data) VALUES (:uid, :data)");
    $ins->execute([":uid" => $uid, ":data" => $today]);
    $id = $pdo->lastInsertId();

    $stm = $pdo->prepare("INSERT INTO domanda (id_interazione, titolo, testo, id_prodotto) VALUES (:id, :titolo, :testo, :idp)");
    $stm->execute([":id" => $id, ":titolo" => $titolo, ":testo" => $testo, ":idp" => $id_prodotto]);

    $pdo->commit();
    echo "Domanda creata con successo";
    header("Location: ../frontend/forum.php");
    exit;
}
catch(PDOException $pdo_e){
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Errore nel database";
    header("Location: ../frontend/forum.php");
    exit;
}
catch(ErrorException $err){
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo $err->getMessage();
    header("Location: ../frontend/forum.php");
    exit;
}

?>
