<?php
require_once __DIR__ . '/auth.php';
school_require_admin();

$message = '';
$messageType = 'success';
$users = school_users();

if (empty($_SESSION['school_users_csrf'])) {
    $_SESSION['school_users_csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'ajouter';
    if (!hash_equals($_SESSION['school_users_csrf'], $_POST['csrf_token'] ?? '')) {
        $message = 'Votre session a expiré. Rechargez la page puis réessayez.';
        $messageType = 'error';
    } elseif ($action === 'supprimer') {
        $emailASupprimer = strtolower(trim($_POST['email_a_supprimer'] ?? ''));
        $utilisateursFiltres = array_values(array_filter($users, static function ($user) use ($emailASupprimer) {
            return strtolower($user['email'] ?? '') !== $emailASupprimer;
        }));
        if (count($utilisateursFiltres) === count($users)) {
            $message = 'Cet utilisateur n’a pas été trouvé.';
            $messageType = 'error';
        } elseif (school_save_users($utilisateursFiltres)) {
            $_SESSION['school_users_csrf'] = bin2hex(random_bytes(32));
            header('Location: param.php?supprime=1#utilisateurs');
            exit;
        } else {
            $message = 'Impossible de supprimer le compte. Vérifiez les droits d’écriture du dossier.';
            $messageType = 'error';
        }
    } else {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $motdepasse = $_POST['motdepasse'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($nom === '' || $prenom === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, ['admin', 'utilisateur'], true)) {
        $message = 'Saisissez un nom, un prénom, une adresse e-mail valide et un rôle.';
        $messageType = 'error';
    } elseif (strlen($motdepasse) < 8) {
        $message = 'Le mot de passe doit contenir au moins 8 caractères.';
        $messageType = 'error';
    } elseif (hash_equals(strtolower(school_admin_email()), $email) || school_registered_user($email) !== null) {
        $message = 'Cette adresse e-mail est déjà utilisée.';
        $messageType = 'error';
    } else {
        $users[] = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'role' => $role,
            'password_hash' => password_hash($motdepasse, PASSWORD_DEFAULT),
            'created_at' => date(DATE_ATOM),
        ];
        if (school_save_users($users)) {
            $_SESSION['school_users_csrf'] = bin2hex(random_bytes(32));
            header('Location: param.php?ajoute=1#utilisateurs');
            exit;
        }
        array_pop($users);
        $message = 'Impossible d’enregistrer le compte. Vérifiez les droits d’écriture du dossier.';
        $messageType = 'error';
    }
    }
}

