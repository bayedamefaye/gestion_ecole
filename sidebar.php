<?php
$activeMenu = $activeMenu ?? '';
$menus = [
    'dashboard' => ['deux.php', 'Tableau de bord'],
    'eleve' => ['eleve.php', 'Élèves'],
    'enseignant' => ['enseignant.php', 'Enseignant'],
    'directeur' => ['directeur.php', 'Directeur'],
    'parametres' => ['param.php', 'Paramètres'],
];
?>
<aside class="sidebar">
    <div class="logo">
        <span class="logo-mark">SK</span>
        <h2>School Kadija</h2>
    </div>
    <div class="profil">
        <div class="avatar">A</div>
        <h3><?= htmlspecialchars($_SESSION['school_user_name'] ?? 'Administrateur', ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= ($_SESSION['school_user_role'] ?? 'admin') === 'admin' ? 'Administrateur' : 'Utilisateur' ?></p>
    </div>
    <nav class="menu" aria-label="Navigation principale">
        <?php foreach ($menus as $key => [$url, $label]): ?>
            <a href="<?= htmlspecialchars($url) ?>" class="<?= $activeMenu === $key ? 'active' : '' ?>" <?= $activeMenu === $key ? 'aria-current="page"' : '' ?>><?= htmlspecialchars($label) ?></a>
        <?php endforeach; ?>
        <?php if ($activeMenu !== 'parametres'): ?>
            <a href="connexion.php?page=deconnexion" class="deconnexion">Se déconnecter</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-footer">Gestion scolaire</div>
</aside>
