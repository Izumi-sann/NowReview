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
    $testo = trim($_POST["testo"] ?? "");
    $uid = $_SESSION["uid"];

    if ($id === null || $testo === ""){
        throw new ErrorException("Dati mancanti o invalidi");
    }

    $q = $pdo->prepare("SELECT UID FROM interazione WHERE id_interazione = :id");
    $q->execute([":id" => $id]);
    $row = $q->fetch();
    if (!$row) throw new ErrorException("Interazione non trovata");
    if ((int)$row["UID"] !== (int)$uid){
        echo "Permesso negato";
        header("Location: risposta.html");
        exit;
    }

    $stm = $pdo->prepare("UPDATE risposta SET testo = :testo WHERE id_interazione = :id");
    $stm->execute([":testo" => $testo, ":id" => $id]);

    echo "Risposta aggiornata con successo";
    header("Location: risposta.html");
    exit;
}
catch(PDOException $pdo_e){
    echo "Errore nel database";
    header("Location: risposta.html");
    exit;
}
catch(ErrorException $err){
    echo $err->getMessage();
    header("Location: risposta.html");
    exit;
}

?>
