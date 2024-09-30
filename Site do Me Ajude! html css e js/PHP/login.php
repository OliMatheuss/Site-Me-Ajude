<?php
// Inclua o arquivo de conexão com o banco de dados
include 'db_connection.php'; // Conexão com o banco de dados
include 'usuario.php'; // Classe Usuario

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtendo os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificando se os campos estão vazios
    if (empty($email) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos.');</script>";
        exit;
    }

    try {
        // Preparando a consulta
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $conn->prepare($sql);
        
        // Bind dos parâmetros
        $stmt->bindParam(':email', $email);
        
        // Executando a consulta
        $stmt->execute();

        // Verificando se um usuário foi encontrado
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificando a senha
            if ($usuario['senha'] === $senha) {
                // Usuário encontrado e senha correta, redirecionar para a página desejada
                session_start();
                $_SESSION['email'] = $email; // Armazenando email na sessão
                header("Location: ../HTML/inicioSessao.html"); // Altere para a página que deseja redirecionar
                exit;
            } else {
                echo "<script>alert('Senha incorreta.');</script>";
            }
        } else {
            echo "<script>alert('Email não encontrado.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
    }
}
?>
