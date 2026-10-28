<?php
require_once 'includes/config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        $check = $pdo->query("SELECT id FROM users WHERE username = '$username' OR email = '$email'");
        
        if ($check && $check->fetch()) {
            $error = 'Ce nom d\'utilisateur ou cet email est déjà utilisé.';
        } else {
            // VULNÉRABLE : mot de passe stocké EN CLAIR
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            
            try {
                $pdo->exec($sql);
                $_SESSION['flash'] = [
                    'type'    => 'success',
                    'message' => 'Compte créé avec succès ! Vous pouvez vous connecter.'
                ];
                header('Location: login.php');
                exit;
            } catch (PDOException $e) {
                $error = 'Erreur lors de la création du compte.';
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="card" style="max-width: 420px; margin: 40px auto;">
    <h2>Inscription</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Créer mon compte</button>
    </form>

    <p style="margin-top: 15px; font-size: 0.9rem;">
        Déjà inscrit ? <a href="login.php">Se connecter</a>
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>