<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    header("Location=register.html");
    exit;
}

//1. inizializzazione ACID
//2. verifica presenza email
//3. verifica password
//4. inizia sessione
//5. commit

try{
    $email = $_POST["email"];
    $unencoded_password = $_POST["password"];

    if ($email == "" || $unencoded_password == "")
        throw new ErrorException("i dati inseriti non sono validi!");

    require "config.php";
    require "functions.php";

    $pdo -> exec("SET AUTOCOMMIT = 0");
    $pdo -> exec("BEGIN WORK");

    if (!checkemail($email, 1))
            throw new ErrorException("i dati inseriti non sono validi!");
    
    
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

function getuserdata(string $email) : array{

}

function get_credenziali(string $email) : array{
    global $pdo;
    $query = "SELECT UID, hash_password FROM credenziali WHERE email = :em";
    $stm = $pdo->prepare($query);
    $stm->bindParam(":em", $email);
    if($stm->execute())
        return $stm->fetch();
    else throw new ErrorException("email o password sbagliata!");
}

function checkuser(string $email, string $unencoded_password) : bool{

    $credenziali = get_credenziali($email);

    if (!password_verify($unencoded_password, $credenziali["password_hash"]))
        return false;

    $user_data = getuserdata($email);

    session_start();
    $_SESSION["nome"] = $user_data["nome"];
    $_SESSION["cognome"] = $user_data["cognome"];
    $_SESSION["username"] = $user_data["username"];
    $_SESSION["email"] = $user_data["email"];
    //$_SESSION["last_activity"] = time();  maybe later TODO   
}


?>