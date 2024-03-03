<?php
session_start();
include 'db.php';

if(isset($_SESSION['user_id']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Verificar se o usuário já curtiu o produto
    $sql_check = "SELECT * FROM likes WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $result_check = mysqli_query($conn, $sql_check);

    if(mysqli_num_rows($result_check) > 0) {
        // Se o usuário já curtiu, remover a curtida
        $sql_delete = "DELETE FROM likes WHERE product_id = '$product_id' AND user_id = '$user_id'";
        if(mysqli_query($conn, $sql_delete)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Erro ao remover a curtida.";
        }
    } else {
        // Se o usuário ainda não curtiu, adicionar a curtida
        $sql_insert = "INSERT INTO likes (product_id, user_id) VALUES ('$product_id', '$user_id')";
        if(mysqli_query($conn, $sql_insert)) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Erro ao adicionar a curtida.";
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
