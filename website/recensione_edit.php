<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location: recensione.html");
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
    $id_prodotto = $_POST["id_prodotto"] ?? null;
    $link = trim($_POST["link_prodotto"] ?? "");
    $uid = $_SESSION["uid"];

    if ($id === null || $testo === "" || $id_prodotto === null){
        throw new ErrorException("Dati mancanti o invalidi");
    }

    $q = $pdo->prepare("SELECT UID FROM interazione WHERE id_interazione = :id");
    $q->execute([":id" => $id]);
    $row = $q->fetch();
    if (!$row) throw new ErrorException("Interazione non trovata");
    if ((int)$row["UID"] !== (int)$uid){
        echo "Permesso negato";
        header("Location: recensione.html");
        exit;
    }

    $stm = $pdo->prepare("UPDATE recensione SET testo = :testo, link_prodotto = :link, id_prodotto = :idp WHERE id_interazione = :id");
    $stm->execute([":testo" => $testo, ":link" => $link, ":idp" => $id_prodotto, ":id" => $id]);

    echo "Recensione aggiornata con successo";
    header("Location: recensione.html");
    exit;
}
catch(PDOException $pdo_e){
    echo "Errore nel database";
    header("Location: recensione.html");
    exit;
}
catch(ErrorException $err){
    echo $err->getMessage();
    header("Location: recensione.html");
    exit;
}

?>
