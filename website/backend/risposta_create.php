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
    $testo = trim($_POST["testo"] ?? "");
    $id_domanda = $_POST["id_domanda"] ?? null;

    if ($testo === "" || $id_domanda === null){
        throw new ErrorException("Dati mancanti");
    }

    $pdo->beginTransaction();
    $ins = $pdo->prepare("INSERT INTO interazione (UID, data) VALUES (:uid, :data)");
    $ins->execute([":uid" => $uid, ":data" => $today]);
    $id = $pdo->lastInsertId();

    $stm = $pdo->prepare("INSERT INTO risposta (id_interazione, testo, id_domanda) VALUES (:id, :testo, :id_domanda)");
    $stm->execute([":id" => $id, ":testo" => $testo, ":id_domanda" => $id_domanda]);

    $pdo->commit();
    echo "Risposta creata con successo";
    header("Location: ../frontend/risposta.php?id=" . urlencode($id_domanda));
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
