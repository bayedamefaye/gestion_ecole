<?php
// Seule cette adresse peut ouvrir une session sur le site.
// Définissez SCHOOL_ADMIN_PASSWORD_HASH dans l'environnement du serveur.
// Le hash se génère avec : php -r "echo password_hash('votre-mot-de-passe', PASSWORD_DEFAULT), PHP_EOL;"

function school_admin_email(): string
{
    return 'fayebayedame399@gmail.com';
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    // XAMPP peut pointer vers un dossier de sessions non accessible à l'utilisateur.
    $sessionDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'sessions';
    if (!is_dir($sessionDirectory)) {
        mkdir($sessionDirectory, 0700, true);
    }
    if (is_dir($sessionDirectory) && is_writable($sessionDirectory)) {
        session_save_path($sessionDirectory);
    }
    session_start();
}

function school_credentials_configured(): bool
{
    return filter_var(school_admin_email(), FILTER_VALIDATE_EMAIL) !== false
        && (getenv('SCHOOL_ADMIN_PASSWORD_HASH') ?: '') !== '';
}

function school_is_authenticated(): bool
{
    return isset($_SESSION['school_authenticated']) && $_SESSION['school_authenticated'] === true;
}

function school_users_file(): string
{
    return __DIR__ . DIRECTORY_SEPARATOR . 'utilisateurs.json';
}

function school_users(): array
{
    $file = school_users_file();
    if (!is_file($file)) {
        return [];
    }
    $contents = file_get_contents($file);
    $users = $contents === false ? null : json_decode($contents, true);
    return is_array($users) ? $users : [];
}

function school_save_users(array $users): bool
{
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return $json !== false && file_put_contents(school_users_file(), $json . PHP_EOL, LOCK_EX) !== false;
}

function school_registered_user(string $email): ?array
{
    foreach (school_users() as $user) {
        if (isset($user['email']) && hash_equals(strtolower($user['email']), strtolower($email))) {
            return $user;
        }
    }
    return null;
}

function school_require_authentication(): void
{
    if (!school_is_authenticated()) {
        header('Location: connexion.php');
        exit;
    }
}

function school_require_admin(): void
{
    school_require_authentication();
    if (($_SESSION['school_user_role'] ?? 'admin') !== 'admin') {
        http_response_code(403);
        exit('Accès réservé aux administrateurs.');
    }
}

function school_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}


