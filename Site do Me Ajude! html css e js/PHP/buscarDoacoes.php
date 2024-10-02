<?php
include 'db_connection.php'; // Inclua sua conexão com o banco de dados

// Recebendo o CPF do usuário via POST e removendo formatação, se necessário
$cpfUsuario = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : '';

// Buscando o histórico de doações com base no CPF, vinculado ao id_usuario
$queryDoacoes = "
    SELECT d.* 
    FROM doacoes d
    JOIN usuarios u ON d.id_usuario = u.id
    WHERE u.cpf = :cpf";

$stmtDoacoes = $conn->prepare($queryDoacoes);
$stmtDoacoes->bindValue(':cpf', $cpfUsuario);
$stmtDoacoes->execute();
$doacoes = $stmtDoacoes->fetchAll(PDO::FETCH_ASSOC);

// Verificando se o usuário possui doações registradas
if ($doacoes && count($doacoes) > 0) {
    echo "<h2>Histórico de Doações</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Valor</th>
                <th>Data da Doação</th>
                <th>Descrição</th>
            </tr>";

    foreach ($doacoes as $doacao) {
        echo "<tr>
                <td>{$doacao['id']}</td>
                <td>{$doacao['valor']}</td>
                <td>{$doacao['data_doacao']}</td>
                <td>{$doacao['descricao']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<script>alert('Nenhuma doação encontrada para este CPF.');</script>";
}

$conn = null; // Fecha a conexão com o banco de dados
?>
