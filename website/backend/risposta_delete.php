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
    $id = $_POST["id_interazione"] ?? null;
    $uid = $_SESSION["uid"];

    if ($id === null){
        throw new ErrorException("id_interazione mancante");
    }

    $pdo->beginTransaction();
    $q = $pdo->prepare("SELECT UID FROM interazione WHERE id_interazione = :id");
    $q->execute([":id" => $id]);
    $row = $q->fetch();
    if (!$row) throw new ErrorException("Interazione non trovata");
    if ((int)$row["UID"] !== (int)$uid){
        $pdo->rollBack();
        echo "Permesso negato";
        header("Location: risposta.html");
        exit;
    }

    $pdo->prepare("DELETE FROM `like` WHERE id_interazione = :id")->execute([":id" => $id]);
    $pdo->prepare("DELETE FROM risposta WHERE id_interazione = :id")->execute([":id" => $id]);
    $pdo->prepare("DELETE FROM interazione WHERE id_interazione = :id")->execute([":id" => $id]);

    $pdo->commit();
    echo "Risposta eliminata con successo";
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
