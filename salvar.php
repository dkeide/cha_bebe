<?php
// Configuração do banco usando variáveis de ambiente
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

// Conexão com o banco
$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if (!$conn) {
    die("Erro na conexão com o PostgreSQL.");
}

// ---------- CRIAR TABELA  ---------- //
$criarTabela = "
CREATE TABLE IF NOT EXISTS convidados (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    fralda VARCHAR(10) NOT NULL,
    mimo VARCHAR(50) NOT NULL
);
";
pg_query($conn, $criarTabela);

// Captura os dados do formulário
$nome    = $_POST['nome'];
$fralda  = $_POST['fralda'];
$mimo    = $_POST['mimo'];

// ---------- LIMITES ---------- //
$limites = [
    "P" => 15,
    "M" => 25,
    "G" => 20,
    "Lenço Umedecido" => 25,
    "Pomada" => 25,
    "Sabonete Líquido" => 10
];

// Conta quantos já escolheram a mesma fralda
$res_fralda = pg_query_params($conn,
    "SELECT COUNT(*) as total FROM convidados WHERE fralda = $1",
    [$fralda]
);
$row_fralda = pg_fetch_assoc($res_fralda);

// Conta quantos já escolheram o mesmo mimo
$res_mimo = pg_query_params($conn,
    "SELECT COUNT(*) as total FROM convidados WHERE mimo = $1",
    [$mimo]
);
$row_mimo = pg_fetch_assoc($res_mimo);

// Verifica limites
if ($row_fralda['total'] >= $limites[$fralda]) {
    die("❌ Limite atingido para a fralda $fralda! Escolha outra opção.");
}
if ($row_mimo['total'] >= $limites[$mimo]) {
    die("❌ Limite atingido para o mimo $mimo! Escolha outra opção.");
}

// Se não passou do limite, insere no banco
$result = pg_query_params($conn,
    "INSERT INTO convidados (nome, fralda, mimo) VALUES ($1, $2, $3)",
    [$nome, $fralda, $mimo]
);

if ($result) {
    echo "🎉 Obrigado, $nome! Sua presença foi confirmada.";
} else {
    echo "Erro ao salvar: " . pg_last_error($conn);
}

pg_close($conn);
?>
