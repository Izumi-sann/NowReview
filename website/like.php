<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location: index.html");
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
    $action = $_POST["action"] ?? "";
    $id = $_POST["id_interazione"] ?? null;
    $uid = $_SESSION["uid"];
    $posted_uid = $_POST["UID"] ?? null;
    
    if ($posted_uid !== null && (int)$posted_uid !== (int)$uid){
        echo "UID mismatch";
        header("Location: index.html");
        exit;
    }

    if ($id === null || $action === ""){
        throw new ErrorException("Parametri mancanti");
    }

    $today = date("Y-m-d");

    if ($action === "add"){
        $stm = $pdo->prepare("INSERT INTO `like` (id_interazione, UID, data) VALUES (:id, :uid, :data)");
        $stm->execute([":id" => $id, ":uid" => $uid, ":data" => $today]);
        echo "Like aggiunto";
    }
    elseif ($action === "remove"){
        $stm = $pdo->prepare("DELETE FROM `like` WHERE id_interazione = :id AND UID = :uid");
        $stm->execute([":id" => $id, ":uid" => $uid]);
        echo "Like rimosso";
    }
    else{
        throw new ErrorException("Azione non valida");
    }

    header("Location: index.html");
    exit;
}
catch(PDOException $e){
    echo "Errore nel database";
    header("Location: index.html");
    exit;
}
catch(ErrorException $err){
    echo $err->getMessage();
    header("Location: index.html");
    exit;
}

?>
