<?php
require_once __DIR__ . '/auth.php';
school_require_authentication();

$page = $_GET['page'] ?? 'dashboard';

$pages = [
    'dashboard' => 'Tableau de bord',
    'classes' => 'Mes classes',
    'emploi' => 'Mon emploi du temps',
    'evaluations' => 'Mes évaluations'
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
    <title>School Kadija - Espace Enseignant</title>
    <link rel="stylesheet" href="enseignant.css">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>

<?php $activeMenu = 'enseignant'; require __DIR__ . '/sidebar.php'; ?>

<main class="contenu">

    <a class="lien-tableau-bord" href="deux.php">← Retour au tableau de bord</a>

    <header class="entete">
        <h1><?= htmlspecialchars($pages[$page]) ?></h1>
        <p>Bienvenue dans votre espace enseignant.</p>
    </header>

    <?php if ($page === 'dashboard'): ?>

        <h2>Bonjour, cher enseignant !</h2>

        <div class="cartes">

            <div class="carte">
                <div class="icone bleu" aria-hidden="true">▦</div>
                <div>
                    <h2>0</h2>
                    <p>Mes classes</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone rose" aria-hidden="true">♙</div>
                <div>
                    <h2>0</h2>
                    <p>Total des élèves</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone vert" aria-hidden="true">✓</div>
                <div>
                    <h2>0</h2>
                    <p>Cours aujourd'hui</p>
                </div>
            </div>

            <div class="carte">
                <div class="icone violet" aria-hidden="true">▤</div>
                <div>
                    <h2>0</h2>
                    <p>évaluations à préparer</p>
                </div>
            </div>

        </div>

        <section class="section">
            <h2>Mon espace pédagogique</h2>
            <p>Consultez vos classes, votre emploi du temps et gérez les évaluations de vos élèves.</p>

            <div class="raccourcis">
                <a href="enseignant.php?page=classes">Consulter mes classes</a>
                <a href="enseignant.php?page=emploi">Voir mon emploi du temps</a>
                <a href="enseignant.php?page=evaluations">Gérer les évaluations</a>
            </div>
        </section>

    <?php elseif ($page === 'classes'): ?>

        <section class="section">
            <h2>Mes classes et mes élèves</h2>
            <p>Retrouvez les élèves de vos classes.</p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nom et prénom</th>
                            <th>Date de naissance</th>
                            <th>Classe</th>
                            <th>Téléphone du parent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="vide">
                                Aucun élève disponible pour le moment.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    <?php elseif ($page === 'emploi'): ?>

        <section class="section">
            <h2>Mon emploi du temps</h2>
            <p>Consultez vos cours programmés.</p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Heure</th>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Salle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="vide">
                                Aucun cours programmé pour le moment.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    <?php elseif ($page === 'evaluations'): ?>

        <section class="section">
            <h2>Mes évaluations</h2>
            <p>Consultez les évaluations et les notes des élèves.</p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>évaluation</th>
                            <th>Matière</th>
                            <th>Classe</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="vide">
                                Aucune évaluation enregistrée pour le moment.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    <?php endif; ?>

    <footer>
        © <?= date('Y') ?> School Kadija - Tous doits réservés
    </footer>

</main>

</body>
</html>




