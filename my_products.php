<?php
session_start();
include 'db.php';

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Consulta para selecionar apenas os produtos do usuário logado
    $sql_my_products = "SELECT products.*, users.username AS author FROM products LEFT JOIN users ON products.user_id = users.id WHERE user_id = '$user_id'";
    $result_my_products = mysqli_query($conn, $sql_my_products);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Produtos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        <h2>Bem-vindo, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Visitante'; ?></h2>
        <div class="actions">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
                <a href="add_product.php">Adicionar Produto</a>
                <a href="index.php">Voltar para Página Inicial</a> <!-- Adiciona o link para a página inicial -->
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Cadastro</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="products">
        <h2>Meus Produtos</h2>
        <?php while($row = mysqli_fetch_assoc($result_my_products)): ?>
            <div class="product">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p>Preço: $ <?php echo $row['price']; ?></p>
                <form method="post" action="end_listing.php">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="submit" value="Encerrar Anúncio">
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
