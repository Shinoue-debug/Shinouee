<?php
require_once '../../fonctions.php';
require_once '../../config/connexion.php';

verifier_connexion_admin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = :id");
$stmt->execute([':id' => $id]);
$projet = $stmt->fetch();

if (!$projet) {
    header('Location: index.php');
    exit();
}

$erreurs = [];
$succes = false;
$csrf_token = generer_token_csrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifier_token_csrf($_POST['csrf_token'] ?? '')) {
        die("Erreur CSRF");
    }
    
    $titre = nettoyer($_POST['titre'] ?? '');
    $description = nettoyer($_POST['description'] ?? '');
    $technologies = nettoyer($_POST['technologies'] ?? '');
    $lien = filter_var(trim($_POST['lien'] ?? ''), FILTER_VALIDATE_URL) ? trim($_POST['lien']) : null;
    
    if (empty($titre)) $erreurs['titre'] = 'Le titre est obligatoire.';
    if (empty($description)) $erreurs['description'] = 'La description est obligatoire.';
    if (empty($technologies)) $erreurs['technologies'] = 'Les technologies sont obligatoires.';
    
    $image_name = $projet['image'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            if (!is_dir('../../images/projets')) {
                mkdir('../../images/projets', 0777, true);
            }
            if (!empty($projet['image']) && file_exists('../../images/projets/' . $projet['image'])) {
                unlink('../../images/projets/' . $projet['image']);
            }
            $image_name = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../../images/projets/' . $image_name);
        } else {
            $erreurs['image'] = 'Format non autorisé';
        }
    }
    
    if (empty($erreurs)) {
        $stmt = $pdo->prepare("UPDATE projets SET titre = :titre, description = :description, technologies = :technologies, image = :image, lien = :lien WHERE id = :id");
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':technologies' => $technologies,
            ':image' => $image_name,
            ':lien' => $lien,
            ':id' => $id
        ]);
        $succes = true;
        // Recharger
        $stmt = $pdo->prepare("SELECT * FROM projets WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $projet = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le projet</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="index.php">Projets</a>
        <a href="../utilisateurs/">Administrateurs</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </div>
    
    <main>
        <section class="contact">
            <h1>Modifier le projet</h1>
            
            <?php if ($succes) : ?>
                <div class="success">Projet modifié avec succès !</div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <label for="titre">Titre * :</label>
                <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($projet['titre']) ?>" required>
                <?php if (isset($erreurs['titre'])) echo '<span class="error">'.$erreurs['titre'].'</span>'; ?>
                
                <label for="description">Description * :</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($projet['description']) ?></textarea>
                <?php if (isset($erreurs['description'])) echo '<span class="error">'.$erreurs['description'].'</span>'; ?>
                
                <label for="technologies">Technologies * :</label>
                <input type="text" id="technologies" name="technologies" value="<?= htmlspecialchars($projet['technologies']) ?>" required>
                <?php if (isset($erreurs['technologies'])) echo '<span class="error">'.$erreurs['technologies'].'</span>'; ?>
                
                <label for="lien">Lien externe :</label>
                <input type="url" id="lien" name="lien" value="<?= htmlspecialchars($projet['lien']) ?>">
                
                <?php if ($projet['image']) : ?>
                    <p>Image actuelle : <img src="../../images/projets/<?= $projet['image'] ?>" width="100"></p>
                    <label>
                        <input type="checkbox" name="supprimer_image" value="oui"> Supprimer cette image
                    </label>
                <?php endif; ?>
                
                <label for="image">Nouvelle image :</label>
                <input type="file" id="image" name="image">
                <?php if (isset($erreurs['image'])) echo '<span class="error">'.$erreurs['image'].'</span>'; ?>
                
                <button type="submit">Enregistrer</button>
            </form>
            
            <p><a href="index.php">← Retour</a></p>
        </section>
    </main>
</body>
</html>