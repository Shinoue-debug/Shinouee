<?php require 'functions.php'; ?>
<?php
$projets = [
    [
        'titre'        => 'Poubelle Intelligente',
        'description'  => 'Un projet IoT utilisant des capteurs pour ouvrir automatiquement la poubelle et suivre son état.',
        'technologies' => ['HTML', 'CSS', 'JavaScript'],
        'media_type'   => 'video',
        'media_src'    => './poubelle.mp4',
    ],
    [
        'titre'        => 'Surveillance Maritime',
        'description'  => 'Une solution de surveillance marine avec capteurs météo et mouvement en temps réel.',
        'technologies' => ['HTML', 'CSS', 'JavaScript'],
        'media_type'   => 'video',
        'media_src'    => './marine.mp4',
    ],
    [
        'titre'        => 'Projet OpenSSL',
        'description'  => 'Compréhension des fondements de la cryptographie, génération de clés RSA et AES.',
        'technologies' => ['PHP', 'Cryptographie'],
        'media_type'   => 'image',
        'media_src'    => './openssl.jpeg',
    ],
    [
        'titre'        => 'Algorithme de répertoire',
        'description'  => 'Un programme de gestion de contacts permettant d’ajouter, modifier et supprimer des entrées.',
        'technologies' => ['Python', 'Algorithmes'],
        'media_type'   => 'image',
        'media_src'    => './algorepertoire.jpeg',
    ],
    [
        'titre'        => 'Projet Sen StarNet',
        'description'  => 'Réseau intelligent au Sénégal, optimisé pour la connectivité et la sécurité des données.',
        'technologies' => ['Réseaux', 'Sécurité'],
        'media_type'   => 'image',
        'media_src'    => './senstarnet.jpeg',
    ],
    [
        'titre'        => 'Teranga Délices',
        'description'  => 'Site de restaurant mettant en valeur une expérience culinaire authentique.',
        'technologies' => ['HTML', 'CSS', 'JavaScript'],
        'media_type'   => 'video',
        'media_src'    => './teranga.mp4',
    ],
    [
        'titre'        => 'Site vitrine entreprise',
        'description'  => 'Conception d’un site vitrine responsive pour présenter une entreprise technologique.',
        'technologies' => ['HTML', 'CSS', 'JavaScript'],
        'media_type'   => 'video',
        'media_src'    => './ataaba.mp4',
    ],
    [
        'titre'        => 'Robot Gaz',
        'description'  => 'Robot capable de détecter et réguler le gaz avec alertes et capteurs en temps réel.',
        'technologies' => ['Électronique', 'Python'],
        'media_type'   => 'video',
        'media_src'    => './gaz.mp4',
    ],
];

$mot_cle = nettoyer($_GET['q'] ?? '');
$resultats = [];

if ($mot_cle !== '') {
    foreach ($projets as $projet) {
        if (stripos($projet['titre'], $mot_cle) !== false || stripos($projet['description'], $mot_cle) !== false) {
            $resultats[] = $projet;
        }
    }
} else {
    $resultats = $projets;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets - Shinouee</title>
    <link rel="stylesheet" href="css1/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require 'composants/navigation.php'; ?>

    <main>
        <header>
            <button class="theme-toggle" onclick="document.body.classList.toggle('dark')">🌙 Mode sombre</button>
            <h1>Projets récents</h1>
            <p>« Quand on n’a rien à perdre, on peut tout accomplir. » – Naruto Uzumaki.</p>
        </header>

        <section class="featured-projects">
            <form class="search-bar" method="get" action="projets.php">
                <input type="search" id="search" name="q" value="<?= $mot_cle ?>" placeholder="Rechercher un projet...">
                <button type="submit">Rechercher</button>
            </form>

            <?php if ($mot_cle !== '') : ?>
                <p>Résultats pour « <?= $mot_cle ?> » : <?= count($resultats) ?> projet(s).</p>
            <?php endif; ?>

            <div class="projects">
                <?php foreach ($resultats as $projet) : ?>
                    <div class="project-card">
                        <?php if ($projet['media_type'] === 'video') : ?>
                            <video controls>
                                <source src="<?= $projet['media_src'] ?>" type="video/mp4">
                                Votre navigateur ne supporte pas la vidéo.
                            </video>
                        <?php else : ?>
                            <img src="<?= $projet['media_src'] ?>" alt="<?= nettoyer($projet['titre']) ?>">
                        <?php endif; ?>
                        <h3><?= nettoyer($projet['titre']) ?></h3>
                        <p><?= nettoyer($projet['description']) ?></p>
                        <div class="technologies">
                            <?php foreach ($projet['technologies'] as $tech) : ?>
                                <span class="badge"><?= nettoyer($tech) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($resultats)) : ?>
                <p>Aucun projet ne correspond à votre recherche.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php require 'composants/pied-de-page.php'; ?>
</body>
</html>
