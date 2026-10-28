<?php
require_once 'includes/config.php';
session_start();

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->query("SELECT p.*, u.username 
                     FROM posts p 
                     JOIN users u ON p.user_id = u.id 
                     WHERE p.id = $post_id");
$post = $stmt->fetch();

if (!$post) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Article introuvable.'];
    header('Location: index.php');
    exit;
}

// Nouveau commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $content = $_POST['content'] ?? '';

    if (trim($content) !== '') {
        $user_id = $_SESSION['user_id'];
        // VULNÉRABLE : XSS Stored (on échappe seulement les quotes SQL)
        $content_escaped = str_replace("'", "''", $content);
        $sql = "INSERT INTO comments (post_id, user_id, content) 
                VALUES ($post_id, $user_id, '$content_escaped')";
        $pdo->exec($sql);
        header("Location: post.php?id=$post_id");
        exit;
    }
}

require_once 'includes/header.php';
?>

<article class="card">
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <p class="meta">
        Par <a href="profile.php?id=<?= $post['user_id'] ?>"><?= htmlspecialchars($post['username']) ?></a>
        • <?= date('d/m/Y à H:i', strtotime($post['created_at'])) ?>
    </p>
    <div style="margin-top: 15px;">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>
</article>

<section class="card">
    <h3>Commentaires</h3>

    <?php
    $comments = $pdo->query("SELECT c.*, u.username 
                             FROM comments c 
                             JOIN users u ON c.user_id = u.id 
                             WHERE c.post_id = $post_id 
                             ORDER BY c.created_at ASC")->fetchAll();

    if (count($comments) > 0):
        foreach ($comments as $c):
    ?>
        <div class="comment">
            <span class="author"><?= htmlspecialchars($c['username']) ?></span>
            <span class="meta">• <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></span>
            <div style="margin-top: 6px;">
                <!-- VULNÉRABLE : XSS STORED - pas de htmlspecialchars() -->
                <?= $c['content'] ?>
            </div>
        </div>
    <?php
        endforeach;
    else:
    ?>
        <p class="meta">Aucun commentaire pour le moment.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e2e8f0;">
        <h4>Ajouter un commentaire</h4>
        <form method="POST" action="post.php?id=<?= $post_id ?>">
            <div class="form-group">
                <textarea name="content" placeholder="Votre commentaire..." required></textarea>
            </div>
            <button type="submit">Publier</button>
        </form>
    <?php else: ?>
        <p style="margin-top: 15px;">
            <a href="login.php">Connectez-vous</a> pour commenter.
        </p>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>