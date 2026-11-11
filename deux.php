<?php
require_once __DIR__ . '/auth.php';
school_require_authentication();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Kadija - Tableau de bord</title>
    <link rel="stylesheet" href="deux.css">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>

    <div class="application">

        <?php $activeMenu = 'dashboard'; require __DIR__ . '/sidebar.php'; ?>

        <main class="contenu">

            <header class="topbar">
                <div>
                    <h1>Tableau de bord</h1>
                    <p>Bienvenue dans votre espace de gestion scolaire.</p>
                </div>

                <div class="annee">
                    Année scolaire<br>
                    <strong>2026 - 2027</strong>
                </div>
            </header>

            <!-- <section class="bienvenue">
                <h2>Bonjour, Administrateur !</h2>
                <p>Voici un aperçu de votre établissement.</p>
            </section> -->

            <section class="statistiques">

                <div class="carte">
                    <div class="icone bleu" aria-hidden="true">▦</div>
                    <div>
                        <h3>0</h3>
                        <p>Total des élèves</p>
                    </div>
                </div>

                <div class="carte">
                    <div class="icone orange" aria-hidden="true">＋</div>
                    <div>
                        <h3>0</h3>
                        <p>Inscriptions</p>
                    </div>
                </div>

                <div class="carte">
                    <div class="icone vert" aria-hidden="true">✓</div>
                    <div>
                        <h3>0</h3>
                        <p>Membres du personnel</p>
                    </div>
                </div>

                <div class="carte">
                    <div class="icone violet" aria-hidden="true">▤</div>
                    <div>
                        <h3>0</h3>
                        <p>Classes</p>
                    </div>
                </div>

            </section>

            <!-- <section class="section-eleves">

                <div class="titre-section">
                    <div>
                        <h2>Dernières inscriptions</h2>
                        <p>Consultez les inscriptions récentes.</p>
                    </div>

                    <a href="inscriptions.html" class="bouton">
                        Voir les inscriptions
                    </a>
                </div>

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
                                    Aucune inscription pour le moment.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section> -->

            <footer>
                <p>© 2026 School Kadija - Gestion scolaire</p>
            </footer>

        </main>

    </div>

</body>
</html>




