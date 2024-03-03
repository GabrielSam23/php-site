<?php
session_start();
include_once "db.php";

// Verificar se o formulário foi enviado
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar os dados do formulário
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Extrair a URL da imagem da descrição
    $image_url = extract_image_url($description);

    // Inserir os detalhes do produto no banco de dados
    $sql = "INSERT INTO products (title, description, price, user_id, image_url) VALUES ('$title', '$description', '$price', '$user_id', '$image_url')";
    if(mysqli_query($conn, $sql)){
        echo "success";
    }else{
        echo "Ocorreu um erro ao adicionar o produto. Por favor, tente novamente!";
    }
}

// Função para extrair a URL da imagem da descrição
function extract_image_url($description) {
    // Expressão regular para encontrar URLs de imagens
    $pattern = '/\bhttps?:\/\/\S+\.(?:png|jpe?g|gif)\b/i';
    if(preg_match($pattern, $description, $matches)) {
        return $matches[0];
    } else {
        return "";
    }
}
?>
