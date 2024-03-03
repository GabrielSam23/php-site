<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processar os dados do formulário
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];
    $phone_number = $_POST['phone_number']; // Novo campo para o número de telefone

    // Verificar se todos os campos foram preenchidos
    if (!empty($title) && !empty($description) && !empty($price) && !empty($user_id) && !empty($phone_number)) {
        // Inserir os dados do produto no banco de dados
        $sql = "INSERT INTO products (title, description, price, user_id, phone_number) VALUES ('$title', '$description', '$price', '$user_id', '$phone_number')";
        if (mysqli_query($conn, $sql)) {
            // Redirecionar o usuário após o envio bem-sucedido
            header("Location: index.php");
            exit(); // Certifique-se de sair do script após o redirecionamento
        } else {
            $error = "Erro ao adicionar o produto.";
        }
    } else {
        $error = "Todos os campos são obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Adicionar Produto</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="title">Título:</label><br>
            <input type="text" id="title" name="title" required><br>

            <label for="description">Descrição:</label><br>
            <textarea id="description" name="description" rows="4" required></textarea><br>

            <label for="price">Preço:</label><br>
            <input type="number" id="price" name="price" step="0.01" required><br>

            <!-- Novo campo para o número de telefone -->
            <label for="phone_number">Número de Telefone:</label><br>
            <input type="text" id="phone_number" name="phone_number" required><br>

            <input type="submit" value="Adicionar Produto">
        </form>
        <?php if(isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
