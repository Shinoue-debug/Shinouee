<?php
// SCRIPT À SUPPRIMER APRÈS EXÉCUTION
require_once 'config/connexion.php';

$mot_de_passe = '';
$email_prof = 'professeur@estm.sn';

// Générer un mot de passe aléatoire
$mot_de_passe = bin2hex(random_bytes(6));
$hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

// Vérifier ou créer le compte
$stmt = $pdo->prepare("SELECT id FROM administrateurs WHERE email = :email");
$stmt->execute([':email' => $email_prof]);
$existant = $stmt->fetch();

if ($existant) {
    $stmt = $pdo->prepare("UPDATE administrateurs SET mot_de_passe = :mdp WHERE email = :email");
    $stmt->execute([':mdp' => $hash, ':email' => $email_prof]);
    $status = "mis à jour";
} else {
    $stmt = $pdo->prepare("INSERT INTO administrateurs (prenom, nom, email, mot_de_passe, date_creation) VALUES ('Professeur', 'Diouf', :email, :mdp, NOW())");
    $stmt->execute([':email' => $email_prof, ':mdp' => $hash]);
    $status = "créé";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Professeur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .info {
            background: #e8f4fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info p {
            margin: 10px 0;
        }
        .email, .password {
            font-family: monospace;
            font-size: 18px;
            font-weight: bold;
            background: white;
            padding: 8px 12px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 5px;
            border: 1px solid #ccc;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            font-size: 14px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 0;
        }
        button:hover {
            background: #218838;
        }
        .delete-note {
            background: #dc3545;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Compte Professeur</h1>
        
        <div class="success">
            Compte <?= $status ?> avec succès !
        </div>
        
        <div class="info">
            <p><strong>Email :</strong></p>
            <div class="email"><?= $email_prof ?></div>
            
            <p><strong>Mot de passe :</strong></p>
            <div class="password"><?= $mot_de_passe ?></div>
        </div>
        
        <div class="warning">
            <strong>Conservez précieusement ces identifiants !</strong>
        </div>
        
        <button onclick="window.location.href='admin/connexion.php'">Accéder à l'administration</button>
        
        
    </div>
</body>
</html>