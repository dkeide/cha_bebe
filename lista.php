<?php
// Configuração do banco usando variáveis de ambiente
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

// DSN para PDO
$dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca todos os convidados
    $stmt = $pdo->query("SELECT * FROM convidados ORDER BY id ASC");
    $convidados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contagem por fralda
    $stmt = $pdo->query("SELECT fralda, COUNT(*) as total FROM convidados GROUP BY fralda");
    $fraldas = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Contagem por mimo
    $stmt = $pdo->query("SELECT mimo, COUNT(*) as total FROM convidados GROUP BY mimo");
    $mimos = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Convidados - Chá de Bebê</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #ffe6f2; text-align: center; font-size: 120%; }
        table { margin: 20px auto; border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #cc0066; padding: 8px; }
        th { background-color: #cc0066; color: #fff; }
        h2 { color: #cc0066; }
    </style>
</head>
<body>
    <h2>🎀 Lista de Convidados</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Fralda</th>
            <th>Mimo</th>
        </tr>
        <?php foreach($convidados as $c): ?>
        <tr>
    <td><?= htmlspecialchars($c['id']) ?></td>
    <td><?= htmlspecialchars($c['nome']) ?></td>
    <td><?= htmlspecialchars($c['fralda']) ?></td>
    <td><?= htmlspecialchars($c['mimo']) ?></td>
    <td><a href="excluir.php?id=<?= $c['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">❌ Excluir</a></td>
</tr>
        <?php endforeach; ?>
    </table>

    <h2>📊 Contagem</h2>
    <p><strong>Fraldas:</strong></p>
    <ul>
        <li>P: <?= $fraldas['P'] ?? 0 ?></li>
        <li>M: <?= $fraldas['M'] ?? 0 ?></li>
        <li>G: <?= $fraldas['G'] ?? 0 ?></li>
    </ul>

    <p><strong>Mimos:</strong></p>
    <ul>
        <li>Lenço Umedecido: <?= $mimos['Lenço Umedecido'] ?? 0 ?></li>
        <li>Pomada: <?= $mimos['Pomada'] ?? 0 ?></li>
        <li>Sabonete Líquido: <?= $mimos['Sabonete Líquido'] ?? 0 ?></li>
    </ul>
</body>
</html>

