
<?php
require_once __DIR__ . '/auth.php';
school_require_authentication();

$page = $_GET['page'] ?? 'dashboard';

$pages = [
    'dashboard' => 'Tableau de bord',
    'classes' => 'Gestion des classes',
    'personnel' => 'Gestion du personnel',
    'fiche' => 'Fiche établissement'
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
    <title>School Kadija - Espace Directeur</title>
    <link rel="stylesheet" href="directeur.css">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>

<?php $activeMenu = 'directeur'; require __DIR__ . '/sidebar.php'; ?>

<main class="contenu">

    <a class="lien-tableau-bord" href="deux.php">← Retour au tableau de bord</a>

    <header class="entete">
        <h1><?= htmlspecialchars($pages[$page]) ?></h1>
        <p>Bienvenue dans votre espace de gestion scolaire.</p>
    </header>

    <?php if ($page === 'dashboard'): ?>


        <div class="cartes">

            <div class="carte">
                <div class="icone bleu" aria-hidden="true">▦</div>
                <div>
                    <h2>0</h2>
                    <p>Total des classes</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone rose" aria-hidden="true">♙</div>
                <div>
                    <h2>0</h2>
                    <p>Membres du personnel</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone vert" aria-hidden="true">✓</div>
                <div>
                    <h2>School Kadija</h2>
                    <p>établissement scolaire</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone violet" aria-hidden="true">▤</div>
                <div>
                    <h2>Administration</h2>
                    <p>Espace directeur</p>
                </div>
            </div>

        </div>

        <section class="section">
            <h2>Gestion de l'établissement</h2>
            <p>Accédez rapidement aux principales fonctionnalités de votre espace.</p>

           
        </section>

    <?php elseif ($page === 'classes'): ?>

        <section class="section">
            <h2>Liste des classes</h2>
            <p>Consultez les classes de l'établissement.</p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nom de la classe</th>
                            <th>Niveau</th>
                            <th>Effectif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="vide">
                                Aucune classe enregistrée pour le moment.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    <?php elseif ($page === 'personnel'): ?>

        <section class="section">
            <h2>Liste du personnel</h2>
            <p>Consultez les informations des membres du personnel.</p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nom et prénom</th>
                            <th>Fonction</th>
                            <th>Téléphone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="vide">
                                Aucun membre du personnel enregistré.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    <?php elseif ($page === 'fiche'): ?>

        <section class="section">
            <h2>Fiche de l'établissement</h2>

            <div class="fiche">
                <div class="champ">
                    <label>Nom de l'établissement</label>
                    <p>School Kadija</p>
                </div>

                <div class="champ">
                    <label>Type d'établissement</label>
                    <p>établissement scolaire</p>
                </div>

                <div class="champ">
                    <label>Directeur</label>
                    <p>À renseigner</p>
                </div>

                <div class="champ">
                    <label>Adresse</label>
                    <p>À renseigner</p>
                </div>

                <div class="champ">
                    <label>Téléphone</label>
                    <p>À renseigner</p>
                </div>

                <div class="champ">
                    <label>Adresse e-mail</label>
                    <p>À renseigner</p>
                </div>
            </div>

            <p class="note">
                Les informations de cette fiche pourront être complétées
                et modifiées dans une prochaine étape.
            </p>
        </section>

    <?php endif; ?>

    <footer>
        © <?= date('Y') ?> School Kadija - Tous doits réservés
    </footer>

</main>

</body>
</html>




