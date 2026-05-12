<?php
session_start();
require "../backend/config.php";

$isLoggedIn = isset($_SESSION["uid"]);
$navLinks = $isLoggedIn
    ? '<a href="index.html">Home</a><a href="profilo.php">Profilo</a><a href="forum.php">Forum</a><a href="recensioni.php">Recensioni</a>'
    : '<a href="index.html">Home</a><a href="login.html">Login</a>';

$productOptions = '';
$productQuery = $pdo->query("SELECT id_prodotto, nome FROM prodotto ORDER BY nome ASC");
while ($prod = $productQuery->fetch()) {
    $productOptions .= '<option value="' . htmlspecialchars($prod['id_prodotto']) . '">' . htmlspecialchars($prod['nome']) . '</option>';
}

$forumFormAllowed = $isLoggedIn;

$query = "SELECT i.id_interazione, i.UID, i.data, d.titolo, d.testo, d.id_prodotto, u.username
          FROM interazione i
          JOIN domanda d ON i.id_interazione = d.id_interazione
          JOIN utente u ON i.UID = u.UID
          ORDER BY i.id_interazione DESC";
$domande = $pdo->query($query)->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>NowReview - Forum</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>

    <nav>
        <?php echo $navLinks; ?>
    </nav>

    <div class="contenuto">
        <img src="logo.png" class="logo">

        <h2>Fai una domanda</h2>

        <?php if ($forumFormAllowed): ?>
        <form action="../backend/domanda_create.php" method="POST">
            <label>Titolo domanda</label>
            <input type="text" name="titolo" required>

            <label>Testo domanda</label>
            <textarea name="testo" rows="5"></textarea>

            <label>Prodotto</label>
            <select name="id_prodotto" required>
                <option value="">Seleziona un prodotto</option>
                <?php echo $productOptions; ?>
            </select>

            <button type="submit">Invia domanda</button>
        </form>
        <?php else: ?>
            <p>Accedi per fare una domanda. <a href="login.html">Vai al login</a></p>
        <?php endif; ?>

        <hr>

        <h2>Domande degli utenti</h2>

        <?php if (count($domande) > 0): ?>
            <?php foreach ($domande as $domanda): ?>
                <div class="box">
                    <b><?php echo htmlspecialchars($domanda['titolo']); ?></b><br>
                    <?php echo htmlspecialchars(substr($domanda['testo'], 0, 100)); ?><?php echo strlen($domanda['testo']) > 100 ? '...' : ''; ?><br><br>
                    <i>Scritto da: <?php echo htmlspecialchars($domanda['username']); ?> il <?php echo htmlspecialchars($domanda['data']); ?></i>&nbsp;&nbsp;
                    <a href="risposta.php?id=<?php echo htmlspecialchars($domanda['id_interazione']); ?>"><button>Rispondi</button></a>

                    <?php if ($isLoggedIn && (int) $_SESSION['uid'] === (int) $domanda['UID']): ?>
                        <br><br>
                        <?php if (isset($_GET['edit_id']) && $_GET['edit_id'] == $domanda['id_interazione']): ?>
                            <form method="POST" action="../backend/domanda_edit.php" style="border: 1px solid #ccc; padding: 10px; margin-top: 10px;">
                                <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($domanda['id_interazione']); ?>">
                                <label>Titolo</label>
                                <input type="text" name="titolo" value="<?php echo htmlspecialchars($domanda['titolo']); ?>" required>
                                <label>Testo</label>
                                <textarea name="testo" rows="5" required><?php echo htmlspecialchars($domanda['testo']); ?></textarea>
                                <label>Prodotto</label>
                                <select name="id_prodotto" required>
                                    <option value="">Seleziona un prodotto</option>
                                    <?php echo $productOptions; ?>
                                </select>
                                <button type="submit">Salva</button>
                                <a href="forum.php"><button type="button">Annulla</button></a>
                            </form>
                        <?php else: ?>
                            <a href="?edit_id=<?php echo htmlspecialchars($domanda['id_interazione']); ?>"><button>Modifica</button></a>
                            <form method="POST" action="../backend/domanda_delete.php" style="display:inline;" onsubmit="return confirm('Sei sicuro?');">
                                <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($domanda['id_interazione']); ?>">
                                <button type="submit">Elimina</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nessuna domanda ancora. Sii il primo a chiedere!</p>
        <?php endif; ?>

    </div>

</body>
</html>
