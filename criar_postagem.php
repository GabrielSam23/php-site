<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Verifica se o formulário de postagem foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o conteúdo da postagem do formulário
    $conteudo = $_POST["conteudo"];
    
    // Conecta ao banco de dados
    $conn = mysqli_connect("localhost", "root", "", "caminhoneiros");

    // Verifica a conexão
    if (!$conn) {
        die("Erro de conexão: " . mysqli_connect_error());
    }

    // Obtém o ID do usuário atualmente logado
    $usuario_id = $_SESSION["id"];

    // Insere a nova postagem no banco de dados
    $sql = "INSERT INTO postagens (usuario_id, conteudo) VALUES ('$usuario_id', '$conteudo')";
    if (mysqli_query($conn, $sql)) {
        // Redireciona de volta para o feed de postagens após a criação da postagem
        header("location: feed.php");
        exit;
    } else {
        echo "Erro ao criar postagem: " . mysqli_error($conn);
    }

    // Fecha a conexão
    mysqli_close($conn);
}
?>
