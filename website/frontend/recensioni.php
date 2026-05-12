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

$createFormAllowed = $isLoggedIn;

$query = "SELECT i.id_interazione, i.UID, i.data, r.testo, p.nome AS prod_nome, u.username,
                 COUNT(l.UID) AS num_like
          FROM interazione i
          JOIN recensione r ON i.id_interazione = r.id_interazione
          JOIN prodotto p ON r.id_prodotto = p.id_prodotto
          JOIN utente u ON i.UID = u.UID
          LEFT JOIN `like` l ON i.id_interazione = l.id_interazione
          GROUP BY i.id_interazione
          ORDER BY i.id_interazione DESC";

$recensioni = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>NowReview - Recensioni</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>

    <nav>
        <?php echo $navLinks; ?>
    </nav>

    <div class="contenuto">
        <img src="logo.png" class="logo">

        <h2>Scrivi una recensione</h2>

        <?php if ($createFormAllowed): ?>
        <form action="../backend/recensione_create.php" method="POST">
            <label>Prodotto</label>
            <select name="id_prodotto" required>
                <option value="">Seleziona un prodotto</option>
                <?php echo $productOptions; ?>
            </select>

            <label>Recensione</label>
            <textarea name="testo" rows="5" required></textarea>

            <button type="submit">Pubblica</button>
        </form>
        <?php else: ?>
            <p>Accedi per scrivere una recensione. <a href="login.html">Vai al login</a></p>
        <?php endif; ?>

        <hr>

        <h2>Recensioni degli utenti</h2>

        <?php if (count($recensioni) > 0): ?>
            <?php foreach ($recensioni as $rec): ?>
                <div class="box">
                    <b><?php echo htmlspecialchars($rec['prod_nome']); ?></b><br>
                    <?php echo htmlspecialchars(substr($rec['testo'], 0, 150)); ?><?php echo strlen($rec['testo']) > 150 ? '...' : ''; ?><br><br>
                    <i>Scritto da: <?php echo htmlspecialchars($rec['username']); ?> il <?php echo htmlspecialchars($rec['data']); ?></i>&nbsp;
                    <b>👍 <?php echo htmlspecialchars($rec['num_like']); ?></b>

                    <?php if ($isLoggedIn): ?>
                        <form method="POST" action="../backend/like.php" style="display:inline;">
                            <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($rec['id_interazione']); ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit" style="font-size:12px;">Mi piace</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($isLoggedIn && (int) $_SESSION['uid'] === (int) $rec['UID']): ?>
                        <br><br>
                        <?php if (isset($_GET['edit_id']) && $_GET['edit_id'] == $rec['id_interazione']): ?>
                            <form method="POST" action="../backend/recensione_edit.php" style="border: 1px solid #ccc; padding: 10px; margin-top: 10px;">
                                <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($rec['id_interazione']); ?>">
                                <label>Prodotto</label>
                                <select name="id_prodotto" required>
                                    <option value="">Seleziona un prodotto</option>
                                    <?php echo $productOptions; ?>
                                </select>
                                <label>Recensione</label>
                                <textarea name="testo" rows="5" required><?php echo htmlspecialchars($rec['testo']); ?></textarea>
                                <button type="submit">Salva</button>
                                <a href="recensioni.php"><button type="button">Annulla</button></a>
                            </form>
                        <?php else: ?>
                            <a href="?edit_id=<?php echo htmlspecialchars($rec['id_interazione']); ?>"><button>Modifica</button></a>
                            <form method="POST" action="../backend/recensione_delete.php" style="display:inline;" onsubmit="return confirm('Sei sicuro?');">
                                <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($rec['id_interazione']); ?>">
                                <button type="submit">Elimina</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nessuna recensione ancora. Sii il primo a recensire!</p>
        <?php endif; ?>

    </div>

</body>
</html>
