<?php
require_once '../fonctions.php';
require_once '../config/connexion.php';

// Si déjà connecté, rediriger vers dashboard
if (est_connecte()) {
    header('Location: dashboard.php');
    exit();
}

$erreur = '';
$csrf_token = generer_token_csrf();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifier_token_csrf($_POST['csrf_token'] ?? '')) {
        die("Erreur CSRF");
    }
    
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($mot_de_passe, $admin['mot_de_passe'])) {
        // Régénération ID session pour éviter fixation
        session_regenerate_id(true);
        
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_prenom'] = $admin['prenom'];
        $_SESSION['admin_nom'] = $admin['nom'];
        
        header('Location: dashboard.php');
        exit();
    } else {
        // Message générique pour ne pas indiquer si c'est l'email ou le mot de passe
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main>
        <section class="contact">
            <h1>Espace d'administration</h1>
            <h2>Connexion</h2>
            
            <?php if ($erreur) : ?>
                <div class="error"><?= $erreur ?></div>
            <?php endif; ?>
            
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
                
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                
                <button type="submit">Se connecter</button>
            </form>
        </section>
    </main>
</body>
</html>
