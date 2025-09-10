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
    // Conectar usando PDO
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ---------- CRIAR TABELA ---------- //
    $sql = "
    CREATE TABLE IF NOT EXISTS convidados (
        id SERIAL PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        fralda VARCHAR(10) NOT NULL,
        mimo VARCHAR(50) NOT NULL
    );
    ";
    $pdo->exec($sql);

    // Captura os dados do formulário
    $nome   = $_POST['nome'];
    $fralda = $_POST['fralda'];
    $mimo   = $_POST['mimo'];

    // ---------- LIMITES ---------- //
    $limites = [
        "P" => 10,
        "M" => 25,
        "G" => 25,
        "Lenço Umedecido" => 25,
        "Pomada" => 25,
        "Sabonete Líquido" => 10
    ];

    // Conta quantos já escolheram a mesma fralda
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM convidados WHERE fralda = :fralda");
    $stmt->execute([':fralda' => $fralda]);
    $totalFralda = $stmt->fetchColumn();

    // Conta quantos já escolheram o mesmo mimo
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM convidados WHERE mimo = :mimo");
    $stmt->execute([':mimo' => $mimo]);
    $totalMimo = $stmt->fetchColumn();

    // Verifica limites
    if ($totalFralda >= $limites[$fralda]) {
        die("❌ Limite atingido para a fralda $fralda! Escolha outra opção.");
    }
    if ($totalMimo >= $limites[$mimo]) {
        die("❌ Limite atingido para o mimo $mimo! Escolha outra opção.");
    }

    // Insere no banco
    $stmt = $pdo->prepare("INSERT INTO convidados (nome, fralda, mimo) VALUES (:nome, :fralda, :mimo)");
    $stmt->execute([
        ':nome' => $nome,
        ':fralda' => $fralda,
        ':mimo' => $mimo
    ]);

    echo "<p style='font-size: 6vw; font-weight: bold;'>
🎉 Obrigado, $nome! Sua presença foi confirmada.
</p>";

} catch (PDOException $e) {
    echo "Erro ao conectar ou salvar: " . $e->getMessage();
}
?>




