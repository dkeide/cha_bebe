<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

$dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['id'])) {
        die("ID não fornecido.");
    }

    $id = (int)$_GET['id']; // garantir que é um número

    // Deletar convidado
    $stmt = $pdo->prepare("DELETE FROM convidados WHERE id = :id");
    $stmt->execute([':id' => $id]);

    // Redireciona de volta para a lista
    header("Location: lista.php");
    exit;

} catch (PDOException $e) {
    die("Erro ao conectar ou excluir: " . $e->getMessage());
}
?>