<?php
session_start();
include 'db.php';

if(isset($_SESSION['user_id']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    // Verifica se o campo de comentário não está vazio
    if(empty($comment)) {
        $_SESSION['error'] = "O campo de comentário não pode estar vazio.";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }

    $sql = "INSERT INTO comments (product_id, user_id, comment) VALUES ('$product_id', '$user_id', '$comment')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Erro ao adicionar o comentário.";
    }
} else {
    header("Location: index.php");
    exit();
}
?>
