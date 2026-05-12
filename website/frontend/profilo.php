<?php
session_start();
require "../backend/config.php";

$isLoggedIn = isset($_SESSION["uid"]);
$navLinks = $isLoggedIn
    ? '<a href="index.html">Home</a><a href="profilo.php">Profilo</a><a href="forum.php">Forum</a><a href="recensioni.php">Recensioni</a>'
    : '<a href="index.html">Home</a><a href="login.html">Login</a>';

$profileHtml = '';
$contributiHtml = '';

if ($isLoggedIn) {
    $profileHtml = '<p><strong>Nome:</strong> ' . htmlspecialchars($_SESSION["nome"]) . '</p>'
        . '<p><strong>Cognome:</strong> ' . htmlspecialchars($_SESSION["cognome"]) . '</p>'
        . '<p><strong>Username:</strong> ' . htmlspecialchars($_SESSION["username"]) . '</p>'
        . '<p><strong>Email:</strong> ' . htmlspecialchars($_SESSION["email"]) . '</p>';

    $query = "SELECT i.id_interazione, i.data, d.titolo, 'domanda' AS tipo
              FROM interazione i
              JOIN domanda d ON i.id_interazione = d.id_interazione
              WHERE i.UID = :uid
              UNION ALL
              SELECT i.id_interazione, i.data, r.testo AS titolo, 'risposta' AS tipo
              FROM interazione i
              JOIN risposta r ON i.id_interazione = r.id_interazione
              WHERE i.UID = :uid
              UNION ALL
              SELECT i.id_interazione, i.data, rec.testo AS titolo, 'recensione' AS tipo
              FROM interazione i
              JOIN recensione rec ON i.id_interazione = rec.id_interazione
              WHERE i.UID = :uid
              ORDER BY id_interazione DESC";

    $stm = $pdo->prepare($query);
    $stm->execute([":uid" => $_SESSION["uid"]]);
    $contributi = $stm->fetchAll();

    if (count($contributi) > 0) {
        foreach ($contributi as $contrib) {
            $tipo = ucfirst($contrib['tipo']);
            $data = htmlspecialchars($contrib['data']);
            $titolo = htmlspecialchars(substr($contrib['titolo'], 0, 100));
            $ellipsis = strlen($contrib['titolo']) > 100 ? '...' : '';

            $contributiHtml .= '<div class="box"><strong>' . $tipo . '</strong> (' . $data . ')<br>' . $titolo . $ellipsis . '</div>';
        }
    } else {
        $contributiHtml = '<p>Non hai ancora pubblicato nulla.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>NowReview - Profilo</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>

    <nav>
        <?php echo $navLinks; ?>
    </nav>

    <div class="contenuto">
        <img src="logo.png" class="logo">

        <h2>Profilo</h2>

        <?php if($isLoggedIn): ?>
            <?php echo $profileHtml; ?>

            <hr>

            <h3>I tuoi contributi</h3>
            <?php echo $contributiHtml; ?>

            <hr>

            <form action="../backend/logout.php" method="POST">
                <button type="submit">Esci</button>
            </form>
        <?php else: ?>
            <p>Nessun utente loggato. <a href="login.html">Vai al login</a></p>
        <?php endif; ?>

    </div>

</body>
</html>
