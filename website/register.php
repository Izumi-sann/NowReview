<?php
    if ($_SERVER["REQUEST_METHOD"] !== "POST"){
        header("Location: login.html");
        exit;
    }
    
    require "config.php";
    require "funcions.php";

    try{
        $email = $_POST["input_email"] ?? "";
        $unencoded_password = $_POST["input_password"] ?? "";
        $nome = $_POST["nome"] ?? "";
        $cognome = $_POST["cognome"] ?? "";
        $username = $_POST["username"] ?? "";

        //vertifica esistenza dati
        if ($email == "" || $unencoded_password == "" || $nome=="" || $cognome == "" || $username == "")
            throw new ErrorException("i dati inseriti non sono validi!");

        //1. imposizione ACID
        //2. ricerca email in db
        //3. hash password
        //4. creazione account
        //5. redirect pagina index.html

        $pdo -> exec("SET AUTOCOMMIT = 0");
        $pdo -> exec("SET SESSION idle_transaction_timeout = 0");
        $pdo -> exec("BEGIN WORK");
        $pdo -> exec("LOCK TABLES utente WRITE, READ");
        $pdo -> exec("LOCK TABLES credenziali WRITE, READ");
        

        if (!checkemail($email, threshold:0))
            throw new ErrorException("i dati inseriti non sono validi!");

        $password_hash = password_hash($unencoded_password, PASSWORD_BCRYPT);
        createuser($email, $password_hash, $nome, $cognome, $username);

        $pdo->exec("COMMIT WORK");

        header("Location: index.html");
        exit;
    }
    catch(PDOException $pdo_e){
        $pdo->exec("ROLLBACK WORK");
        echo "si è verificato un errore nella procedura di login. riprovare.";
        header("Location: register.html");
        exit;
    }
    catch(ErrorException $err){ //qualsiasi situazione di eccezione lanciata manualmente converge qui
        echo $err->getMessage();
        header("Location: register.html");
        exit;
    }
    finally{
        $pdo->exec("UNLOCK TABLES");
        $pdo -> exec("UNLOCK TABLES utente");
        $pdo -> exec("UNLOCK TABLES credenziali");
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