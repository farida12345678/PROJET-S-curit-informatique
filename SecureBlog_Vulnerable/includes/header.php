<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureBlog - Version Vulnérable</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="index.php" class="logo">SecureBlog <span class="badge">VULN</span></a>
            <nav>
                <a href="index.php">Accueil</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="new_post.php">Nouvel article</a>
                    <a href="profile.php?id=<?= $_SESSION['user_id'] ?>">Mon profil</a>
                    <a href="logout.php">Déconnexion (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                <?php else: ?>
                    <a href="login.php">Connexion</a>
                    <a href="register.php">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                <?= $_SESSION['flash']['message'] ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>