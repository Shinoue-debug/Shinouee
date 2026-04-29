<?php require 'functions.php'; ?>
<?php
$contact = [
    'name'    => '',
    'email'   => '',
    'message' => '',
];

$projet = [
    'project_name' => '',
    'description'  => '',
    'budget'       => '',
];

$erreurs_contact = [];
$erreurs_projet = [];
$succes_contact = false;
$succes_projet = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formulaire = $_POST['formulaire'] ?? '';

    if ($formulaire === 'contact') {
        $contact['name']    = nettoyer($_POST['name'] ?? '');
        $contact['email']   = nettoyer($_POST['email'] ?? '');
        $contact['message'] = nettoyer($_POST['message'] ?? '');

        $raw_email = trim($_POST['email'] ?? '');

        if (!champ_requis($contact['name'])) {
            $erreurs_contact['name'] = 'Le nom est obligatoire.';
        }

        if (!champ_requis($raw_email)) {
            $erreurs_contact['email'] = 'L\'adresse e-mail est obligatoire.';
        } elseif (!filter_var($raw_email, FILTER_VALIDATE_EMAIL)) {
            $erreurs_contact['email'] = 'L\'adresse e-mail est invalide.';
        }

        if (!champ_requis($contact['message'])) {
            $erreurs_contact['message'] = 'Le message ne peut pas être vide.';
        }

        if (empty($erreurs_contact)) {
            $succes_contact = true;
        }
    }

    if ($formulaire === 'demande_projet') {
        $projet['project_name'] = nettoyer($_POST['project-name'] ?? '');
        $projet['description']  = nettoyer($_POST['description'] ?? '');
        $projet['budget']       = nettoyer($_POST['budget'] ?? '');

        if (!champ_requis($projet['project_name'])) {
            $erreurs_projet['project_name'] = 'Le nom du projet est obligatoire.';
        }

        if (!champ_requis($projet['description'])) {
            $erreurs_projet['description'] = 'La description est obligatoire.';
        }

        if (!champ_requis($projet['budget'])) {
            $erreurs_projet['budget'] = 'Le budget estimé est requis.';
        }

        if (empty($erreurs_projet)) {
            $succes_projet = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Shinouee</title>
    <link rel="stylesheet" href="css1/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require 'composants/navigation.php'; ?>
    <header>
        <button class="theme-toggle" onclick="document.body.classList.toggle('dark')">🌙 Mode sombre</button>
        <h1>Bienvenue sur mon portfolio</h1>
        <p>Je suis Haby Sow, aspirante hackeuse , curieuse du code , exploratrice des failles.</p>
    </header>

    <section class="contact">
        <h2>Contactez-moi</h2>

        <?php if ($succes_contact) : ?>
            <div class="success">Merci, votre message a bien été reçu.</div>
        <?php endif; ?>

        <form action="contact.php" method="post">
            <input type="hidden" name="formulaire" value="contact">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" value="<?= $contact['name'] ?>">
            <?php if (isset($erreurs_contact['name'])) : ?>
                <span class="error"><?= $erreurs_contact['name'] ?></span>
            <?php endif; ?>

            <label for="email">Email :</label>
            <input type="text" id="email" name="email" value="<?= $contact['email'] ?>">
            <?php if (isset($erreurs_contact['email'])) : ?>
                <span class="error"><?= $erreurs_contact['email'] ?></span>
            <?php endif; ?>

            <label for="message">Message :</label>
            <textarea id="message" name="message"><?= $contact['message'] ?></textarea>
            <?php if (isset($erreurs_contact['message'])) : ?>
                <span class="error"><?= $erreurs_contact['message'] ?></span>
            <?php endif; ?>

            <button type="submit">Envoyer</button>
        </form>

        <h2>Demande de projet</h2>

        <?php if ($succes_projet) : ?>
            <div class="success">
                Votre demande de projet a bien été prise en compte.
                <strong>Récapitulatif :</strong>
                <ul>
                    <li>Projet : <?= $projet['project_name'] ?></li>
                    <li>Description : <?= $projet['description'] ?></li>
                    <li>Budget : <?= $projet['budget'] ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <form action="contact.php" method="post">
            <input type="hidden" name="formulaire" value="demande_projet">
            <label for="project-name">Nom du projet :</label>
            <input type="text" id="project-name" name="project-name" value="<?= $projet['project_name'] ?>">
            <?php if (isset($erreurs_projet['project_name'])) : ?>
                <span class="error"><?= $erreurs_projet['project_name'] ?></span>
            <?php endif; ?>

            <label for="description">Description :</label>
            <textarea id="description" name="description"><?= $projet['description'] ?></textarea>
            <?php if (isset($erreurs_projet['description'])) : ?>
                <span class="error"><?= $erreurs_projet['description'] ?></span>
            <?php endif; ?>

            <label for="budget">Budget estimé :</label>
            <input type="text" id="budget" name="budget" value="<?= $projet['budget'] ?>">
            <?php if (isset($erreurs_projet['budget'])) : ?>
                <span class="error"><?= $erreurs_projet['budget'] ?></span>
            <?php endif; ?>

            <button type="submit">Envoyer demande</button>
        </form>
    </section>

    <footer>
        <p>© 2026 - SHINOUEE</p>
        <p>Aie de la détermination et dépasse ta génération. » – Cheikh Ahmadou Bamba</p>
       
        <div class="social-links">
            <a href="https://github.com/Shinoue-debug/Portfolio-Shinoue.git" target="_blank">GitHub</a>
            <a href="mailto:sowh07331@gmail.com">Email</a>
        </div>
    </footer>
</body>
</html>
