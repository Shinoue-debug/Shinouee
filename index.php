<?php
require_once '../../fonctions.php';
require_once '../../config/connexion.php';

verifier_connexion_admin();

// Marquer un message comme lu
if (isset($_GET['voir']) && is_numeric($_GET['voir'])) {
    $id = (int)$_GET['voir'];
    $stmt = $pdo->prepare("UPDATE messages_contact SET lu = 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

$stmt = $pdo->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC");
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages de contact</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="../projets/">Projets</a>
        <a href="index.php">Messages</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </div>
    
    <main>
        <section>
            <h1>Messages de contact</h1>
            
            <table border="1" cellpadding="8" style="width:100%">
                <thead>
                    <tr><th>ID</th><th>Nom</th><th>Email</th><th>Message</th><th>Date</th><th>Statut</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message) : ?>
                        <tr style="<?= $message['lu'] ? '' : 'background: #fff3cd;' ?>">
                            <td><?= $message['id'] ?></td>
                            <td><?= nettoyer($message['nom']) ?></td>
                            <td><?= nettoyer($message['email']) ?></td>
                            <td><?= nettoyer(substr($message['message'], 0, 100)) ?>...</td>
                            <td><?= $message['date_envoi'] ?></td>
                            <td><?= $message['lu'] ? 'Lu' : 'Non lu' ?></td>
                            <td>
                                <a href="voir.php?id=<?= $message['id'] ?>">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
