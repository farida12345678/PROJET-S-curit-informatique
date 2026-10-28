<?php
require_once 'includes/config.php';
session_start();

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->query("SELECT id, username, email, created_at FROM users WHERE id = $user_id");
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Utilisateur introuvable.'];
    header('Location: index.php');
    exit;
}

$posts = $pdo->query("SELECT id, title, created_at FROM posts WHERE user_id = $user_id ORDER BY created_at DESC")->fetchAll();

require_once 'includes/header.php';
?>

<div class="card">
    <h2>Profil de <?= htmlspecialchars($user['username']) ?></h2>

    <div class="profile-info" style="margin-top: 15px;">
        <p><strong>ID :</strong> <?= $user['id'] ?></p>
        <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Membre depuis :</strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
    </div>
</div>

<div class="card">
    <h3>Articles publiés</h3>
    <?php if (count($posts) > 0): ?>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($posts as $p): ?>
                <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                    <a href="post.php?id=<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></a>
                    <span class="meta"> – <?= date('d/m/Y', strtotime($p['created_at'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="meta">Aucun article publié.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>