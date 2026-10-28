<?php
/**
 * SecureBlog - Version VULNÉRABLE
 * Configuration pour Docker + PostgreSQL
 */

$host = getenv('DB_HOST') ?: 'db';
$port = getenv('DB_PORT') ?: '5432';
$name = getenv('DB_NAME') ?: 'secureblog_vuln';
$user = getenv('DB_USER') ?: 'secureblog';
$pass = getenv('DB_PASS') ?: 'secureblog123';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$name";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à PostgreSQL : " . $e->getMessage());
}
?>