<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");
if (!$conn) {
    die("Erro na conexão com o PostgreSQL.");
}

$result = pg_query($conn, "SELECT * FROM convidados ORDER BY id ASC");

echo "<h2>Lista de Convidados</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>ID</th><th>Nome</th><th>Fralda</th><th>Mimo</th></tr>";

while ($row = pg_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['nome']."</td>";
    echo "<td>".$row['fralda']."</td>";
    echo "<td>".$row['mimo']."</td>";
    echo "</tr>";
}

echo "</table>";
pg_close($conn);
?>