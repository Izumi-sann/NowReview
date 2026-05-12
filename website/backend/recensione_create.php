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
    $uid = $_SESSION["uid"];
    $today = date("Y-m-d");
    $testo = trim($_POST["testo"] ?? "");
    $id_prodotto = $_POST["id_prodotto"] ?? null;
    $link = null;//per motivi di tempo il link non verrà usato

    if ($testo === "" || $id_prodotto === null){
        throw new ErrorException("Dati mancanti");
    }

    $pdo->beginTransaction();
    $ins = $pdo->prepare("INSERT INTO interazione (UID, data) VALUES (:uid, :data)");
    $ins->execute([":uid" => $uid, ":data" => $today]);
    $id = $pdo->lastInsertId();

    $stm = $pdo->prepare("INSERT INTO recensione (id_interazione, testo, id_prodotto, link_prodotto) VALUES (:id, :testo, :idp, :link)");
    $stm->execute([":id" => $id, ":testo" => $testo, ":idp" => $id_prodotto, ":link" => $link]);

    $pdo->commit();
    echo "Recensione creata con successo";
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
