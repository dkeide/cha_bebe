<?php
// Pega as variáveis de ambiente
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

// Mostra o que está sendo usado (sem senha, por segurança)
echo "<h3>🔎 Testando conexão com o banco...</h3>";
echo "Host: $host<br>";
echo "Port: $port<br>";
echo "Database: $db<br>";
echo "User: $user<br><br>";

// Tenta conectar
$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if ($conn) {
    echo "✅ Conexão bem-sucedida com o banco <strong>$db</strong>!";
} else {
    echo "❌ Erro na conexão: " . pg_last_error();
}
?>