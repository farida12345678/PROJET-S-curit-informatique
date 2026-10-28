<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';
$where  = '';

if ($search !== '') {
    // VULNÉRABLE : concaténation directe → SQLi possible
    $where = "WHERE p.title ILIKE '%$search%' OR p.content ILIKE '%$search%'";
}
?>

<h1>Articles récents</h1>

<div class="search-box card">
    <form method="GET" action="index.php">
        <input type="text" name="q" placeholder="Rechercher un article..." value="<?= $search ?>">
        <button type="submit">Rechercher</button>
    </form>
    <?php if ($search !== ''): ?>
        <p class="meta" style="margin-top:10px;">
            Résultats pour : <strong><?= $search ?></strong>
        </p>
    <?php endif; ?>
</div>

<?php
$sql = "SELECT p.*, u.username 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        $where
        ORDER BY p.created_at DESC";

try {
    $stmt = $pdo->query($sql);
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
    echo '<div class="alert alert-error">Erreur SQL : ' . htmlspecialchars($e->getMessage()) . '</div>';
}

if (count($posts) > 0):
    foreach ($posts as $post):
?>
    <article class="card">
        <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
        <p class="meta">
            Par <a href="profile.php?id=<?= $post['user_id'] ?>"><?= htmlspecialchars($post['username']) ?></a>
            • <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
        </p>
        <p><?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?>...</p>
        <a href="post.php?id=<?= $post['id'] ?>" class="btn">Lire la suite</a>
    </article>
<?php
    endforeach;
else:
?>
    <div class="card">
        <p>Aucun article trouvé.</p>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>