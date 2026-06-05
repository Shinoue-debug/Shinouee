<?php
require_once '../../fonctions.php';
require_once '../../config/connexion.php';

verifier_connexion_admin();

$id = (int)($_GET['id'] ?? 0);

// Récupérer le projet pour vérifier qu'il existe
$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = :id");
$stmt->execute([':id' => $id]);
$projet = $stmt->fetch();

if (!$projet) {
    header('Location: index.php');
    exit();
}

$erreur = '';
$csrf_token = generer_token_csrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifier_token_csrf($_POST['csrf_token'] ?? '')) {
        die("Erreur CSRF");
    }
    
    $confirm = $_POST['confirm'] ?? '';
    
    if ($confirm === 'oui') {
        // Supprimer l'image associée si elle existe
        if (!empty($projet['image'])) {
            $chemin_image = '../../images/projets/' . $projet['image'];
            if (file_exists($chemin_image)) {
                unlink($chemin_image);
            }
        }
        
        // Supprimer le projet de la base de données
        $stmt = $pdo->prepare("DELETE FROM projets WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        header('Location: index.php?supprime=1');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un projet - Administration</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .confirmation-box {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: var(--card-bg);
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .confirmation-box h2 {
            color: #c62828;
            margin-bottom: 1rem;
        }
        .project-preview {
            background-color: #f5f5f5;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            text-align: left;
        }
        body.dark .project-preview {
            background-color: #333;
        }
        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }
        .btn-danger {
            background-color: #c62828 !important;
        }
        .btn-danger:hover {
            background-color: #b71c1c !important;
        }
        .btn-secondary {
            background-color: #666 !important;
        }
        .btn-secondary:hover {
            background-color: #555 !important;
        }
    </style>
</head>
<body>
    <div class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="index.php">Projets</a>
        <a href="../utilisateurs/">Administrateurs</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </div>
    
    <main>
        <div class="confirmation-box">
            <h2>⚠️ Confirmation de suppression</h2>
            <p>Êtes-vous sûr de vouloir supprimer définitivement ce projet ?</p>
            
            <div class="project-preview">
                <p><strong>Titre :</strong> <?= nettoyer($projet['titre']) ?></p>
                <p><strong>Description :</strong> <?= nettoyer(substr($projet['description'], 0, 100)) ?>...</p>
                <?php if (!empty($projet['image'])) : ?>
                    <p><strong>Image associée :</strong> <?= nettoyer($projet['image']) ?></p>
                <?php endif; ?>
            </div>
            
            <p style="color: #c62828; font-weight: bold;">Cette action est irréversible !</p>
            
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="buttons">
                    <button type="submit" name="confirm" value="oui" class="btn-danger">✓ Oui, supprimer</button>
                    <button type="submit" name="confirm" value="non" class="btn-secondary">✗ Non, annuler</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
