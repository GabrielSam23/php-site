<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Excluir likes relacionados ao anúncio
    $sql_delete_likes = "DELETE FROM likes WHERE product_id = '$product_id'";
    if (!mysqli_query($conn, $sql_delete_likes)) {
        // Tratar erro, se necessário
    }

    // Excluir comentários relacionados ao anúncio
    $sql_delete_comments = "DELETE FROM comments WHERE product_id = '$product_id'";
    if (!mysqli_query($conn, $sql_delete_comments)) {
        // Tratar erro, se necessário
    }

    // Excluir o próprio anúncio
    $sql_delete_product = "DELETE FROM products WHERE id = '$product_id'";
    if (mysqli_query($conn, $sql_delete_product)) {
        header("Location: my_products.php");
        exit();
    } else {
        // Tratar erro, se necessário
    }
} else {
    header("Location: my_products.php");
    exit();
}
?>
