
<?php
require_once __DIR__ . '/auth.php';
school_require_authentication();

if (!isset($_SESSION['eleve'])) {
    $_SESSION['eleve'] = [
        'nom' => 'Élève',
        
    ];
} 

$page = $_GET['page'] ?? 'dashboard';

$pages = [
    'dashboard' => 'Tableau de bord',
    'emploi' => 'Mon emploi du temps',

];

if (!array_key_exists($page, $pages)) {
    $page = 'dashboard';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Kadija - Espace Élève</title>
    <link rel="stylesheet" href="eleve.css">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>

<?php $activeMenu = 'eleve'; require __DIR__ . '/sidebar.php'; ?>

<main class="contenu">

    <a class="lien-tableau-bord" href="deux.php">← Retour au tableau de bord</a>

    <header class="entete">
        <h1><?= htmlspecialchars($pages[$page]) ?></h1>
        <p>Bienvenue dans votre espace élève.</p>
    </header>

    <?php if ($page === 'dashboard'): ?>

        <h2>Bonjour, <?= htmlspecialchars($_SESSION['eleve']['nom']) ?> !</h2>

        <div class="cartes">
            <div class="carte">
                <div class="icone bleu">♟</div>
                <div>
                    <h2>0</h2>
                    <p>Cours disponibles</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone rose">▦</div>
                <div>
                    <h2>0</h2>
                    <p>Cours aujourd'hui</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone vert">◷</div>
                <div>
                    <h2>0</h2>
                    <p>Évaluations</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone violet">★</div>
                <div>
                    <h2>--</h2>
                    <p>Moyenne générale</p>
                </div>
            </div>
        </div>

    <?php elseif ($page === 'emploi'): ?>
        <section class="section">
            <h2>Mon emploi du temps</h2>
            <p>Retrouvez ici les cours prévus pour votre classe.</p>
            <div class="table-container"><table>
                <thead><tr><th>Jour</th><th>Heure</th><th>Matière</th><th>Salle</th></tr></thead>
                <tbody><tr><td colspan="4" class="vide">Aucun cours programmé pour le moment.</td></tr></tbody>
            </table></div>
        </section>
    <?php endif; ?>

    <footer>
        © <?= date('Y') ?> School Kadija - Tous doits réservés
    </footer>

</main>

</body>
</html>





