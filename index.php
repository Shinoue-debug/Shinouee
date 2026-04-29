<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Shinouee</title>
    <link rel="stylesheet" href="css1/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require 'composants/navigation.php'; ?>

    <main>
        <header>
            <button class="theme-toggle" onclick="document.body.classList.toggle('dark')">🌙 Mode sombre</button>
            <section class="hero">
                <h1>Bienvenue sur mon portfolio</h1>
                <p>Découvrez mes projets, mes compétences et mes réalisations. Tout est ici pour vous inspirer et montrer mon univers créatif !</p>
                <button class="cta-btn orange">Découvrir mes projets</button>
            </section>
        </header>

        <section class="hero">
            <h2>Welcome to my World</h2>
            <p>Découvrez mon expertise en développement web et cybersécurité.</p>
        </section>
    </main>

    <?php require 'composants/pied-de-page.php'; ?>
</body>
</html>
