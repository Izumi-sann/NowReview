<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location: ../frontend/recensioni.php");
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
        header("Location: ../frontend/recensioni.php");
        exit;
    }

    $pdo->prepare("DELETE FROM `like` WHERE id_interazione = :id")->execute([":id" => $id]);
    $pdo->prepare("DELETE FROM recensione WHERE id_interazione = :id")->execute([":id" => $id]);
    $pdo->prepare("DELETE FROM interazione WHERE id_interazione = :id")->execute([":id" => $id]);

    $pdo->commit();
    echo "Recensione eliminata con successo";
    header("Location: ../frontend/recensioni.php");
    exit;
}
catch(PDOException $pdo_e){
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Errore nel database";
    header("Location: ../frontend/recensioni.php");
    exit;
}
catch(ErrorException $err){
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo $err->getMessage();
    header("Location: ../frontend/recensioni.php");
    exit;
}

?>
