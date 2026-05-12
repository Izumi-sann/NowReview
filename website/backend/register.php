<?php
    if ($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: ../frontend/login.html");
        exit;
    }
    
    require "config.php";
    require "functions.php";

    try{
        $email = $_POST["input_email"] ?? "";
        $unencoded_password = $_POST["input_password"] ?? "";
        $nome = $_POST["nome"] ?? "";
        $cognome = $_POST["cognome"] ?? "";
        $username = $_POST["username"] ?? "";

        //vertifica esistenza dati
        if ($email == "" || $unencoded_password == "" || $nome=="" || $cognome == "" || $username == "")
            throw new ErrorException("i dati inseriti non sono validi!");

        //1. apertura transazione
        //2. ricerca email in db
        //3. hash password
        //4. creazione account
        //5. commit e redirect
        $pdo->beginTransaction();
        

        if (!checkemail($email, threshold:0))
            throw new ErrorException("i dati inseriti non sono validi!");

        $password_hash = password_hash($unencoded_password, PASSWORD_BCRYPT);
        createuser($email, $password_hash, $nome, $cognome, $username);

        $pdo->commit();

        header("Location: ../frontend/login.html");
        exit;
    }
    catch(PDOException $pdo_e){
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        header("Location: ../frontend/login.html");
        exit;
    }
    catch(ErrorException $err){ //qualsiasi situazione di eccezione lanciata manualmente converge qui
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        header("Location: ../frontend/login.html");
        exit;
    }

    function createuser($email, $password_hash, $nome, $cognome, $username){
        global $pdo;

        $query = "INSERT INTO utente (username, nome, cognome) VALUES (:username, :nome, :cognome)";
        $stm = $pdo->prepare($query);
        $stm -> bindParam(":username", $username);
        $stm -> bindParam(":nome", $nome);
        $stm -> bindParam(":cognome", $cognome);
        $stm -> execute();

        $UID = $pdo->lastInsertId();

        $query = "INSERT INTO credenziali (UID, email, password_hash) VALUES (:UID, :email, :hash_pass)";
        $stm = $pdo->prepare($query);
        $stm -> bindParam(":UID", $UID);
        $stm -> bindParam(":email", $email);
        $stm -> bindParam(":hash_pass", $password_hash);
        $stm -> execute();
    }
?>