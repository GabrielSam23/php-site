<?php
session_start();
include 'db.php';

// Consulta para selecionar todos os produtos
$sql = "SELECT products.*, users.username AS author FROM products LEFT JOIN users ON products.user_id = users.id";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        <h2>Bem-vindo, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Visitante'; ?></h2>
        <div class="actions">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php">Logout</a>
                <a href="add_product.php">Adicionar Produto</a>
                <a href="my_products.php">Meus Produtos</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Cadastro</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if(!isset($_SESSION['user_id'])): ?>
    <div class="intro">
        <p>Bem-vindo ao Marketplace! Somos uma empresa especializada em vendas e trocas na região de San Andreas. Oferecemos uma ampla variedade de produtos para atender às suas necessidades. Faça login ou cadastre-se para começar a explorar nossas ofertas.</p>
        <p>Não tem uma conta? <a href="signup.php">Cadastre-se</a> agora mesmo.</p>
    </div>
    <?php endif; ?>
    <div class="products">
        <?php 
        if ($result && mysqli_num_rows($result) > 0):
            while($row = mysqli_fetch_assoc($result)): ?>
                <div class="product">
                    <h3><?php echo $row['title']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <p>Preço: $ <?php echo $row['price']; ?></p>
                    <?php if(isset($row['phone_number'])): ?>
                        <div class="phone-number-container">
                            <span class="phone-number" style="display: inline-block;"><?php echo $row['phone_number']; ?></span>
                            <button onclick="copyPhoneNumber(this, '<?php echo $row['phone_number']; ?>')" style="vertical-align: middle;">Copiar</button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulário para adicionar comentários -->
                    <form method="post" action="comment.php">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <textarea name="comment" placeholder="Comentar"></textarea><br>
                        <input type="submit" value="Comentar">
                    </form>

                    <!-- Exibir os comentários -->
                    <div class="comments">
                        <?php
                        $product_id = $row['id'];
                        $sql_comments = "SELECT comments.*, users.username AS commenter FROM comments LEFT JOIN users ON comments.user_id = users.id WHERE product_id = '$product_id'";
                        $result_comments = mysqli_query($conn, $sql_comments);
                        while ($comment_row = mysqli_fetch_assoc($result_comments)) {
                            echo "<p><strong>{$comment_row['commenter']}</strong>: {$comment_row['comment']} <span class='time'>(" . date('H:i', strtotime($comment_row['created_at'])) . ")</span></p>";
                        }
                        ?>
                    </div>

                    <!-- Botão de curtir -->
                    <?php
                    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                    $sql_like_check = "SELECT * FROM likes WHERE product_id = '$product_id' AND user_id = '$user_id'";
                    $result_like_check = mysqli_query($conn, $sql_like_check);
                    $already_liked = mysqli_num_rows($result_like_check) > 0;
                    ?>
                    <form method="post" action="like.php">
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="submit" value="<?php echo $already_liked ? 'Descurtir' : 'Curtir'; ?>">
                    </form>

                    <!-- Exibir número de curtidas -->
                    <?php
                    $sql_likes = "SELECT COUNT(*) AS likes_count FROM likes WHERE product_id = '$product_id'";
                    $result_likes = mysqli_query($conn, $sql_likes);
                    $likes_count = mysqli_fetch_assoc($result_likes)['likes_count'];
                    echo "<p class='likes'>{$likes_count} curtidas</p>";
                    ?>
                </div>
            <?php endwhile;
        else: ?>
            <p>Nenhum produto disponível no momento.</p>
        <?php endif; ?>
    </div>

    <script>
    function copyPhoneNumber(button, phoneNumber) {
        var $button = button;
        var $defaultMessage = $button.querySelector('.default-message');
        var $successMessage = $button.querySelector('.success-message');

        navigator.clipboard.writeText(phoneNumber).then(function() {
            $defaultMessage.classList.add('hidden');
            $successMessage.classList.remove('hidden');

            // Reset to default state after 3 seconds
            setTimeout(function() {
                $defaultMessage.classList.remove('hidden');
                $successMessage.classList.add('hidden');
            }, 3000);
        }, function(err) {
            console.error('Erro ao copiar número de telefone: ', err);
        });
    }
    </script>
</body>
</html>
