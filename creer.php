<?php
require_once '../../fonctions.php';
require_once '../../config/connexion.php';

verifier_connexion_admin();

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
    
    if (!champ_requis($titre)) {
        $erreurs['titre'] = 'Le titre est obligatoire.';
    }
    if (!champ_requis($description)) {
        $erreurs['description'] = 'La description est obligatoire.';
    }
    if (!champ_requis($technologies)) {
        $erreurs['technologies'] = 'Les technologies sont obligatoires.';
    }
    
    $image_name = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $image_name = upload_image($_FILES['image'], '../../images/projets');
        if (!$image_name) {
            $erreurs['image'] = "Format d'image non autorisé (jpg, jpeg, png, webp, gif)";
        }
    }
    
    if (empty($erreurs)) {
        $stmt = $pdo->prepare("INSERT INTO projets (titre, description, technologies, image, lien, date_creation) VALUES (:titre, :description, :technologies, :image, :lien, NOW())");
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':technologies' => $technologies,
            ':image' => $image_name,
            ':lien' => $lien
        ]);
        $succes = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un projet</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="admin-nav">
        <a href="../dashboard.php">Dashboard</a>
        <a href="index.php">Projets</a>
        <a href="../deconnexion.php">Déconnexion</a>
    </div>
    
    <main>
        <section class="contact">
            <h1>Nouveau projet</h1>
            
            <?php if ($succes) : ?>
                <div class="success">Projet créé avec succès !</div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <label for="titre">Titre * :</label>
                <input type="text" id="titre" name="titre" value="<?= $_POST['titre'] ?? '' ?>">
                <?php if (isset($erreurs['titre'])) : ?>
                    <span class="error"><?= $erreurs['titre'] ?></span>
                <?php endif; ?>
                
                <label for="description">Description * :</label>
                <textarea id="description" name="description"><?= $_POST['description'] ?? '' ?></textarea>
                <?php if (isset($erreurs['description'])) : ?>
                    <span class="error"><?= $erreurs['description'] ?></span>
                <?php endif; ?>
                
                <label for="technologies">Technologies * (séparées par des virgules) :</label>
                <input type="text" id="technologies" name="technologies" value="<?= $_POST['technologies'] ?? '' ?>">
                <?php if (isset($erreurs['technologies'])) : ?>
                    <span class="error"><?= $erreurs['technologies'] ?></span>
                <?php endif; ?>
                
                <label for="lien">Lien externe :</label>
                <input type="text" id="lien" name="lien" value="<?= $_POST['lien'] ?? '' ?>">
                
                <label for="image">Image (jpg, jpeg, png, webp, gif) :</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
                <?php if (isset($erreurs['image'])) : ?>
                    <span class="error"><?= $erreurs['image'] ?></span>
                <?php endif; ?>
                
                <button type="submit">Créer</button>
            </form>
        </section>
    </main>
</body>
</html>
