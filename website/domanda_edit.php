<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location: domanda.html");
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
    $titolo = trim($_POST["titolo"] ?? "");
    $testo = trim($_POST["testo"] ?? "");
    $id_prodotto = $_POST["id_prodotto"] ?? null;
    $uid = $_SESSION["uid"];

    if ($id === null || $titolo === "" || $testo === "" || $id_prodotto === null){
        throw new ErrorException("Dati mancanti o invalidi");
    }

    $q = $pdo->prepare("SELECT UID FROM interazione WHERE id_interazione = :id");
    $q->execute([":id" => $id]);
    $row = $q->fetch();
    if (!$row) throw new ErrorException("Interazione non trovata");
    if ((int)$row["UID"] !== (int)$uid){
        echo "Permesso negato";
        header("Location: domanda.html");
        exit;
    }

    $stm = $pdo->prepare("UPDATE domanda SET titolo = :titolo, testo = :testo, id_prodotto = :idp WHERE id_interazione = :id");
    $stm->execute([":titolo" => $titolo, ":testo" => $testo, ":idp" => $id_prodotto, ":id" => $id]);

    echo "Domanda aggiornata con successo";
    header("Location: domanda.html");
    exit;
}
catch(PDOException $pdo_e){
    echo "Errore nel database";
    header("Location: domanda.html");
    exit;
}
catch(ErrorException $err){
    echo $err->getMessage();
    header("Location: domanda.html");
    exit;
}

?>