if (isset($_GET['ajoute'])) {
    $message = 'Le compte utilisateur a été créé. Il peut maintenant se connecter avec son adresse e-mail et son mot de passe.';
}
if (isset($_GET['supprime'])) {
    $message = 'Le compte utilisateur a été supprimé.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - School Kadija</title>
    <link rel="stylesheet" href="param.css">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>
<div class="application">
    <?php $activeMenu = 'parametres'; require __DIR__ . '/sidebar.php'; ?>

    <main class="contenu">
        <header class="topbar">
            <div>
                <p class="sur-titre">ADMINISTRATION</p>
                <h1>Paramètres</h1>
                <p>Gérez les utilisateurs, les profils et les menus de l’application.</p>
            </div>
            <a class="retour" href="deux.php">← Tableau de bord</a>
        </header>

        <nav class="sections" aria-label="Sections des paramètres">
            <a href="#utilisateurs">Gestion des utilisateurs</a>
            <a href="#profils">Gestion des profils</a>
            <a href="#menus">Gestion des menus</a>
        </nav>

        <section class="section" id="utilisateurs">
            <div class="section-titre">
                <div><h2>Gestion des utilisateurs</h2><p>Consultez et préparez les comptes de votre établissement.</p></div>
                <button type="button" class="bouton" id="ouvrir-formulaire" aria-expanded="false" aria-controls="formulaire-utilisateur">＋ Ajouter un utilisateur</button>
            </div>

            <?php if ($message !== ''): ?>
                <div class="notice <?= htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8') ?>" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form class="formulaire-utilisateur" id="formulaire-utilisateur" method="post" action="param.php#utilisateurs" hidden>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['school_users_csrf'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="champs-utilisateur">
                    <div class="champ"><label for="prenom">Prénom</label><input id="prenom" name="prenom" autocomplete="given-name" maxlength="100" required></div>
                    <div class="champ"><label for="nom">Nom</label><input id="nom" name="nom" autocomplete="family-name" maxlength="100" required></div>
                    <div class="champ"><label for="email">Adresse e-mail</label><input type="email" id="email" name="email" autocomplete="email" maxlength="254" required></div>
                    <div class="champ"><label for="motdepasse">Mot de passe</label><input type="password" id="motdepasse" name="motdepasse" autocomplete="new-password" minlength="8" required><small>8 caractères minimum.</small></div>
                    <div class="champ"><label for="role">Rôle du compte</label><select id="role" name="role" required><option value="">Choisir un rôle</option><option value="admin">Administrateur</option><option value="utilisateur">Utilisateur</option></select></div>
                </div>
                <div class="actions-formulaire"><button type="submit" class="bouton">Créer le compte</button><button type="button" class="bouton secondaire" id="fermer-formulaire">Annuler</button></div>
            </form>

            <div class="table-container"><table>
                <thead><tr><th>Nom et prénom</th><th>Adresse e-mail</th><th>Profil</th><th>Statut</th><th>Action</th></tr></thead>
                <tbody>
                <?php if ($users === []): ?>
                    <tr><td colspan="5" class="vide">Aucun utilisateur enregistré pour le moment.</td></tr>
                <?php else: foreach (array_reverse($users) as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars(trim(($user['nom'] ?? '') . ' ' . ($user['prenom'] ?? '')), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= ($user['role'] ?? 'utilisateur') === 'admin' ? 'Administrateur' : 'Utilisateur' ?></td>
                        <td><span class="etat">Actif</span></td>
                        <td><form method="post" action="param.php#utilisateurs" onsubmit="return confirm('Confirmer la suppression de cet utilisateur ?')"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['school_users_csrf'], ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="action" value="supprimer"><input type="hidden" name="email_a_supprimer" value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"><button type="submit" class="bouton-danger">Supprimer</button></form></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table></div>
        </section>

        <!-- <section class="section" id="profils">
            <div class="section-titre"><div><h2>Gestion des profils</h2><p>Les profils définissent le type d’accès associé à chaque utilisateur.</p></div></div>
            <div class="profils">
                <article class="profil-carte"><span class="badge">Administrateur</span><h3>Administration complète</h3><p>Accès aux utilisateurs, aux profils, aux menus et aux espaces de gestion.</p></article>
                <article class="profil-carte"><span class="badge bleu">Directeur</span><h3>Gestion de l’établissement</h3><p>Accès au tableau de bord et aux informations de l’établissement.</p></article>
                <article class="profil-carte"><span class="badge vert">Enseignant</span><h3>Espace pédagogique</h3><p>Accès aux classes, à l’emploi du temps et aux évaluations.</p></article>
                <article class="profil-carte"><span class="badge orange">Élève</span><h3>Espace élève</h3><p>Accès au tableau de bord et à l’emploi du temps.</p></article>
            </div>
        </section>

        <section class="section" id="menus">
            <div class="section-titre"><div><h2>Gestion des menus</h2><p>Repérez les menus disponibles dans les espaces de l’application.</p></div></div>
            <div class="table-container"><table>
                <thead><tr><th>Menu</th><th>Espace</th><th>Visibilité</th></tr></thead>
                <tbody>
                    <tr><td>Tableau de bord</td><td>Administration</td><td><span class="etat">Disponible</span></td></tr>
                    <tr><td>Élèves</td><td>Administration</td><td><span class="etat">Disponible</span></td></tr>
                    <tr><td>Enseignant</td><td>Administration</td><td><span class="etat">Disponible</span></td></tr>
                    <tr><td>Directeur</td><td>Administration</td><td><span class="etat">Disponible</span></td></tr>
                    <tr><td>Paramètres</td><td>Administration</td><td><span class="etat">Disponible</span></td></tr>
                </tbody>
            </table></div>
        </section> -->
        <footer>© <?= date('Y') ?> School Kadija - Gestion scolaire</footer>
    </main>
</div>
<script>
const formulaireUtilisateur = document.getElementById('formulaire-utilisateur');
const boutonOuvrir = document.getElementById('ouvrir-formulaire');
function afficherFormulaire(visible) {
    formulaireUtilisateur.hidden = !visible;
    boutonOuvrir.setAttribute('aria-expanded', String(visible));
    if (visible) document.getElementById('prenom').focus();
}
boutonOuvrir.addEventListener('click', () => afficherFormulaire(formulaireUtilisateur.hidden));
document.getElementById('fermer-formulaire').addEventListener('click', () => afficherFormulaire(false));
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $messageType === 'error'): ?>
afficherFormulaire(true);
<?php endif; ?>
</script>
</body>
</html>
