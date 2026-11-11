<?php
require_once __DIR__ . '/auth.php';

$message = '';

if (isset($_GET['page']) && $_GET['page'] === 'deconnexion') {
    school_logout();
    header('Location: connexion.php');
    exit;
}

if (school_is_authenticated()) {
    header('Location: deux.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $motdepasse === '') {
        $message = 'Veuillez saisir une adresse e-mail valide et votre mot de passe.';
    } elseif (!school_credentials_configured()) {
        $message = 'La connexion n’est pas encore configurée. Contactez l’administrateur du site.';
    } elseif (hash_equals(strtolower(school_admin_email()), strtolower($email))
        && school_credentials_configured()
        && password_verify($motdepasse, getenv('SCHOOL_ADMIN_PASSWORD_HASH'))) {
        session_regenerate_id(true);
        $_SESSION['school_authenticated'] = true;
        $_SESSION['school_user_name'] = 'Administrateur';
        $_SESSION['school_user_role'] = 'admin';
        header('Location: deux.php');
        exit;
    } else {
        $user = school_registered_user($email);
        if ($user !== null && password_verify($motdepasse, $user['password_hash'] ?? '')) {
            session_regenerate_id(true);
            $_SESSION['school_authenticated'] = true;
            $_SESSION['school_user_name'] = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
            $_SESSION['school_user_role'] = ($user['role'] ?? 'utilisateur') === 'admin' ? 'admin' : 'utilisateur';
            header('Location: deux.php');
            exit;
        }
        $message = 'Adresse e-mail ou mot de passe incorrect.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - School Kadija</title>
    <link rel="stylesheet" href="connexion.css">
</head>
<body>

<header class="entete">
    <a href="accueil.php" class="logo">
        <span class="logo-icon">SK</span>
        <span>School Kadija</span>
    </a>

    <a href="accueil.php" class="retour">← Retour à l'accueil</a>
</header>

<main class="conteneur">

    <section class="carte-connexion">

        <div class="icone-connexion">SK</div>

        <h1>Bienvenue !</h1>
        <p class="description">
            Connectez-vous à votre espace scolaire.
        </p>

        <?php if ($message !== ''): ?>
            <div class="message">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="connexion.php" method="post">

            <div class="champ">
                <label for="email">Adresse e-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="exemple@email.com"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="champ">
                <label for="motdepasse">Mot de passe</label>
                <input
                    type="password"
                    id="motdepasse"
                    name="motdepasse"
                    placeholder="Entrez votre mot de passe"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="bouton">
                Se connecter
            </button>

        </form>

        <a href="accueil.php" class="lien-accueil">
            ← Retour à l'accueil
        </a>

    </section>

</main>

<footer>
    © <?= date('Y') ?> School Kadija - Tous doits réservés
</footer>

</body>
</html>




