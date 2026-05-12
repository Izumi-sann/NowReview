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

$id_domanda = $_GET["id"] ?? null;
if ($id_domanda === null) {
    header("Location: forum.php");
    exit;
}

$rispostaFormAllowed = $isLoggedIn;

$query = "SELECT i.id_interazione, i.UID, i.data, d.titolo, d.testo, d.id_prodotto, p.nome AS prod_nome, u.username,
                 COUNT(l.UID) AS num_like
          FROM interazione i
          JOIN domanda d ON i.id_interazione = d.id_interazione
          JOIN prodotto p ON d.id_prodotto = p.id_prodotto
          JOIN utente u ON i.UID = u.UID
          LEFT JOIN `like` l ON i.id_interazione = l.id_interazione
          WHERE i.id_interazione = :id
          GROUP BY i.id_interazione";

$stm = $pdo->prepare($query);
$stm->execute([":id" => $id_domanda]);
$domanda = $stm->fetch();

$query = "SELECT i.id_interazione, i.UID, i.data, r.testo, u.username,
                 COUNT(l.UID) AS num_like
          FROM interazione i
          JOIN risposta r ON i.id_interazione = r.id_interazione
          JOIN utente u ON i.UID = u.UID
          LEFT JOIN `like` l ON i.id_interazione = l.id_interazione
          WHERE r.id_domanda = :id_domanda
          GROUP BY i.id_interazione
          ORDER BY i.id_interazione ASC";

$stm = $pdo->prepare($query);
$stm->execute([":id_domanda" => $id_domanda]);
$risposte = $stm->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>NowReview - Risposte</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>

    <nav>
        <?php echo $navLinks; ?>
    </nav>

    <div class="contenuto">
        <img src="logo.png" class="logo">

        <h2>Domanda</h2>
        <?php if ($domanda): ?>
            <div class="box">
                <b><?php echo htmlspecialchars($domanda['titolo']); ?></b><br>
                <?php echo htmlspecialchars($domanda['testo']); ?><br><br>
                <i>Scritto da: <?php echo htmlspecialchars($domanda['username']); ?> il <?php echo htmlspecialchars($domanda['data']); ?> | Prodotto: <?php echo htmlspecialchars($domanda['prod_nome']); ?></i>&nbsp;
                <b>👍 <?php echo htmlspecialchars($domanda['num_like']); ?></b>

                <?php if ($isLoggedIn): ?>
                    <form method="POST" action="../backend/like.php" style="display:inline;">
                        <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($domanda['id_interazione']); ?>">
                        <input type="hidden" name="action" value="add">
                        <button type="submit" style="font-size:12px;">Mi piace</button>
                    </form>
                <?php endif; ?>

                <?php if ($isLoggedIn && (int) $_SESSION['uid'] === (int) $domanda['UID']): ?>
                    <br><br>
                    <?php if (isset($_GET['edit_domanda']) && $_GET['edit_domanda'] == $domanda['id_interazione']): ?>
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
                            <a href="risposta.php?id=<?php echo htmlspecialchars($id_domanda); ?>"><button type="button">Annulla</button></a>
                        </form>
                    <?php else: ?>
                        <a href="?id=<?php echo htmlspecialchars($id_domanda); ?>&edit_domanda=<?php echo htmlspecialchars($domanda['id_interazione']); ?>"><button>Modifica</button></a>
                        <form method="POST" action="../backend/domanda_delete.php" style="display:inline;" onsubmit="return confirm('Sei sicuro?');">
                            <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($domanda['id_interazione']); ?>">
                            <button type="submit">Elimina</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Domanda non trovata.</p>
            <a href="forum.php"><button>Torna al Forum</button></a>
        <?php endif; ?>

        <hr>

        <h2>Risposte</h2>

        <?php if (count($risposte) > 0): ?>
            <?php foreach ($risposte as $risp): ?>
                <div class="box">
                    <b><?php echo htmlspecialchars($risp['username']); ?></b> - <?php echo htmlspecialchars($risp['data']); ?><br>
                    <?php echo htmlspecialchars($risp['testo']); ?><br><br>
                    <b>👍 <?php echo htmlspecialchars($risp['num_like']); ?></b>

                    <?php if ($isLoggedIn): ?>
                        <form method="POST" action="../backend/like.php" style="display:inline;">
                            <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($risp['id_interazione']); ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit" style="font-size:12px;">Mi piace</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($isLoggedIn && (int) $_SESSION['uid'] === (int) $risp['UID']): ?>
                        <br><br>
                        <?php if (isset($_GET['edit_risp']) && $_GET['edit_risp'] == $risp['id_interazione']): ?>
                            <form method="POST" action="../backend/risposta_edit.php" style="border: 1px solid #ccc; padding: 10px; margin-top: 10px;">
                                <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($risp['id_interazione']); ?>">
                                <label>Risposta</label>
                                <textarea name="testo" rows="5" required><?php echo htmlspecialchars($risp['testo']); ?></textarea>
                                <button type="submit">Salva</button>
                                <a href="risposta.php?id=<?php echo htmlspecialchars($id_domanda); ?>"><button type="button">Annulla</button></a>
                            </form>
                        <?php else: ?>
                            <a href="?id=<?php echo htmlspecialchars($id_domanda); ?>&edit_risp=<?php echo htmlspecialchars($risp['id_interazione']); ?>"><button>Modifica</button></a>
                            <form method="POST" action="../backend/risposta_delete.php" style="display:inline;" onsubmit="return confirm('Sei sicuro?');">
                                <input type="hidden" name="id_interazione" value="<?php echo htmlspecialchars($risp['id_interazione']); ?>">
                                <button type="submit">Elimina</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nessuna risposta ancora.</p>
        <?php endif; ?>

        <hr>

        <h2>Scrivi una risposta</h2>

        <?php if ($rispostaFormAllowed): ?>
        <form action="../backend/risposta_create.php" method="POST">
            <input type="hidden" name="id_domanda" value="<?php echo htmlspecialchars($id_domanda); ?>">

            <label>La tua risposta</label>
            <textarea name="testo" rows="5" required></textarea>

            <button type="submit">Invia risposta</button>
        </form>
        <?php else: ?>
            <p>Accedi per rispondere. <a href="login.html">Vai al login</a></p>
        <?php endif; ?>

    </div>

</body>
</html>
